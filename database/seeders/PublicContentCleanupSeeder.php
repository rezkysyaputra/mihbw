<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\DownloadDocument;
use Illuminate\Database\Seeder;

class PublicContentCleanupSeeder extends Seeder
{
    public function run(): void
    {
        Announcement::query()
            ->where('slug', 'jadwal-pengambilan-seragam-siswa')
            ->update([
                'body' => 'Orang tua/wali dimohon membawa bukti administrasi saat pengambilan seragam. Informasi jadwal disampaikan melalui wali kelas dan kanal resmi madrasah.',
            ]);

        Announcement::query()
            ->whereIn('slug', ['test-pengumuman', 'informasi-ppdb-tahun-ajaran-baru'])
            ->update(['status' => 'draft']);

        DownloadDocument::query()
            ->whereIn('slug', [
                'brosur-profil-mi-hubbul-wathan',
                'kalender-akademik-dummy',
                'formulir-data-siswa',
            ])
            ->update(['status' => 'draft']);
    }
}
