<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\DownloadDocument;
use Database\Seeders\PublicContentCleanupSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicContentCleanupSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_content_is_removed_from_publication(): void
    {
        Announcement::create([
            'title' => 'TEST PENGUMUMAN',
            'slug' => 'test-pengumuman',
            'body' => 'Lorem ipsum',
            'status' => 'published',
            'published_at' => now(),
        ]);

        DownloadDocument::create([
            'title' => 'Kalender Akademik Dummy',
            'slug' => 'kalender-akademik-dummy',
            'file_path' => 'documents/kalender-akademik.pdf',
            'status' => 'published',
        ]);

        $this->seed(PublicContentCleanupSeeder::class);

        $this->assertDatabaseHas('announcements', [
            'slug' => 'test-pengumuman',
            'status' => 'draft',
        ]);

        $this->assertDatabaseHas('download_documents', [
            'slug' => 'kalender-akademik-dummy',
            'status' => 'draft',
        ]);
    }
}
