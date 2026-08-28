<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::simpleContentTable($table);
    }
}
