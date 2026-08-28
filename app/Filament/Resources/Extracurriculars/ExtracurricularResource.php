<?php

namespace App\Filament\Resources\Extracurriculars;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Resources\Extracurriculars\Pages\CreateExtracurricular;
use App\Filament\Resources\Extracurriculars\Pages\EditExtracurricular;
use App\Filament\Resources\Extracurriculars\Pages\ListExtracurriculars;
use App\Filament\Resources\Extracurriculars\Schemas\ExtracurricularForm;
use App\Filament\Resources\Extracurriculars\Tables\ExtracurricularsTable;
use App\Models\Extracurricular;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExtracurricularResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = Extracurricular::class;

    protected static ?string $modelLabel = 'ekstrakurikuler';

    protected static ?string $pluralModelLabel = 'Ekstrakurikuler';

    protected static ?string $navigationLabel = 'Ekstrakurikuler';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return ExtracurricularForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExtracurricularsTable::configure($table);
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
            'index' => ListExtracurriculars::route('/'),
            'create' => CreateExtracurricular::route('/create'),
            'edit' => EditExtracurricular::route('/{record}/edit'),
        ];
    }
}
