<?php

namespace App\Filament\Resources\AreaHubContentResource\Pages;

use App\Filament\Resources\AreaHubContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAreaHubContent extends EditRecord
{
    protected static string $resource = AreaHubContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
