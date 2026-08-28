<?php

namespace App\Filament\Resources\GalleryAlbums;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Resources\GalleryAlbums\Pages\CreateGalleryAlbum;
use App\Filament\Resources\GalleryAlbums\Pages\EditGalleryAlbum;
use App\Filament\Resources\GalleryAlbums\Pages\ListGalleryAlbums;
use App\Filament\Resources\GalleryAlbums\Schemas\GalleryAlbumForm;
use App\Filament\Resources\GalleryAlbums\Tables\GalleryAlbumsTable;
use App\Models\GalleryAlbum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GalleryAlbumResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = GalleryAlbum::class;

    protected static ?string $modelLabel = 'album galeri';

    protected static ?string $pluralModelLabel = 'Album Galeri';

    protected static ?string $navigationLabel = 'Album Galeri';

    protected static string|\UnitEnum|null $navigationGroup = 'Galeri';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolderOpen;

    public static function form(Schema $schema): Schema
    {
        return GalleryAlbumForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GalleryAlbumsTable::configure($table);
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
            'index' => ListGalleryAlbums::route('/'),
            'create' => CreateGalleryAlbum::route('/create'),
            'edit' => EditGalleryAlbum::route('/{record}/edit'),
        ];
    }
}
