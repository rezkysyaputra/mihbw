<?php

namespace App\Filament\Resources\Users\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::userTable($table);
    }
}
