<?php

namespace App\Filament\Resources\SchoolSettings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SchoolSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'info',
                        'contact' => 'success',
                        'social' => 'warning',
                        'ppdb' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'general' => 'Umum',
                        'contact' => 'Kontak',
                        'social' => 'Medsos',
                        'ppdb' => 'PPDB',
                        'academic' => 'Akademik',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                TextColumn::make('key')->label('Kunci Pengaturan')->searchable()->sortable(),
                TextColumn::make('value')->label('Nilai Pengaturan')->limit(60)->searchable(),
                TextColumn::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                SelectFilter::make('group')->label('Kategori')->options([
                    'general' => 'Umum / Identitas',
                    'contact' => 'Kontak & Alamat',
                    'social' => 'Media Sosial',
                    'ppdb' => 'PPDB',
                    'academic' => 'Akademik',
                ]),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
