<?php

namespace App\Filament\Resources\GalleryItems\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class GalleryItemsTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::basicTable($table, 'title');
    }
}
