<?php

namespace App\Filament\Resources\GalleryAlbums\Pages;

use App\Filament\Resources\GalleryAlbums\GalleryAlbumResource;
use App\Models\GalleryItem;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGalleryAlbum extends EditRecord
{
    protected static string $resource = GalleryAlbumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $bulkPhotos = $this->data['bulk_photos'] ?? [];

        if (!empty($bulkPhotos) && is_array($bulkPhotos)) {
            $existingCount = $this->record->items()->count();
            foreach ($bulkPhotos as $index => $photoPath) {
                GalleryItem::create([
                    'gallery_album_id' => $this->record->id,
                    'title' => $this->record->title . ' Foto ' . ($existingCount + $index + 1),
                    'image' => $photoPath,
                    'caption' => 'Dokumentasi ' . $this->record->title,
                    'sort_order' => $existingCount + $index,
                    'status' => 'published',
                ]);
            }
        }
    }
}
