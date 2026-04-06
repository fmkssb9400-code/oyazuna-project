<?php

namespace App\Filament\Resources\EmailSettingsResource\Pages;

use App\Filament\Resources\EmailSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmailSettings extends EditRecord
{
    protected static string $resource = EmailSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
