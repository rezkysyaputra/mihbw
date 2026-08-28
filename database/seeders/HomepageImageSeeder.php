<?php

namespace Database\Seeders;

use App\Models\GalleryItem;
use App\Models\HomepageImage;
use Illuminate\Database\Seeder;

class HomepageImageSeeder extends Seeder
{
    public function run(): void
    {
        if (HomepageImage::query()->exists()) {
            return;
        }

        $galleryItems = GalleryItem::query()
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        foreach ([
            HomepageImage::SECTION_HERO => $galleryItems->take(5),
            HomepageImage::SECTION_ACTIVITIES => $galleryItems->take(8),
            HomepageImage::SECTION_HIGHLIGHTS => $galleryItems->skip(1)->take(3),
            HomepageImage::SECTION_CTA => $galleryItems->skip(4)->take(1),
        ] as $section => $items) {
            foreach ($items->values() as $index => $item) {
                HomepageImage::create([
                    'gallery_item_id' => $item->id,
                    'section' => $section,
                    'alt_text' => $item->caption ?: $item->title,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}
