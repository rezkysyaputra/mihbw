<?php

namespace App\Filament\Resources\Extracurriculars\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class ExtracurricularForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::extracurricularForm($schema);
    }
}
