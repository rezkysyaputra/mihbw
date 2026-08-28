<?php

namespace App\Filament\Resources\AcademicEvents\Pages;

use App\Filament\Resources\AcademicEvents\AcademicEventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAcademicEvent extends EditRecord
{
    protected static string $resource = AcademicEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
