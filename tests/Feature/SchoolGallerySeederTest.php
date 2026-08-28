<?php

namespace Tests\Feature;

use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use Database\Seeders\SchoolGallerySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SchoolGallerySeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_school_gallery_seeder_imports_curated_photos(): void
    {
        Storage::fake('public');

        $this->seed(SchoolGallerySeeder::class);

        $this->assertSame(3, GalleryAlbum::query()->count());
        $this->assertSame(12, GalleryItem::query()->where('status', 'published')->count());
        $this->assertSame(3, GalleryItem::query()->where('status', 'draft')->count());

        $this->assertDatabaseHas('gallery_albums', [
            'slug' => 'kegiatan-belajar',
            'cover_image' => 'gallery/school/presentasi-hasil-belajar.jpg',
            'status' => 'published',
        ]);

        Storage::disk('public')->assertExists([
            'gallery/school/presentasi-hasil-belajar.jpg',
            'gallery/school/pameran-hasil-karya.jpg',
            'gallery/school/kantor-madrasah.jpg',
        ]);
    }
}
