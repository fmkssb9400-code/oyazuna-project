<?php

namespace App\Filament\Resources\SentEmailResource\Pages;

use App\Filament\Resources\SentEmailResource;
use Filament\Resources\Pages\ListRecords;

class ListSentEmails extends ListRecords
{
    protected static string $resource = SentEmailResource::class;
}
