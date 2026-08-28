<?php

namespace App\Filament\Resources\PpdbDocuments\Pages;

use App\Filament\Resources\PpdbDocuments\PpdbDocumentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPpdbDocument extends EditRecord
{
    protected static string $resource = PpdbDocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
