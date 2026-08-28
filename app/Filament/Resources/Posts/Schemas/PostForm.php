<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::contentForm($schema, true);
    }
}
