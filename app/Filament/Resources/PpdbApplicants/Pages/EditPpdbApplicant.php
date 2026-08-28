<?php

namespace App\Filament\Resources\PpdbApplicants\Pages;

use App\Filament\Resources\PpdbApplicants\PpdbApplicantResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPpdbApplicant extends EditRecord
{
    protected static string $resource = PpdbApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
