<?php

namespace App\Filament\Resources\HomepageImages\Schemas;

use App\Models\GalleryItem;
use App\Models\HomepageImage;
use App\Support\PublicImage;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Unique;

class HomepageImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('section')
                    ->label('Area tampilan')
                    ->options(HomepageImage::sectionOptions())
                    ->helperText(fn (Get $get): string => match ($get('section')) {
                        HomepageImage::SECTION_HERO => 'Maksimal 5 gambar pertama akan tampil pada carousel.',
                        HomepageImage::SECTION_ACTIVITIES => 'Maksimal 8 gambar pertama akan tampil pada Visual Kegiatan.',
                        HomepageImage::SECTION_HIGHLIGHTS => 'Maksimal 3 gambar pertama akan tampil pada Galeri Singkat.',
                        HomepageImage::SECTION_CTA => 'Hanya gambar pertama yang digunakan pada ajakan PPDB.',
                        default => 'Pilih area beranda tempat gambar akan ditampilkan.',
                    })
                    ->required()
                    ->live(),
                Select::make('gallery_item_id')
                    ->label('Foto dari galeri')
                    ->relationship(
                        name: 'galleryItem',
                        titleAttribute: 'title',
                        modifyQueryUsing: fn (Builder $query): Builder => $query
                            ->where('status', 'published')
                            ->with('album')
                            ->orderBy('title')
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (GalleryItem $record): string => ($record->title ?: 'Tanpa judul')
                            .' - '.($record->album?->title ?: 'Tanpa album')
                    )
                    ->searchable(['title', 'caption'])
                    ->preload()
                    ->required()
                    ->live()
                    ->unique(
                        table: HomepageImage::class,
                        column: 'gallery_item_id',
                        ignoreRecord: true,
                        modifyRuleUsing: fn (Unique $rule, Get $get): Unique => $rule->where('section', $get('section'))
                    ),
                Placeholder::make('preview')
                    ->label('Pratinjau')
                    ->content(function (Get $get): HtmlString|string {
                        $item = GalleryItem::query()->find($get('gallery_item_id'));
                        $url = PublicImage::storageUrl($item?->image);

                        if (! $url) {
                            return 'Pilih foto galeri untuk melihat pratinjau.';
                        }

                        return new HtmlString(
                            '<img src="'.e($url).'" alt="'.e($item?->title ?: 'Pratinjau foto').'" class="h-40 w-full rounded-sm object-cover">'
                        );
                    })
                    ->columnSpanFull(),
                Textarea::make('alt_text')
                    ->label('Teks alternatif')
                    ->helperText('Jelaskan isi gambar secara singkat untuk aksesibilitas dan SEO. Jika kosong, keterangan foto galeri akan digunakan.')
                    ->rows(3)
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->required(),
                Toggle::make('is_active')
                    ->label('Tampilkan di beranda')
                    ->default(true),
            ])
            ->columns(2);
    }
}
