<?php

namespace App\Filament\Resources\PpdbDocuments\Pages;

use App\Filament\Resources\PpdbDocuments\PpdbDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPpdbDocuments extends ListRecords
{
    protected static string $resource = PpdbDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
