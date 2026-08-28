<?php

namespace App\Filament\Resources\SchoolSettings;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Resources\SchoolSettings\Pages\CreateSchoolSetting;
use App\Filament\Resources\SchoolSettings\Pages\EditSchoolSetting;
use App\Filament\Resources\SchoolSettings\Pages\ListSchoolSettings;
use App\Filament\Resources\SchoolSettings\Schemas\SchoolSettingForm;
use App\Filament\Resources\SchoolSettings\Tables\SchoolSettingsTable;
use App\Models\SchoolSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SchoolSettingResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = SchoolSetting::class;

    protected static ?string $modelLabel = 'pengaturan sekolah';

    protected static ?string $pluralModelLabel = 'Pengaturan Sekolah';

    protected static ?string $navigationLabel = 'Pengaturan Sekolah';

    protected static string|\UnitEnum|null $navigationGroup = 'Sistem';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public static function form(Schema $schema): Schema
    {
        return SchoolSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SchoolSettingsTable::configure($table);
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
            'index' => ListSchoolSettings::route('/'),
            'create' => CreateSchoolSetting::route('/create'),
            'edit' => EditSchoolSetting::route('/{record}/edit'),
        ];
    }
}
