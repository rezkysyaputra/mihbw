<?php

namespace App\Filament\Resources\DownloadDocuments\Pages;

use App\Filament\Resources\DownloadDocuments\DownloadDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDownloadDocument extends EditRecord
{
    protected static string $resource = DownloadDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
