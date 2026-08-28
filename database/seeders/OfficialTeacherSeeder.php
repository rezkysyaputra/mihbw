<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Seeder;

class OfficialTeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = $this->teachers();
        $officialSlugs = collect($teachers)->pluck('slug');

        foreach ($teachers as $index => $teacher) {
            Teacher::updateOrCreate(
                ['slug' => $teacher['slug']],
                $teacher + [
                    'photo' => null,
                    'status' => 'published',
                    'sort_order' => $index + 1,
                ]
            );
        }


    }

    private function teachers(): array
    {
        return [
            [
                'name' => 'Hasnah, S.Pd.I',
                'slug' => 'hasnah',
                'position' => 'Kepala Madrasah',
                'subject' => null,
            ],
            [
                'name' => 'Hj. St. Saidah, S.Pd.I',
                'slug' => 'hj-st-saidah',
                'position' => 'Komite Madrasah',
                'subject' => null,
            ],
            [
                'name' => 'Ilmiah, S.Pd.I',
                'slug' => 'ilmiah',
                'position' => 'Urusan Kurikulum',
                'subject' => 'Wali Kelas I',
            ],
            [
                'name' => 'Albar, S.Pd.I',
                'slug' => 'albar',
                'position' => 'Urusan Kesiswaan',
                'subject' => 'Wali Kelas IV',
            ],
            [
                'name' => 'St. Rahmah, S.Pd.I',
                'slug' => 'st-rahmah',
                'position' => 'Urusan Humas',
                'subject' => 'Wali Kelas V',
            ],
            [
                'name' => 'Mukhadar, S.Pd',
                'slug' => 'mukhadar',
                'position' => 'Tata Usaha',
                'subject' => 'Bendahara / Wali Kelas VI / Pembina Pramuka',
            ],
            [
                'name' => 'Magfirah, S.E',
                'slug' => 'magfirah',
                'position' => 'Operator Madrasah',
                'subject' => 'Wali Kelas II / Ketua UKS',
            ],
            [
                'name' => 'Hartina Haris, S.Pd.I',
                'slug' => 'hartina-haris',
                'position' => 'Kepala Perpustakaan',
                'subject' => 'Wali Kelas III',
            ],
            [
                'name' => 'Rezky Amelia Aty, S.H.I',
                'slug' => 'rezky-amelia-aty',
                'position' => 'Guru Mata Pelajaran',
                'subject' => null,
            ],
            [
                'name' => 'Muhammad Syarif, S.Pd.I',
                'slug' => 'muhammad-syarif',
                'position' => 'Guru Mata Pelajaran',
                'subject' => null,
            ],
        ];
    }
}
