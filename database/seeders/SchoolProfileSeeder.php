<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;

class SchoolProfileSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->sourcePages() as $page) {
            Page::firstOrCreate(
                ['slug' => $page['slug']],
                $page + [
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );
        }

        foreach ($this->pages() as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page + [
                    'status' => 'published',
                    'published_at' => now(),
                ]
            );
        }

        foreach ([
            'address' => 'Kecamatan Lalonggasumeeto, Kabupaten Konawe, Sulawesi Tenggara',
            'maps_query' => 'MI Hubbul Wathan Lalonggasumeeto Kabupaten Konawe Sulawesi Tenggara',
            'maps_embed_url' => null,
        ] as $key => $value) {
            SchoolSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'contact']
            );
        }
    }

    private function sourcePages(): array
    {
        return [
            [
                'title' => 'Profil Sekolah',
                'slug' => 'profil-sekolah',
                'excerpt' => 'Mengenal MI Hubbul Wathan sebagai madrasah yang berorientasi pada akhlak, ilmu, dan kemandirian.',
                'body' => '<p>MI Hubbul Wathan menyelenggarakan pendidikan dasar Islam dalam lingkungan belajar yang religius, tertib, dan dekat dengan kebutuhan peserta didik serta orang tua.</p>',
            ],
            [
                'title' => 'Fasilitas',
                'slug' => 'fasilitas',
                'excerpt' => 'Sarana utama yang mendukung pembelajaran dan kegiatan siswa.',
                'body' => '<p>Fasilitas madrasah meliputi ruang kelas, ruang perpustakaan, ruang UKS, dan lapangan olahraga untuk mendukung kegiatan peserta didik.</p>',
            ],
        ];
    }

    private function pages(): array
    {
        return [
            [
                'title' => 'Sambutan Kepala Madrasah',
                'slug' => 'sambutan-kepala-madrasah',
                'excerpt' => 'Pesan Kepala MI Hubbul Wathan untuk peserta didik, orang tua, dan masyarakat.',
                'body' => <<<'HTML'
<p>Assalamu'alaikum warahmatullahi wabarakatuh.</p>
<p>Selamat datang di website resmi MI Hubbul Wathan. Website ini menjadi sarana untuk menyampaikan informasi madrasah, kegiatan pembelajaran, pengumuman, serta layanan penerimaan peserta didik baru kepada orang tua dan masyarakat.</p>
<p>Kami berkomitmen mendampingi peserta didik agar tumbuh dalam lingkungan belajar yang religius, tertib, aktif, dan menyenangkan. Dukungan orang tua, yayasan, guru, tenaga kependidikan, dan masyarakat menjadi bagian penting dalam ikhtiar tersebut.</p>
<p>Semoga website ini memudahkan komunikasi dan memperkuat kerja sama kita dalam memberikan pendidikan terbaik bagi anak-anak.</p>
<p>Wassalamu'alaikum warahmatullahi wabarakatuh.</p>
HTML,
            ],
            [
                'title' => 'Sejarah Singkat',
                'slug' => 'sejarah-singkat',
                'excerpt' => 'Perjalanan MI Hubbul Wathan sebagai lembaga pendidikan dasar Islam di Kabupaten Konawe.',
                'body' => <<<'HTML'
<p>MI Hubbul Wathan hadir sebagai lembaga pendidikan dasar Islam di Kabupaten Konawe untuk mendukung kebutuhan pendidikan anak-anak di lingkungan sekitar.</p>
<p>Dalam perkembangannya, madrasah bertumbuh melalui kerja sama yayasan, guru, tenaga kependidikan, orang tua, dan masyarakat. Pembelajaran tidak hanya menekankan kemampuan akademik, tetapi juga pembiasaan ibadah, adab, kedisiplinan, kepedulian, dan kemandirian peserta didik.</p>
<p>Semangat kebersamaan tersebut terus menjadi dasar bagi MI Hubbul Wathan dalam meningkatkan mutu layanan pendidikan dan menciptakan lingkungan belajar yang aman, tertib, dan menyenangkan.</p>
HTML,
            ],
            [
                'title' => 'Struktur Organisasi',
                'slug' => 'struktur-organisasi',
                'excerpt' => 'Susunan pimpinan, pengelola, guru, dan tenaga kependidikan MI Hubbul Wathan.',
                'body' => '<p>Struktur organisasi menampilkan pembagian tugas pimpinan, pengelola, guru, dan tenaga kependidikan MI Hubbul Wathan.</p>',
            ],
            [
                'title' => 'Kurikulum',
                'slug' => 'kurikulum',
                'excerpt' => 'Arah pembelajaran dan program pendidikan MI Hubbul Wathan.',
                'body' => <<<'HTML'
<p>MI Hubbul Wathan menyelenggarakan pembelajaran berdasarkan ketentuan kurikulum nasional dan pendidikan madrasah yang berlaku. Program pembelajaran memadukan mata pelajaran umum, pendidikan agama, pembiasaan karakter, serta kegiatan pengembangan minat peserta didik.</p>
<h2>Fokus Pembelajaran</h2>
<ul>
    <li>Penguatan literasi dan numerasi sebagai kecakapan dasar.</li>
    <li>Pembiasaan ibadah, adab, disiplin, dan tanggung jawab.</li>
    <li>Pembelajaran aktif, kontekstual, dan sesuai tahap perkembangan siswa.</li>
    <li>Pengembangan kreativitas, kerja sama, serta kemandirian.</li>
</ul>
<h2>Program Pendukung</h2>
<p>Pelaksanaan pembelajaran didukung oleh kegiatan tahfidz, baca tulis Al-Quran, pramuka, seni, olahraga, literasi, dan program pembiasaan madrasah.</p>
HTML,
            ],
            [
                'title' => 'Kontak',
                'slug' => 'kontak',
                'excerpt' => 'Informasi kontak, media sosial, dan lokasi MI Hubbul Wathan.',
                'body' => '<p>Untuk informasi sekolah dan layanan administrasi, silakan hubungi MI Hubbul Wathan melalui kanal resmi berikut.</p><ul><li>WhatsApp: 085396590157</li><li>Email: <a href="mailto:yppm.hubbulwathan@gmail.com">yppm.hubbulwathan@gmail.com</a></li><li>Instagram: <a href="https://www.instagram.com/yppm.hubbulwathan" target="_blank" rel="noopener">@yppm.hubbulwathan</a></li><li>Facebook: <a href="https://www.facebook.com/yppm.hubbulwathan" target="_blank" rel="noopener">@yppm.hubbulwathan</a></li></ul>',
            ],
        ];
    }
}
