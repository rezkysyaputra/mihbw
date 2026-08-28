<?php

namespace App\Filament\Resources\PpdbApplicants\Pages;

use App\Filament\Resources\PpdbApplicants\PpdbApplicantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPpdbApplicants extends ListRecords
{
    protected static string $resource = PpdbApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
