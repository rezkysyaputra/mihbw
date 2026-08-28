<?php

namespace App\Filament\Resources\PpdbApplicants\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class PpdbApplicantForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::ppdbForm($schema);
    }
}
