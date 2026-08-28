<?php

namespace App\Filament\Resources\PpdbDocuments\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class PpdbDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::ppdbDocumentTable($table);
    }
}
