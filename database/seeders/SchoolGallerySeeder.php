<?php

namespace Database\Seeders;

use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class SchoolGallerySeeder extends Seeder
{
    public function run(): void
    {
        GalleryItem::query()
            ->where('image', 'like', 'gallery/school/%')
            ->delete();

        foreach ($this->albums() as $albumData) {
            $items = collect($albumData['items'])
                ->filter(fn (array $item): bool => File::exists(base_path('album/'.$item['source'])))
                ->values();

            if ($items->isEmpty()) {
                continue;
            }

            $album = GalleryAlbum::updateOrCreate(
                ['slug' => $albumData['slug']],
                [
                    'title' => $albumData['title'],
                    'description' => null,
                    'cover_image' => $this->destinationFor($items[0]['source']),
                    'status' => 'published',
                ]
            );

            foreach ($items as $index => $itemData) {
                $source = base_path('album/'.$itemData['source']);
                $destination = $this->destinationFor($itemData['source']);

                $this->storeImage($source, $destination);

                GalleryItem::updateOrCreate(
                    [
                        'gallery_album_id' => $album->id,
                        'image' => $destination,
                    ],
                    [
                        'title' => null,
                        'caption' => null,
                        'sort_order' => $index + 1,
                        'status' => 'published',
                    ]
                );
            }
        }

        GalleryAlbum::query()
            ->whereNotIn('slug', collect($this->albums())->pluck('slug'))
            ->delete();
    }

    private function destinationFor(string $filename): string
    {
        return 'gallery/school/'.str(pathinfo($filename, PATHINFO_FILENAME))->slug()->value().'.jpg';
    }

    private function storeImage(string $source, string $destination): void
    {
        if (! extension_loaded('gd')) {
            Storage::disk('public')->put($destination, File::get($source));

            return;
        }

        $image = Image::read($source)
            ->orient()
            ->scaleDown(width: 1600, height: 1600)
            ->toJpeg(quality: 82, progressive: true, strip: true);

        Storage::disk('public')->put($destination, (string) $image);
    }

    private function albums(): array
    {
        return [
            [
                'title' => 'Kegiatan Pembelajaran',
                'slug' => 'kegiatan-pembelajaran',
                'items' => [
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.13.42 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.13.43 PM (1).jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.13.47 PM (1).jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.13.49 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.13.52 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.54.22 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.54.24 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.54.24 PM (1).jpeg'],
                ],
            ],
            [
                'title' => 'Kegiatan Akademik',
                'slug' => 'kegiatan-akademik',
                'items' => [
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.27.35 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.27.40 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.27.43 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.27.58 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.28.03 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.28.18 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.28.31 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.28.47 PM.jpeg'],
                ],
            ],
            [
                'title' => 'Kegiatan Madrasah',
                'slug' => 'kegiatan-madrasah',
                'items' => [
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.40.01 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.40.02 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.40.03 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.40.05 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.40.05 PM (1).jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 7.40.06 PM.jpeg'],
                ],
            ],
            [
                'title' => 'Fasilitas Madrasah',
                'slug' => 'fasilitas-madrasah',
                'items' => [
                    ['source' => 'WhatsApp Image 2026-06-12 at 8.00.23 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 8.00.24 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 8.00.46 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 8.00.46 PM (1).jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 8.00.47 PM.jpeg'],
                    ['source' => 'WhatsApp Image 2026-06-12 at 8.00.48 PM.jpeg'],
                ],
            ],
        ];
    }
}
