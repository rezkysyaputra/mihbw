<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\SchoolSetting;
use Database\Seeders\OfficialTeacherSeeder;
use Database\Seeders\SchoolProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SchoolProfileSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(OfficialTeacherSeeder::class);
        $this->seed(SchoolProfileSeeder::class);
    }

    public function test_recommended_school_profile_pages_are_published(): void
    {
        $this->assertSame(
            [
                'kurikulum',
                'sambutan-kepala-madrasah',
                'sejarah-singkat',
                'struktur-organisasi',
            ],
            Page::query()
                ->whereIn('slug', [
                    'kurikulum',
                    'sambutan-kepala-madrasah',
                    'sejarah-singkat',
                    'struktur-organisasi',
                ])
                ->where('status', 'published')
                ->orderBy('slug')
                ->pluck('slug')
                ->all()
        );

        $this->assertSame(
            'Kecamatan Lalonggasumeeto, Kabupaten Konawe, Sulawesi Tenggara',
            SchoolSetting::query()->where('key', 'address')->value('value')
        );
    }

    public function test_recommended_profile_pages_render_publicly(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Bersama mendampingi tumbuh kembang peserta didik')
            ->assertSee('Hasnah, S.Pd.I')
            ->assertSee("Wassalamu'alaikum warahmatullahi wabarakatuh.", false)
            ->assertDontSee('Baca sambutan lengkap')
            ->assertDontSee('href="'.route('pages.show', 'sambutan-kepala-madrasah').'"', false)
            ->assertDontSee('href="'.route('pages.show', 'sejarah-singkat').'"', false)
            ->assertDontSee('href="'.route('pages.show', 'fasilitas').'"', false);

        $this->get('/profil/tentang-madrasah')
            ->assertOk()
            ->assertSee('Tentang MI Hubbul Wathan')
            ->assertSee('Profil Sekolah')
            ->assertSee('Sejarah Singkat')
            ->assertSee('Fasilitas');

        $this->get('/profil/kurikulum')
            ->assertOk()
            ->assertSee('Fokus Pembelajaran');

        $this->get('/profil/struktur-organisasi')
            ->assertOk()
            ->assertSee('Hasnah, S.Pd.I')
            ->assertSee('Pengelola Madrasah');

        $this->get('/profil/kontak')
            ->assertOk()
            ->assertSee('Lokasi Madrasah')
            ->assertSee('Kecamatan Lalonggasumeeto, Kabupaten Konawe, Sulawesi Tenggara')
            ->assertSee('google.com/maps', false);
    }
}
