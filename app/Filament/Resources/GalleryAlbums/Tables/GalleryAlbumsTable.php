<?php

namespace App\Filament\Resources\GalleryAlbums\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class GalleryAlbumsTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::basicTable($table);
    }
}
