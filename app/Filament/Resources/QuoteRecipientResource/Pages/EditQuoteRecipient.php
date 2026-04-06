<?php

namespace App\Filament\Resources\QuoteRecipientResource\Pages;

use App\Filament\Resources\QuoteRecipientResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuoteRecipient extends EditRecord
{
    protected static string $resource = QuoteRecipientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
