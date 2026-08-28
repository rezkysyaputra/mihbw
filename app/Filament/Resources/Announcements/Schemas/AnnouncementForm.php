<?php

namespace App\Filament\Resources\Announcements\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::contentForm($schema, true, 'announcements', true);
    }
}
