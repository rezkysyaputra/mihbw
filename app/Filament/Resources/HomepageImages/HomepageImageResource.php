<?php

namespace App\Filament\Resources\HomepageImages;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Resources\HomepageImages\Pages\CreateHomepageImage;
use App\Filament\Resources\HomepageImages\Pages\EditHomepageImage;
use App\Filament\Resources\HomepageImages\Pages\ListHomepageImages;
use App\Filament\Resources\HomepageImages\Schemas\HomepageImageForm;
use App\Filament\Resources\HomepageImages\Tables\HomepageImagesTable;
use App\Models\HomepageImage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HomepageImageResource extends Resource
{
    use HasRoleAccess;

    protected static ?string $model = HomepageImage::class;

    protected static ?string $modelLabel = 'gambar beranda';

    protected static ?string $pluralModelLabel = 'Tampilan Beranda';

    protected static ?string $navigationLabel = 'Tampilan Beranda';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten Publik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return HomepageImageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomepageImagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomepageImages::route('/'),
            'create' => CreateHomepageImage::route('/create'),
            'edit' => EditHomepageImage::route('/{record}/edit'),
        ];
    }
}
