<?php

namespace App\Filament\Resources\DownloadDocuments;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Resources\DownloadDocuments\Pages\CreateDownloadDocument;
use App\Filament\Resources\DownloadDocuments\Pages\EditDownloadDocument;
use App\Filament\Resources\DownloadDocuments\Pages\ListDownloadDocuments;
use App\Filament\Resources\DownloadDocuments\Schemas\DownloadDocumentForm;
use App\Filament\Resources\DownloadDocuments\Tables\DownloadDocumentsTable;
use App\Models\DownloadDocument;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DownloadDocumentResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = DownloadDocument::class;

    protected static ?string $modelLabel = 'dokumen unduhan';

    protected static ?string $pluralModelLabel = 'Dokumen Unduhan';

    protected static ?string $navigationLabel = 'Dokumen Unduhan';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Publik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static function allowedRoles(): array
    {
        return ['Admin', 'Guru'];
    }

    public static function form(Schema $schema): Schema
    {
        return DownloadDocumentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DownloadDocumentsTable::configure($table);
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
            'index' => ListDownloadDocuments::route('/'),
            'create' => CreateDownloadDocument::route('/create'),
            'edit' => EditDownloadDocument::route('/{record}/edit'),
        ];
    }
}
