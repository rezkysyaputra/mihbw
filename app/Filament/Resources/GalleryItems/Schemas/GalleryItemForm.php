<?php

namespace App\Filament\Resources\GalleryItems\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class GalleryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::galleryItemForm($schema);
    }
}
