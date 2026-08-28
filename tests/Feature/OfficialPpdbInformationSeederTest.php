<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Extracurricular;
use App\Models\Page;
use App\Models\SchoolSetting;
use Database\Seeders\OfficialPpdbInformationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OfficialPpdbInformationSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_official_ppdb_information_is_imported_from_poster(): void
    {
        Storage::fake('public');

        $this->seed(OfficialPpdbInformationSeeder::class);

        $announcement = Announcement::query()
            ->where('slug', 'ppdb-mi-hubbul-wathan-2026-2027')
            ->firstOrFail();

        $this->assertSame('announcements/ppdb-2026-2027.jpg', $announcement->cover_image);
        $this->assertStringContainsString('2 Juni 2026', $announcement->body);
        $this->assertStringContainsString('085396590157', $announcement->body);

        Storage::disk('public')->assertExists('announcements/ppdb-2026-2027.jpg');

        $this->assertSame(
            ['Pramuka', 'Seni Tari', 'TBTQ', 'Tahfidz'],
            Extracurricular::query()->where('status', 'published')->orderBy('name')->pluck('name')->all()
        );

        $this->assertStringContainsString(
            'Ruang perpustakaan',
            Page::query()->where('slug', 'fasilitas')->value('body')
        );

        $this->assertSame(
            '085396590157',
            SchoolSetting::query()->where('key', 'phone')->value('value')
        );

        $this->assertSame(
            [
                'email' => 'yppm.hubbulwathan@gmail.com',
                'facebook' => 'yppm.hubbulwathan',
                'instagram' => 'yppm.hubbulwathan',
            ],
            SchoolSetting::query()
                ->whereIn('key', ['email', 'facebook', 'instagram'])
                ->orderBy('key')
                ->pluck('value', 'key')
                ->all()
        );

        $contactBody = Page::query()->where('slug', 'kontak')->value('body');

        $this->assertStringContainsString('yppm.hubbulwathan@gmail.com', $contactBody);
        $this->assertStringContainsString('instagram.com/yppm.hubbulwathan', $contactBody);
        $this->assertStringContainsString('facebook.com/yppm.hubbulwathan', $contactBody);
    }
}
