<?php

namespace App\Filament\Resources\AreaHubContentResource\Pages;

use App\Filament\Resources\AreaHubContentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAreaHubContent extends CreateRecord
{
    protected static string $resource = AreaHubContentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
