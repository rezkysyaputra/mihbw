<?php

namespace App\Filament\Resources\Extracurriculars\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class ExtracurricularsTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::basicTable($table, 'name');
    }
}
