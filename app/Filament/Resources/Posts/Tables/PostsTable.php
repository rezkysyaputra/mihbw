<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::simpleContentTable($table, 'post');
    }
}
