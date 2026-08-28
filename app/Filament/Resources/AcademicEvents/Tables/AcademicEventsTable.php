<?php

namespace App\Filament\Resources\AcademicEvents\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class AcademicEventsTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::basicTable($table);
    }
}
