<?php

namespace App\Filament\Resources\PartnerInquiryResource\Pages;

use App\Filament\Resources\PartnerInquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePartnerInquiries extends ManageRecords
{
    protected static string $resource = PartnerInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
