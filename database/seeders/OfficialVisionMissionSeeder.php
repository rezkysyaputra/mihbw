<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OfficialVisionMissionSeeder extends Seeder
{
    public function run(): void
    {
        $source = base_path('album/WhatsApp Image 2026-06-12 at 7.11.15 PM.jpeg');
        $imagePath = 'pages/visi-misi-mi-hubbul-wathan.jpg';

        if (File::exists($source)) {
            Storage::disk('public')->put($imagePath, File::get($source));
        }

        Page::updateOrCreate(
            ['slug' => 'visi-misi'],
            [
                'title' => 'Visi Misi',
                'excerpt' => 'Visi dan misi resmi MI Hubbul Wathan.',
                'body' => <<<'HTML'
<img src="/storage/pages/visi-misi-mi-hubbul-wathan.jpg" alt="Visi dan misi MI Hubbul Wathan">
<h2>Visi</h2>
<p>Terwujudnya insan yang bertakwa, berakhlak mulia, berprestasi, berbudaya, berwawasan lingkungan, inovatif, dan mandiri (BERIMAN).</p>
<h2>Misi</h2>
<ol>
    <li>Mewujudkan karakter religius, disiplin, jujur, bertanggung jawab, santun, peduli, dan terampil melalui pembiasaan ubudiyah dan muamalah.</li>
    <li>Mewujudkan kegiatan pembelajaran yang humanis, aktif, inovatif, kreatif, efektif, menyenangkan, bermakna, dan berprestasi.</li>
    <li>Menyelenggarakan kegiatan pembiasaan guna menciptakan lingkungan madrasah yang bersih, rapi, indah, dan rindang melalui Program Madrasah Bersih (PMB).</li>
    <li>Menyelenggarakan program madrasah sehat untuk mendukung terciptanya siswa yang sehat jasmani dan rohani.</li>
</ol>
HTML,
                'status' => 'published',
                'published_at' => now(),
            ]
        );
    }
}
