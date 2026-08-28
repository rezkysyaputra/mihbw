<?php

namespace App\Filament\Resources\GalleryAlbums\Pages;

use App\Filament\Resources\GalleryAlbums\GalleryAlbumResource;
use App\Models\GalleryItem;
use Filament\Resources\Pages\CreateRecord;

class CreateGalleryAlbum extends CreateRecord
{
    protected static string $resource = GalleryAlbumResource::class;

    protected function afterCreate(): void
    {
        $bulkPhotos = $this->data['bulk_photos'] ?? [];

        if (!empty($bulkPhotos) && is_array($bulkPhotos)) {
            foreach ($bulkPhotos as $index => $photoPath) {
                GalleryItem::create([
                    'gallery_album_id' => $this->record->id,
                    'title' => $this->record->title . ' Foto ' . ($index + 1),
                    'image' => $photoPath,
                    'caption' => 'Dokumentasi ' . $this->record->title,
                    'sort_order' => $index,
                    'status' => 'published',
                ]);
            }
        }
    }
}
