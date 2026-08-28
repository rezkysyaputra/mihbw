<?php

namespace App\Filament\Resources\DownloadDocuments\Pages;

use App\Filament\Resources\DownloadDocuments\DownloadDocumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDownloadDocuments extends ListRecords
{
    protected static string $resource = DownloadDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
