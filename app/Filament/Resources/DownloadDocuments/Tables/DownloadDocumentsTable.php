<?php

namespace App\Filament\Resources\DownloadDocuments\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class DownloadDocumentsTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::basicTable($table);
    }
}
