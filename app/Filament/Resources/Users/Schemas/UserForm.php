<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Filament\Support\PortalResourceKit;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return PortalResourceKit::userForm($schema);
    }
}
