<?php

namespace App\Filament\Resources\QuoteDataResource\Pages;

use App\Filament\Resources\QuoteDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuoteData extends EditRecord
{
    protected static string $resource = QuoteDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
