<?php

namespace App\Filament\Resources\Announcements\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class AnnouncementsTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::simpleContentTable($table, 'announcement');
    }
}
