<?php

namespace App\Filament\Resources\HomepageImages\Tables;

use App\Models\HomepageImage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class HomepageImagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('galleryItem.image')
                    ->label('Foto')
                    ->disk('public')
                    ->visibility('public')
                    ->imageSize(56)
                    ->square(),
                TextColumn::make('galleryItem.title')
                    ->label('Judul foto')
                    ->description(fn (HomepageImage $record): ?string => $record->galleryItem?->album?->title)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('section')
                    ->label('Area')
                    ->formatStateUsing(fn (string $state): string => HomepageImage::sectionOptions()[$state] ?? $state)
                    ->badge()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Tampil' : 'Nonaktif')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'gray'),
            ])
            ->filters([
                SelectFilter::make('section')
                    ->label('Area')
                    ->options(HomepageImage::sectionOptions()),
                TernaryFilter::make('is_active')
                    ->label('Status tampil')
                    ->trueLabel('Hanya yang tampil')
                    ->falseLabel('Hanya yang nonaktif'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
