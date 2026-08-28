<?php

namespace App\Filament\Resources\PpdbDocuments;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Resources\PpdbDocuments\Pages\CreatePpdbDocument;
use App\Filament\Resources\PpdbDocuments\Pages\EditPpdbDocument;
use App\Filament\Resources\PpdbDocuments\Pages\ListPpdbDocuments;
use App\Filament\Resources\PpdbDocuments\Schemas\PpdbDocumentForm;
use App\Filament\Resources\PpdbDocuments\Tables\PpdbDocumentsTable;
use App\Models\PpdbDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PpdbDocumentResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = PpdbDocument::class;

    protected static ?string $modelLabel = 'dokumen PPDB';

    protected static ?string $pluralModelLabel = 'Dokumen PPDB';

    protected static ?string $navigationLabel = 'Dokumen PPDB';

    protected static string|\UnitEnum|null $navigationGroup = 'PPDB';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return PpdbDocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PpdbDocumentsTable::configure($table);
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
            'index' => ListPpdbDocuments::route('/'),
            'create' => CreatePpdbDocument::route('/create'),
            'edit' => EditPpdbDocument::route('/{record}/edit'),
        ];
    }
}
