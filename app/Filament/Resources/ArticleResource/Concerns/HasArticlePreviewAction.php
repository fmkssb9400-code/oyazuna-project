<?php

namespace App\Filament\Resources\ArticleResource\Concerns;

use Filament\Actions\Action;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait HasArticlePreviewAction
{
    protected function getPreviewAction(): Action
    {
        return Action::make('preview')
            ->label('プレビュー')
            ->icon('heroicon-o-eye')
            ->color('gray')
            ->action(function () {
                $url = $this->buildArticlePreviewUrl();

                $this->js('window.open(' . json_encode($url) . ', "_blank")');
            });
    }

    protected function buildArticlePreviewUrl(): string
    {
        $data = Arr::except($this->form->getRawState(), ['featured_image', 'supervisor_avatar']);

        $rawState = $this->form->getRawState();
        $data['featured_image_url'] = $this->resolvePreviewImageUrl($rawState['featured_image'] ?? null);
        $data['supervisor_avatar_url'] = $this->resolvePreviewImageUrl($rawState['supervisor_avatar'] ?? null);
        $data['id'] = $this->record?->id;

        $token = (string) Str::uuid();

        Cache::put("article_preview:{$token}", $data, now()->addMinutes(30));

        return route('admin.articles.preview', $token);
    }

    protected function resolvePreviewImageUrl(mixed $value): ?string
    {
        if (is_array($value)) {
            $value = Arr::first($value);
        }

        if ($value instanceof TemporaryUploadedFile) {
            try {
                return $value->temporaryUrl();
            } catch (\Throwable $e) {
                return null;
            }
        }

        if (!is_string($value) || $value === '') {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return Storage::disk('public')->url($value);
    }
}
