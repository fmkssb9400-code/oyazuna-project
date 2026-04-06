<?php

namespace App\Filament\Resources\EmailSettingsResource\Pages;

use App\Filament\Resources\EmailSettingsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmailSettings extends CreateRecord
{
    protected static string $resource = EmailSettingsResource::class;
}
