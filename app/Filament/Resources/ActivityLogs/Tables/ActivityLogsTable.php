<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use App\Filament\Support\PortalResourceKit;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return PortalResourceKit::activityLogTable($table);
    }
}
