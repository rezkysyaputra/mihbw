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
            Page::updateOrCreate(
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
            'address' => 'Desa Toli-Toli, Kecamatan Lalonggasumeeto, Kabupaten Konawe, Sulawesi Tenggara 93351',
            'maps_query' => 'MI Hubbul Wathan Lalonggasumeeto Kabupaten Konawe Sulawesi Tenggara',
            'maps_embed_url' => 'https://www.google.com/maps?q=MI%20Hubbul%20Wathan%20Lalonggasumeeto%20Kabupaten%20Konawe%20Sulawesi%20Tenggara&output=embed',
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
                'title' => 'Profil Madrasah',
                'slug' => 'profil-sekolah',
                'excerpt' => 'Mengenal MI Hubbul Wathan sebagai madrasah yang berorientasi pada akhlak, ilmu, dan kemandirian.',
                'body' => '<p>MI Hubbul Wathan menyelenggarakan pendidikan dasar Islam dalam lingkungan belajar yang religius, tertib, dan dekat dengan kebutuhan peserta didik serta orang tua di Desa Toli-Toli.</p>',
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
                'title' => 'Visi dan Misi',
                'slug' => 'visi-misi',
                'excerpt' => 'Arah dan komitmen MI Hubbul Wathan dalam membentuk generasi santun dan mandiri.',
                'body' => <<<'HTML'
<h2>Visi Madrasah</h2>
<p>Terwujudnya peserta didik yang berakhlak mulia, cerdas, terampil, dan mandiri berlandaskan nilai-nilai Islam.</p>
<h2>Misi Madrasah</h2>
<ol>
    <li>Menanamkan nilai-nilai keislaman dan pembiasaan adab dalam kehidupan sehari-hari.</li>
    <li>Melaksanakan pembelajaran aktif, kreatif, dan bermakna untuk menguatkan literasi dan numerasi.</li>
    <li>Mengembangkan potensi, minat, dan bakat peserta didik melalui kegiatan akademik dan ekstrakurikuler.</li>
    <li>Membangun lingkungan madrasah yang bersih, aman, disiplin, dan harmonis bersama orang tua serta masyarakat.</li>
</ol>
<h2>Tujuan</h2>
<p>Membekali lulusan dengan dasar keimanan yang kokoh, penguasaan ilmu dasar yang baik, serta kesiapan melanjutkan ke jenjang pendidikan berikutnya.</p>
HTML,
            ],
            [
                'title' => 'Sejarah Singkat',
                'slug' => 'sejarah-singkat',
                'excerpt' => 'Perjalanan MI Hubbul Wathan sebagai lembaga pendidikan dasar Islam di Desa Toli-Toli, Kabupaten Konawe.',
                'body' => <<<'HTML'
<p>MI Hubbul Wathan hadir sebagai lembaga pendidikan dasar Islam di Desa Toli-Toli, Kecamatan Lalonggasumeeto, Kabupaten Konawe untuk mendukung kebutuhan pendidikan anak-anak di lingkungan sekitar.</p>
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
    <li>Membiasakan salat berjamaah dan hafalan surat pendek.</li>
</ul>
<h2>Program Pendukung</h2>
<p>Pelaksanaan pembelajaran didukung oleh kegiatan tahfidz, baca tulis Al-Quran, pramuka, seni, olahraga, literasi, dan program pembiasaan madrasah.</p>
HTML,
            ],
            [
                'title' => 'Kontak',
                'slug' => 'kontak',
                'excerpt' => 'Informasi kontak, media sosial, dan lokasi MI Hubbul Wathan.',
                'body' => '<p>Untuk informasi madrasah dan layanan administrasi, silakan hubungi MI Hubbul Wathan melalui kanal resmi berikut.</p><ul><li>Alamat: Desa Toli-Toli, Kecamatan Lalonggasumeeto, Kabupaten Konawe, Sulawesi Tenggara 93351</li><li>WhatsApp: 085396590157</li><li>Email: <a href="mailto:yppm.hubbulwathan@gmail.com">yppm.hubbulwathan@gmail.com</a></li><li>Instagram: <a href="https://www.instagram.com/yppm.hubbulwathan" target="_blank" rel="noopener">@yppm.hubbulwathan</a></li><li>Facebook: <a href="https://www.facebook.com/yppm.hubbulwathan" target="_blank" rel="noopener">@yppm.hubbulwathan</a></li></ul>',
            ],
        ];
    }
}
