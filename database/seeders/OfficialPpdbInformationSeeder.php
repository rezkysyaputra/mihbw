<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Extracurricular;
use App\Models\Page;
use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OfficialPpdbInformationSeeder extends Seeder
{
    public function run(): void
    {
        $posterSource = base_path('album/WhatsApp Image 2026-06-12 at 7.04.15 PM.jpeg');
        $posterPath = 'announcements/ppdb-2026-2027.jpg';

        if (File::exists($posterSource)) {
            Storage::disk('public')->put($posterPath, File::get($posterSource));
        }

        Announcement::updateOrCreate(
            ['slug' => 'ppdb-mi-hubbul-wathan-2026-2027'],
            [
                'title' => 'Penerimaan Peserta Didik Baru Tahun Ajaran 2026/2027',
                'excerpt' => 'Pendaftaran peserta didik baru MI Hubbul Wathan Toli-Toli dibuka mulai 2 Juni 2026.',
                'body' => <<<'HTML'
<p>Pendaftaran peserta didik baru MI Hubbul Wathan Toli-Toli untuk tahun ajaran 2026/2027 dibuka mulai <strong>2 Juni 2026</strong>.</p>
<h2>Syarat Pendaftaran</h2>
<ul>
    <li>Fotokopi Akta Kelahiran dan Kartu Keluarga.</li>
    <li>Fotokopi ijazah TK, jika ada.</li>
    <li>Fotokopi KIP, KPS, atau PKH bagi yang memiliki.</li>
    <li>Berkas dimasukkan ke dalam map untuk verifikasi di madrasah.</li>
</ul>
<h2>Fasilitas Sekolah</h2>
<ul>
    <li>Ruang kelas luas.</li>
    <li>Ruang perpustakaan.</li>
    <li>Ruang UKS.</li>
    <li>Lapangan olahraga.</li>
</ul>
<h2>Ekstrakurikuler</h2>
<ul>
    <li>Tahfidz.</li>
    <li>TBTQ.</li>
    <li>Seni Tari.</li>
    <li>Pramuka.</li>
</ul>
<p>Informasi pendaftaran melalui WhatsApp: <strong>085396590157</strong>.</p>
HTML,
                'cover_image' => $posterPath,
                'status' => 'published',
                'published_at' => '2026-06-02 08:00:00',
            ]
        );

        Announcement::query()
            ->where('slug', 'informasi-ppdb-tahun-ajaran-baru')
            ->update(['status' => 'draft']);

        Page::updateOrCreate(
            ['slug' => 'fasilitas'],
            [
                'title' => 'Fasilitas',
                'excerpt' => 'Sarana utama yang mendukung pembelajaran dan kegiatan siswa MI Hubbul Wathan.',
                'body' => <<<'HTML'
<p>MI Hubbul Wathan menyediakan fasilitas utama untuk mendukung pembelajaran, kesehatan, dan aktivitas peserta didik.</p>
<ul>
    <li>Ruang kelas luas.</li>
    <li>Ruang perpustakaan.</li>
    <li>Ruang UKS.</li>
    <li>Lapangan olahraga.</li>
</ul>
HTML,
                'status' => 'published',
                'published_at' => now(),
            ]
        );

        foreach ($this->extracurriculars() as $item) {
            Extracurricular::updateOrCreate(
                ['slug' => $item['slug']],
                $item + [
                    'coach' => null,
                    'schedule' => null,
                    'image' => null,
                    'status' => 'published',
                ]
            );
        }

        Extracurricular::query()
            ->whereNotIn('slug', collect($this->extracurriculars())->pluck('slug'))
            ->update(['status' => 'draft']);

        SchoolSetting::updateOrCreate(
            ['key' => 'phone'],
            ['value' => '085396590157', 'group' => 'contact']
        );

        foreach ([
            'email' => 'yppm.hubbulwathan@gmail.com',
            'instagram' => 'yppm.hubbulwathan',
            'facebook' => 'yppm.hubbulwathan',
        ] as $key => $value) {
            SchoolSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'contact']
            );
        }

        Page::updateOrCreate(
            ['slug' => 'kontak'],
            [
                'title' => 'Kontak',
                'excerpt' => 'Informasi kontak dan layanan administrasi sekolah.',
                'body' => '<p>Untuk informasi sekolah dan layanan administrasi, silakan hubungi MI Hubbul Wathan melalui kanal resmi berikut.</p><ul><li>WhatsApp: 085396590157</li><li>Email: <a href="mailto:yppm.hubbulwathan@gmail.com">yppm.hubbulwathan@gmail.com</a></li><li>Instagram: <a href="https://www.instagram.com/yppm.hubbulwathan" target="_blank" rel="noopener">@yppm.hubbulwathan</a></li><li>Facebook: <a href="https://www.facebook.com/yppm.hubbulwathan" target="_blank" rel="noopener">@yppm.hubbulwathan</a></li></ul>',
                'status' => 'published',
                'published_at' => now(),
            ]
        );
    }

    private function extracurriculars(): array
    {
        return [
            [
                'name' => 'Tahfidz',
                'slug' => 'tahfidz',
                'description' => 'Pembinaan hafalan Al-Quran secara bertahap sesuai kemampuan siswa.',
            ],
            [
                'name' => 'TBTQ',
                'slug' => 'tbtq',
                'description' => 'Pembinaan kemampuan membaca dan menulis Al-Quran.',
            ],
            [
                'name' => 'Seni Tari',
                'slug' => 'seni-tari',
                'description' => 'Pengembangan minat, percaya diri, dan kreativitas siswa melalui seni tari.',
            ],
            [
                'name' => 'Pramuka',
                'slug' => 'pramuka',
                'description' => 'Kegiatan untuk melatih kedisiplinan, kerja sama, dan kemandirian.',
            ],
        ];
    }
}
