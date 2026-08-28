<?php

namespace App\Filament\Resources\Teachers\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class TeachersTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::basicTable($table, 'photo');
    }
}
