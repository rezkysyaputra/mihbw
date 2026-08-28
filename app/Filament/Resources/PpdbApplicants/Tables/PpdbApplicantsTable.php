<?php

namespace App\Filament\Resources\PpdbApplicants\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class PpdbApplicantsTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::ppdbTable($table);
    }
}
