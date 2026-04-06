<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class EditCompany extends EditRecord
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        \Log::info('=== COMPANY SAVE DEBUG ===');
        \Log::info('Company ID: ' . $this->record->id);
        \Log::info('Company Name: ' . $this->record->name);
        \Log::info('Form Data Keys: ' . implode(', ', array_keys($data)));
        \Log::info('Form Data (before save):', [
            'is_featured' => $data['is_featured'] ?? 'NOT_SET',
            'sort_order' => $data['sort_order'] ?? 'NOT_SET',
            'recommend_score' => $data['recommend_score'] ?? 'NOT_SET',
            'safety_score' => $data['safety_score'] ?? 'NOT_SET',
            'performance_score' => $data['performance_score'] ?? 'NOT_SET',
            'email_quote' => $data['email_quote'] ?? 'NOT_SET',
            'phone' => $data['phone'] ?? 'NOT_SET',
            'address_text' => $data['address_text'] ?? 'NOT_SET',
            'published_at' => $data['published_at'] ?? 'NOT_SET',
            'tags' => $data['tags'] ?? 'NOT_SET',
            'areas' => $data['areas'] ?? 'NOT_SET',
            'service_categories' => $data['service_categories'] ?? 'NOT_SET',
            'logo_path' => $data['logo_path'] ?? 'NOT_SET',
            'ranking_logo_path' => $data['ranking_logo_path'] ?? 'NOT_SET',
        ]);
        
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        \Log::info('=== BEFORE UPDATE ===');
        \Log::info('Record current state:', [
            'is_featured' => $record->is_featured,
            'sort_order' => $record->sort_order,
            'recommend_score' => $record->recommend_score,
            'safety_score' => $record->safety_score,
            'performance_score' => $record->performance_score,
            'email_quote' => $record->email_quote,
            'phone' => $record->phone,
            'address_text' => $record->address_text,
            'published_at' => $record->published_at ? $record->published_at->format('Y-m-d H:i:s') : null,
        ]);

        try {
            $record->update($data);
            \Log::info('Update successful');
        } catch (\Exception $e) {
            \Log::error('Update failed: ' . $e->getMessage());
            \Log::error('Exception trace: ' . $e->getTraceAsString());
            throw $e;
        }

        return $record;
    }

    protected function afterSave(): void
    {
        \Log::info('=== AFTER SAVE ===');
        \Log::info('Record final state:', [
            'is_featured' => $this->record->is_featured,
            'sort_order' => $this->record->sort_order,
            'recommend_score' => $this->record->recommend_score,
            'safety_score' => $this->record->safety_score,
            'performance_score' => $this->record->performance_score,
            'email_quote' => $this->record->email_quote,
            'phone' => $this->record->phone,
            'address_text' => $this->record->address_text,
            'published_at' => $this->record->published_at ? $this->record->published_at->format('Y-m-d H:i:s') : null,
        ]);
        \Log::info('=== END COMPANY SAVE DEBUG ===');
    }

    protected function getRedirectUrl(): string
    {
        // Stay on the edit page to verify changes were saved
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function getSavedNotification(): ?\Filament\Notifications\Notification
    {
        return Notification::make()
            ->success()
            ->title('保存成功')
            ->body('会社情報が正常に更新されました。');
    }
}
