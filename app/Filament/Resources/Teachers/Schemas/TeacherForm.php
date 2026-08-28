<?php

namespace App\Filament\Resources\Teachers\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::teacherForm($schema);
    }
}
