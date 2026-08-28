<?php

namespace Database\Seeders;

use App\Models\AcademicEvent;
use App\Models\Announcement;
use App\Models\DownloadDocument;
use App\Models\Extracurricular;
use App\Models\Page;
use App\Models\Post;
use App\Models\PpdbApplicant;
use App\Models\SchoolSetting;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $teacherRole = Role::firstOrCreate(['name' => 'Guru']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@mihubbulwathan.test'],
            ['name' => 'Administrator', 'password' => Hash::make('password')]
        );
        $admin->syncRoles([$adminRole]);

        $operator = User::firstOrCreate(
            ['email' => 'operator@mihubbulwathan.test'],
            ['name' => 'Operator Sekolah', 'password' => Hash::make('password')]
        );
        $operator->syncRoles([$adminRole]);

        $guruUser = User::firstOrCreate(
            ['email' => 'guru@mihubbulwathan.test'],
            ['name' => 'Guru MI Hubbul Wathan', 'password' => Hash::make('password')]
        );
        $guruUser->syncRoles([$teacherRole]);

        // Alamat, telepon, email, dan media sosial resmi di-seed oleh
        // SchoolProfileSeeder + OfficialPpdbInformationSeeder agar satu sumber saja.
        foreach ([
            ['school_name', 'MI Hubbul Wathan', 'identity'],
            ['tagline', 'Berakhlak, Cerdas, dan Mandiri', 'identity'],
            ['office_hours', 'Senin - Sabtu, 07.30 - 13.00 WITA', 'contact'],
        ] as [$key, $value, $group]) {
            SchoolSetting::updateOrCreate(['key' => $key], compact('value', 'group'));
        }

        foreach ($this->pages() as $page) {
            Page::updateOrCreate(
                ['slug' => $page['slug']],
                $page + ['status' => 'published', 'published_at' => now()]
            );
        }

        foreach ($this->posts() as $index => $post) {
            Post::updateOrCreate(
                ['slug' => $post['slug']],
                $post + [
                    'cover_image' => null,
                    'status' => 'published',
                    'published_at' => now()->subDays($index + 1),
                ]
            );
        }

        foreach ($this->announcements() as $index => $announcement) {
            Announcement::updateOrCreate(
                ['slug' => $announcement['slug']],
                $announcement + [
                    'status' => 'published',
                    'published_at' => now()->subDays($index),
                ]
            );
        }

        foreach ($this->teachers() as $index => $teacher) {
            Teacher::updateOrCreate(
                ['slug' => $teacher['slug']],
                $teacher + ['status' => 'published', 'sort_order' => $index + 1]
            );
        }

        foreach ($this->extracurriculars() as $item) {
            Extracurricular::updateOrCreate(
                ['slug' => $item['slug']],
                $item + ['image' => null, 'status' => 'published']
            );
        }

        foreach ($this->academicEvents() as $item) {
            AcademicEvent::updateOrCreate(
                ['title' => $item['title'], 'starts_at' => $item['starts_at']],
                $item + ['status' => 'published']
            );
        }

        foreach ($this->downloadDocuments() as $document) {
            DownloadDocument::updateOrCreate(
                ['slug' => $document['slug']],
                $document + ['mime_type' => 'application/pdf', 'file_size' => 128000, 'status' => 'draft']
            );
        }

        $this->call(SchoolGallerySeeder::class);
        $this->call(HomepageImageSeeder::class);
        $this->call(OfficialPpdbInformationSeeder::class);
        $this->call(OfficialVisionMissionSeeder::class);
        $this->call(OfficialTeacherSeeder::class);
        $this->call(SchoolProfileSeeder::class);
        $this->call(PublicContentCleanupSeeder::class);

        foreach ($this->ppdbApplicants() as $index => $data) {
            $applicant = PpdbApplicant::updateOrCreate(
                ['registration_number' => sprintf('PPDB-%s-%04d', now()->year, $index + 1)],
                $data + [
                    'academic_year' => now()->year.'/'.(now()->year + 1),
                ]
            );

            foreach (['Akta Kelahiran', 'Kartu Keluarga', 'Pas Foto'] as $type) {
                $applicant->documents()->updateOrCreate(
                    ['type' => $type],
                    [
                        'file_path' => 'ppdb/'.$applicant->registration_number.'/'.str($type)->slug().'.pdf',
                        'original_name' => str($type)->slug().'-'.$applicant->registration_number.'.pdf',
                        'mime_type' => 'application/pdf',
                        'file_size' => 64000,
                    ]
                );
            }
        }
    }

    private function pages(): array
    {
        return [
            [
                'title' => 'Profil Sekolah',
                'slug' => 'profil-sekolah',
                'excerpt' => 'Mengenal MI Hubbul Wathan sebagai madrasah ibtidaiyah yang berorientasi pada akhlak, ilmu, dan kemandirian.',
                'body' => "MI Hubbul Wathan adalah madrasah ibtidaiyah yang menyelenggarakan pendidikan dasar dengan suasana belajar tertib, religius, dan dekat dengan kebutuhan orang tua.\n\nMadrasah ini berkomitmen membentuk peserta didik yang memiliki dasar keimanan, kemampuan literasi, numerasi, dan karakter sosial yang baik. Kegiatan belajar dirancang agar siswa terbiasa disiplin, santun, percaya diri, dan mampu bekerja sama.",
            ],
            [
                'title' => 'Visi Misi',
                'slug' => 'visi-misi',
                'excerpt' => 'Arah pengembangan pendidikan MI Hubbul Wathan.',
                'body' => "Visi:\nTerwujudnya peserta didik yang berakhlak mulia, cerdas, mandiri, dan cinta lingkungan.\n\nMisi:\n1. Menyelenggarakan pembelajaran yang aktif, tertib, dan menyenangkan.\n2. Membiasakan ibadah, adab, dan perilaku santun dalam keseharian.\n3. Menguatkan literasi, numerasi, dan kecakapan dasar peserta didik.\n4. Membangun kerja sama yang baik antara madrasah, orang tua, dan masyarakat.",
            ],
            [
                'title' => 'Fasilitas',
                'slug' => 'fasilitas',
                'excerpt' => 'Sarana pendukung pembelajaran dan kegiatan siswa.',
                'body' => 'Fasilitas madrasah meliputi ruang kelas, ruang perpustakaan, ruang UKS, dan lapangan olahraga untuk mendukung kegiatan peserta didik.',
            ],
            [
                'title' => 'Kontak',
                'slug' => 'kontak',
                'excerpt' => 'Informasi kontak dan layanan administrasi sekolah.',
                'body' => '<p>Untuk informasi sekolah dan layanan administrasi, silakan hubungi MI Hubbul Wathan melalui kanal resmi berikut.</p><ul><li>WhatsApp: 085396590157</li><li>Email: <a href="mailto:yppm.hubbulwathan@gmail.com">yppm.hubbulwathan@gmail.com</a></li><li>Instagram: <a href="https://www.instagram.com/yppm.hubbulwathan" target="_blank" rel="noopener">@yppm.hubbulwathan</a></li><li>Facebook: <a href="https://www.facebook.com/yppm.hubbulwathan" target="_blank" rel="noopener">@yppm.hubbulwathan</a></li></ul>',
            ],
        ];
    }

    private function posts(): array
    {
        return [
            [
                'title' => 'Kegiatan Belajar Madrasah Berjalan Tertib',
                'slug' => 'kegiatan-belajar-madrasah',
                'excerpt' => 'Kegiatan belajar berlangsung dengan suasana tertib dan pendampingan guru kelas.',
                'body' => 'MI Hubbul Wathan terus mendorong kegiatan belajar yang terencana, ramah anak, dan sejalan dengan nilai-nilai madrasah. Guru kelas mendampingi siswa melalui kegiatan membaca, berhitung, diskusi kelompok, dan pembiasaan adab.',
            ],
            [
                'title' => 'Pembiasaan Pagi Membentuk Karakter Siswa',
                'slug' => 'pembiasaan-pagi-membentuk-karakter-siswa',
                'excerpt' => 'Siswa mengikuti doa pagi, murajaah, dan arahan wali kelas sebelum pembelajaran.',
                'body' => 'Pembiasaan pagi menjadi bagian penting dari kehidupan madrasah. Kegiatan ini membantu siswa memulai hari dengan tertib, tenang, dan siap mengikuti pelajaran.',
            ],
            [
                'title' => 'Guru Mengikuti Rapat Persiapan Kalender Akademik',
                'slug' => 'rapat-persiapan-kalender-akademik',
                'excerpt' => 'Dewan guru menyusun agenda kegiatan pembelajaran dan program sekolah.',
                'body' => 'Rapat persiapan kalender akademik dilakukan untuk memastikan kegiatan sekolah berjalan terencana. Agenda meliputi penilaian, kegiatan keagamaan, ekstrakurikuler, dan komunikasi dengan orang tua.',
            ],
            [
                'title' => 'Kegiatan Literasi Kelas Rendah',
                'slug' => 'kegiatan-literasi-kelas-rendah',
                'excerpt' => 'Siswa kelas rendah dilatih membaca nyaring dan mengenal kosakata baru.',
                'body' => 'Program literasi kelas rendah dilakukan secara bertahap melalui bacaan pendek, permainan kata, dan pendampingan guru. Kegiatan ini bertujuan memperkuat dasar membaca siswa.',
            ],
            [
                'title' => 'Kerja Bakti Menjaga Kebersihan Lingkungan Madrasah',
                'slug' => 'kerja-bakti-lingkungan-madrasah',
                'excerpt' => 'Siswa dan guru bersama-sama menjaga lingkungan sekolah tetap bersih.',
                'body' => 'Kegiatan kerja bakti menjadi sarana pembiasaan tanggung jawab dan kepedulian terhadap lingkungan. Siswa diajak merapikan kelas, halaman, dan area kegiatan bersama.',
            ],
        ];
    }

    private function announcements(): array
    {
        return [
            [
                'title' => 'Informasi PPDB Tahun Ajaran Baru',
                'slug' => 'informasi-ppdb-tahun-ajaran-baru',
                'excerpt' => 'Formulir PPDB online telah tersedia melalui website resmi sekolah.',
                'body' => 'Calon wali murid dapat mengisi formulir PPDB online dan mengunggah dokumen dasar sesuai ketentuan. Setelah submit, simpan nomor pendaftaran yang muncul di halaman sukses.',
            ],
            [
                'title' => 'Jadwal Pengambilan Seragam Siswa',
                'slug' => 'jadwal-pengambilan-seragam-siswa',
                'excerpt' => 'Pengambilan seragam dilakukan bertahap sesuai jadwal kelas.',
                'body' => 'Orang tua/wali dimohon membawa bukti administrasi saat pengambilan seragam. Informasi jadwal disampaikan melalui wali kelas dan kanal resmi madrasah.',
            ],
            [
                'title' => 'Libur Kegiatan Belajar Hari Besar Nasional',
                'slug' => 'libur-kegiatan-belajar-hari-besar-nasional',
                'excerpt' => 'Kegiatan belajar diliburkan sementara sesuai kalender pendidikan.',
                'body' => 'Seluruh siswa kembali mengikuti pembelajaran pada hari berikutnya sesuai jadwal reguler.',
            ],
        ];
    }

    private function teachers(): array
    {
        return [
            ['name' => 'Hj. Nur Aisyah, S.Pd.I', 'slug' => 'hj-nur-aisyah', 'position' => 'Kepala Madrasah', 'subject' => 'Manajemen Pendidikan', 'photo' => null],
            ['name' => 'Ahmad Fauzan, S.Pd', 'slug' => 'ahmad-fauzan', 'position' => 'Guru Kelas I', 'subject' => 'Tematik', 'photo' => null],
            ['name' => 'Siti Rahmah, S.Pd.I', 'slug' => 'siti-rahmah', 'position' => 'Guru Kelas II', 'subject' => 'Tematik', 'photo' => null],
            ['name' => 'Muhammad Irfan, S.Pd', 'slug' => 'muhammad-irfan', 'position' => 'Guru Kelas IV', 'subject' => 'Matematika dan IPA', 'photo' => null],
            ['name' => 'Fatimah Zahra, S.Pd.I', 'slug' => 'fatimah-zahra', 'position' => 'Guru PAI', 'subject' => 'Pendidikan Agama', 'photo' => null],
            ['name' => 'Hasan Basri, S.Pd', 'slug' => 'hasan-basri', 'position' => 'Pembina Ekstrakurikuler', 'subject' => 'Pramuka', 'photo' => null],
        ];
    }

    private function extracurriculars(): array
    {
        return [
            ['name' => 'Tahfidz dan Baca Tulis Al-Quran', 'slug' => 'tahfidz-btq', 'description' => 'Pembinaan kemampuan membaca, menghafal, dan memahami dasar Al-Quran.', 'coach' => 'Fatimah Zahra', 'schedule' => 'Selasa dan Kamis'],
            ['name' => 'Pramuka Madrasah', 'slug' => 'pramuka-madrasah', 'description' => 'Kegiatan kedisiplinan, kerja sama, dan kemandirian peserta didik.', 'coach' => 'Hasan Basri', 'schedule' => 'Jumat'],
            ['name' => 'Kaligrafi', 'slug' => 'kaligrafi', 'description' => 'Pengembangan minat seni tulis indah Islami.', 'coach' => 'Siti Rahmah', 'schedule' => 'Rabu'],
            ['name' => 'Olahraga Dasar', 'slug' => 'olahraga-dasar', 'description' => 'Aktivitas fisik dasar untuk menjaga kebugaran siswa.', 'coach' => 'Ahmad Fauzan', 'schedule' => 'Sabtu'],
        ];
    }

    private function academicEvents(): array
    {
        return [
            ['title' => 'Pembukaan Tahun Ajaran', 'description' => 'Kegiatan awal tahun ajaran dan pengarahan peserta didik.', 'location' => 'Halaman Madrasah', 'starts_at' => now()->addDays(12), 'ends_at' => now()->addDays(12)->addHours(2)],
            ['title' => 'Rapat Orang Tua/Wali', 'description' => 'Pertemuan awal semester bersama orang tua/wali siswa.', 'location' => 'Ruang Kelas', 'starts_at' => now()->addDays(18), 'ends_at' => now()->addDays(18)->addHours(2)],
            ['title' => 'Penilaian Tengah Semester', 'description' => 'Agenda penilaian pembelajaran tengah semester.', 'location' => 'Ruang Kelas', 'starts_at' => now()->addDays(45), 'ends_at' => now()->addDays(50)],
            ['title' => 'Peringatan Hari Santri', 'description' => 'Kegiatan keagamaan dan pentas siswa.', 'location' => 'Aula Madrasah', 'starts_at' => now()->addDays(70), 'ends_at' => now()->addDays(70)->addHours(3)],
        ];
    }

    private function downloadDocuments(): array
    {
        return [
            ['title' => 'Brosur Profil MI Hubbul Wathan', 'slug' => 'brosur-profil-mi-hubbul-wathan', 'description' => 'Brosur singkat berisi profil dan layanan sekolah.', 'file_path' => 'documents/brosur-profil.pdf', 'original_name' => 'brosur-profil.pdf'],
            ['title' => 'Kalender Akademik', 'slug' => 'kalender-akademik-dummy', 'description' => 'Dokumen kalender akademik madrasah.', 'file_path' => 'documents/kalender-akademik.pdf', 'original_name' => 'kalender-akademik.pdf'],
            ['title' => 'Formulir Data Siswa', 'slug' => 'formulir-data-siswa', 'description' => 'Contoh formulir data siswa yang dapat diunduh wali murid.', 'file_path' => 'documents/formulir-data-siswa.pdf', 'original_name' => 'formulir-data-siswa.pdf'],
        ];
    }

    private function ppdbApplicants(): array
    {
        return [
            ['student_name' => 'Ahmad Fathan', 'nik' => '7400000000000001', 'nisn' => '1234567890', 'birth_place' => 'Bombana', 'birth_date' => now()->subYears(7), 'gender' => 'Laki-laki', 'address' => 'Jl. Melati No. 12', 'previous_school' => 'RA Al-Ikhlas', 'father_name' => 'Bapak Ahmad', 'mother_name' => 'Ibu Aminah', 'guardian_name' => null, 'parent_job' => 'Wiraswasta', 'parent_phone' => '081234567890'],
            ['student_name' => 'Salsabila Nur', 'nik' => '7400000000000002', 'nisn' => null, 'birth_place' => 'Bombana', 'birth_date' => now()->subYears(6)->subMonths(8), 'gender' => 'Perempuan', 'address' => 'Jl. Mawar No. 8', 'previous_school' => 'TK Harapan Bangsa', 'father_name' => 'Bapak Ridwan', 'mother_name' => 'Ibu Sari', 'guardian_name' => null, 'parent_job' => 'Petani', 'parent_phone' => '082211223344'],
            ['student_name' => 'Muhammad Rafi', 'nik' => '7400000000000003', 'nisn' => '9988776655', 'birth_place' => 'Bombana', 'birth_date' => now()->subYears(7)->subMonths(2), 'gender' => 'Laki-laki', 'address' => 'Jl. Kenanga No. 5', 'previous_school' => 'RA Nurul Ilmi', 'father_name' => 'Bapak Hendra', 'mother_name' => 'Ibu Nurlina', 'guardian_name' => null, 'parent_job' => 'Nelayan', 'parent_phone' => '085245678901'],
        ];
    }
}
