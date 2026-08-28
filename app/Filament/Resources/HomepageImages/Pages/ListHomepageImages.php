<?php

namespace App\Filament\Resources\HomepageImages\Pages;

use App\Filament\Resources\HomepageImages\HomepageImageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHomepageImages extends ListRecords
{
    protected static string $resource = HomepageImageResource::class;

    public function getSubheading(): ?string
    {
        return 'Pilih foto dari Galeri, tentukan area tampil, lalu atur urutannya. Gunakan filter Area saat melakukan drag-and-drop.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Pilih Gambar'),
        ];
    }
}
