<?php

namespace App\Filament\Resources\QuoteDataResource\Pages;

use App\Filament\Resources\QuoteDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuoteData extends ListRecords
{
    protected static string $resource = QuoteDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
