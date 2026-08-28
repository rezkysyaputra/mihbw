<?php

namespace App\Filament\Resources\AcademicEvents;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Resources\AcademicEvents\Pages\CreateAcademicEvent;
use App\Filament\Resources\AcademicEvents\Pages\EditAcademicEvent;
use App\Filament\Resources\AcademicEvents\Pages\ListAcademicEvents;
use App\Filament\Resources\AcademicEvents\Schemas\AcademicEventForm;
use App\Filament\Resources\AcademicEvents\Tables\AcademicEventsTable;
use App\Models\AcademicEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AcademicEventResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = AcademicEvent::class;

    protected static ?string $modelLabel = 'agenda akademik';

    protected static ?string $pluralModelLabel = 'Kalender Akademik';

    protected static ?string $navigationLabel = 'Kalender Akademik';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDateRange;

    protected static function allowedRoles(): array
    {
        return ['Admin', 'Guru'];
    }

    public static function form(Schema $schema): Schema
    {
        return AcademicEventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcademicEventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAcademicEvents::route('/'),
            'create' => CreateAcademicEvent::route('/create'),
            'edit' => EditAcademicEvent::route('/{record}/edit'),
        ];
    }
}
