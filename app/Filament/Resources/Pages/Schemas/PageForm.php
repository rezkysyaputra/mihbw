<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::pageForm($schema);
    }
}
