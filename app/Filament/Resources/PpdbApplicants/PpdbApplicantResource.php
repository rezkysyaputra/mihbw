<?php

namespace App\Filament\Resources\PpdbApplicants;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Resources\PpdbApplicants\Pages\CreatePpdbApplicant;
use App\Filament\Resources\PpdbApplicants\Pages\EditPpdbApplicant;
use App\Filament\Resources\PpdbApplicants\Pages\ListPpdbApplicants;
use App\Filament\Resources\PpdbApplicants\Schemas\PpdbApplicantForm;
use App\Filament\Resources\PpdbApplicants\Tables\PpdbApplicantsTable;
use App\Models\PpdbApplicant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PpdbApplicantResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = PpdbApplicant::class;

    protected static ?string $modelLabel = 'pendaftar PPDB';

    protected static ?string $pluralModelLabel = 'Pendaftar PPDB';

    protected static ?string $navigationLabel = 'Pendaftar PPDB';

    protected static string|\UnitEnum|null $navigationGroup = 'PPDB';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    public static function form(Schema $schema): Schema
    {
        return PpdbApplicantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PpdbApplicantsTable::configure($table);
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
            'index' => ListPpdbApplicants::route('/'),
            'create' => CreatePpdbApplicant::route('/create'),
            'edit' => EditPpdbApplicant::route('/{record}/edit'),
        ];
    }
}
