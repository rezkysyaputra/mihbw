<?php

namespace App\Filament\Resources\AcademicEvents\Pages;

use App\Filament\Resources\AcademicEvents\AcademicEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAcademicEvents extends ListRecords
{
    protected static string $resource = AcademicEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
