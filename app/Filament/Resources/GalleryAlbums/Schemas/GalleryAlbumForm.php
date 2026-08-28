<?php

namespace App\Filament\Resources\GalleryAlbums\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class GalleryAlbumForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::albumForm($schema);
    }
}
