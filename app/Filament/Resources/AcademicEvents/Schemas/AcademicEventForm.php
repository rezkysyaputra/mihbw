<?php

namespace App\Filament\Resources\AcademicEvents\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class AcademicEventForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::eventForm($schema);
    }
}
