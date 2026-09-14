<?php

namespace App\Filament\Resources\AreaHubContentResource\Pages;

use App\Filament\Resources\AreaHubContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAreaHubContents extends ListRecords
{
    protected static string $resource = AreaHubContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('コンテンツを作成'),
        ];
    }
}
