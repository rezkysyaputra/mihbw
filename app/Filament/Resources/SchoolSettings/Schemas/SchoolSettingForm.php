<?php

namespace App\Filament\Resources\SchoolSettings\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class SchoolSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::settingForm($schema);
    }
}
