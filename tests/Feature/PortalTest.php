<?php

namespace Tests\Feature;

use App\Filament\Resources\AcademicEvents\AcademicEventResource;
use App\Filament\Resources\DownloadDocuments\DownloadDocumentResource;
use App\Filament\Resources\HomepageImages\HomepageImageResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\PpdbApplicants\PpdbApplicantResource;
use App\Filament\Resources\Teachers\TeacherResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Widgets\PortalAnalyticsOverview;
use App\Models\Announcement;
use App\Models\Extracurricular;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use App\Models\HomepageImage;
use App\Models\Page;
use App\Models\Post;
use App\Models\PpdbApplicant;
use App\Models\Teacher;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_home_page_is_accessible(): void
    {
        $this->get('/')->assertOk()->assertSee('MI Hubbul Wathan');
    }

    public function test_home_page_outputs_basic_seo_meta(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<meta name="description"', false)
            ->assertSee('<link rel="canonical"', false)
            ->assertSee('application/ld+json', false);
    }

    public function test_home_overview_uses_published_page_data_without_admin_copy(): void
    {
        foreach ([
            ['title' => 'Profil Sekolah', 'slug' => 'profil-sekolah', 'excerpt' => 'Profil resmi dari database.'],
            ['title' => 'Visi Misi', 'slug' => 'visi-misi', 'excerpt' => 'Visi resmi dari database.'],
            ['title' => 'Struktur Organisasi', 'slug' => 'struktur-organisasi', 'excerpt' => 'Struktur resmi dari database.'],
        ] as $page) {
            Page::create($page + [
                'body' => 'Isi halaman.',
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        $this->get('/')
            ->assertOk()
            ->assertSee('Profil resmi dari database.')
            ->assertSee('Visi resmi dari database.')
            ->assertSee('Struktur resmi dari database.')
            ->assertDontSee('panel admin', false)
            ->assertDontSee('Masuk Panel Admin');
    }

    public function test_public_portal_has_no_theme_switcher(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee('data-theme-option', false)
            ->assertDontSee('data-theme-menu-button', false)
            ->assertDontSee('portal-theme');
    }

    public function test_public_navigation_marks_current_menu(): void
    {
        Page::create([
            'title' => 'Visi Misi',
            'slug' => 'visi-misi',
            'excerpt' => 'Visi dan misi sekolah.',
            'body' => 'Konten visi misi.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee(route('about'), false)
            ->assertSee(route('pages.show', 'visi-misi'), false)
            ->assertSee(route('organization'), false)
            ->assertSee(route('pages.show', 'kontak'), false);

        $this->get('/berita')
            ->assertOk()
            ->assertSee('href="'.route('posts.index').'" aria-current="page"', false);

        $this->get('/galeri')
            ->assertOk()
            ->assertSee('href="'.route('gallery').'" aria-current="page"', false);

        $this->get('/kalender-akademik')
            ->assertOk()
            ->assertSee('href="'.route('calendar').'" aria-current="page"', false)
            ->assertSee('data-mobile-academic-button', false)
            ->assertSee('data-mobile-academic-menu', false);

        $this->get('/profil/visi-misi')
            ->assertOk()
            ->assertSee('href="'.route('pages.show', 'visi-misi').'" aria-current="page"', false)
            ->assertDontSee('href="'.route('pages.show', 'fasilitas').'" aria-current="page"', false)
            ->assertSee('data-mobile-profile-button', false)
            ->assertSee('data-mobile-profile-menu', false)
            ->assertSee('data-mobile-profile-icon', false)
            ->assertSee('mobile-subnav-link', false);
    }

    public function test_public_portal_does_not_render_announcement_popup(): void
    {
        Announcement::create([
            'title' => 'Libur Semester',
            'slug' => 'libur-semester',
            'excerpt' => 'Informasi jadwal libur semester untuk wali murid.',
            'body' => 'Isi pengumuman libur semester.',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('data-announcement-popup', false)
            ->assertDontSee('images/placeholders/announcement-placeholder.svg', false)
            ->assertDontSee('data-fallback-src', false)
            ->assertSee('Libur Semester')
            ->assertDontSee('Baca Pengumuman')
            ->assertSee(route('announcements.show', 'libur-semester'), false);
    }

    public function test_home_page_has_viewable_activity_images(): void
    {
        $academicYearStart = now()->month >= 7 ? now()->year : now()->year - 1;

        $this->get('/')
            ->assertOk()
            ->assertSee('Kegiatan di madrasah')
            ->assertSee('data-hero-carousel', false)
            ->assertSee('data-hero-slide', false)
            ->assertSee('data-hero-prev', false)
            ->assertSee('data-hero-next', false)
            ->assertSee('data-hero-indicator', false)
            ->assertSee('Guru &amp; Tendik', false)
            ->assertSee('Ekstrakurikuler')
            ->assertSee('Tahun Ajaran')
            ->assertSee($academicYearStart.'/'.($academicYearStart + 1))
            ->assertDontSee('data-counter', false)
            ->assertSee('data-gallery-view', false)
            ->assertSee('data-gallery-modal-date', false)
            ->assertSee('data-gallery-prev', false)
            ->assertSee('data-gallery-next', false)
            ->assertSee('data-gallery-counter', false)
            ->assertSee('data-gallery-group="home-activities"', false)
            ->assertDontSee('background-image: url(', false)
            ->assertSee('cursor-zoom-in', false)
            ->assertSee('group-hover:scale-[1.02]', false)
            ->assertSee('images/placeholders/activity-literacy.svg', false)
            ->assertDontSee('images.pexels.com/photos/', false)
            ->assertDontSee('Sumber gambar online');
    }

    public function test_home_page_shows_one_gallery_strip_not_two_grids(): void
    {
        $content = $this->get('/')->assertOk()->getContent();

        $this->assertSame(0, substr_count($content, 'data-gallery-group="home-highlights"'));
        $this->assertLessThanOrEqual(5, substr_count($content, 'data-gallery-group="home-activities"'));
        $this->assertStringNotContainsString('Empat langkah mudah untuk mendaftar', $content);
        $this->assertStringNotContainsString('Galeri Singkat', $content);
    }

    public function test_home_page_uses_uploaded_gallery_images_when_available(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/upacara.jpg', 'dummy image');

        $album = GalleryAlbum::create([
            'title' => 'Kegiatan Sekolah',
            'slug' => 'kegiatan-sekolah',
            'description' => 'Dokumentasi kegiatan.',
            'status' => 'published',
        ]);

        GalleryItem::create([
            'gallery_album_id' => $album->id,
            'title' => 'Upacara Madrasah',
            'image' => 'gallery/upacara.jpg',
            'caption' => 'Kegiatan upacara madrasah.',
            'status' => 'published',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('/storage/gallery/upacara.jpg', false)
            ->assertSee('Upacara Madrasah');
    }

    public function test_admin_can_choose_images_for_each_homepage_section(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/dipilih.jpg', 'selected image');
        Storage::disk('public')->put('gallery/tidak-dipilih.jpg', 'unselected image');

        $album = GalleryAlbum::create([
            'title' => 'Kegiatan Sekolah',
            'slug' => 'kegiatan-sekolah',
            'description' => 'Dokumentasi kegiatan.',
            'status' => 'published',
        ]);

        $selected = GalleryItem::create([
            'gallery_album_id' => $album->id,
            'title' => 'Foto Pilihan Beranda',
            'image' => 'gallery/dipilih.jpg',
            'caption' => 'Foto yang dipilih admin.',
            'status' => 'published',
        ]);

        GalleryItem::create([
            'gallery_album_id' => $album->id,
            'title' => 'Foto Tidak Dipilih',
            'image' => 'gallery/tidak-dipilih.jpg',
            'caption' => 'Foto yang tidak dipilih admin.',
            'status' => 'published',
        ]);

        foreach (array_keys(HomepageImage::sectionOptions()) as $section) {
            HomepageImage::create([
                'gallery_item_id' => $selected->id,
                'section' => $section,
                'alt_text' => 'Dokumentasi pilihan untuk '.$section,
                'sort_order' => 1,
                'is_active' => true,
            ]);
        }

        $this->get('/')
            ->assertOk()
            ->assertSee('/storage/gallery/dipilih.jpg', false)
            ->assertSee('Dokumentasi pilihan untuk hero')
            ->assertDontSee('/storage/gallery/tidak-dipilih.jpg', false)
            ->assertDontSee('Foto Tidak Dipilih');
    }

    public function test_post_cover_image_is_shown_publicly_when_file_exists(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('posts/sampul-berita.jpg', 'dummy image');

        Post::create([
            'title' => 'Berita Dengan Sampul',
            'slug' => 'berita-dengan-sampul',
            'excerpt' => 'Ringkasan berita bersampul.',
            'body' => 'Isi berita bersampul.',
            'cover_image' => 'posts/sampul-berita.jpg',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('/storage/posts/sampul-berita.jpg', false)
            ->assertSee('Gambar sampul Berita Dengan Sampul');

        $this->get('/berita')
            ->assertOk()
            ->assertSee('/storage/posts/sampul-berita.jpg', false)
            ->assertSee('Gambar sampul Berita Dengan Sampul')
            ->assertSee('MI Hubbul Wathan')
            ->assertSee('clean-list-item', false)
            ->assertSee('clean-list-thumb', false);

        $this->get('/berita/berita-dengan-sampul')
            ->assertOk()
            ->assertSee('/storage/posts/sampul-berita.jpg', false)
            ->assertSee('<meta property="og:image" content="/storage/posts/sampul-berita.jpg">', false);
    }

    public function test_missing_post_cover_image_is_not_rendered_publicly(): void
    {
        Storage::fake('public');

        Post::create([
            'title' => 'Berita Sampul Hilang',
            'slug' => 'berita-sampul-hilang',
            'excerpt' => 'Ringkasan berita.',
            'body' => 'Isi berita.',
            'cover_image' => 'posts/hilang.jpg',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/berita')
            ->assertOk()
            ->assertDontSee('/storage/posts/hilang.jpg', false)
            ->assertSee('images/placeholders/news-placeholder.svg', false);
    }

    public function test_missing_gallery_storage_files_use_local_placeholders(): void
    {
        Storage::fake('public');

        $album = GalleryAlbum::create([
            'title' => 'Kegiatan Keagamaan',
            'slug' => 'kegiatan-keagamaan',
            'description' => 'Dokumentasi kegiatan.',
            'status' => 'published',
        ]);

        GalleryItem::create([
            'gallery_album_id' => $album->id,
            'title' => 'Dummy Kegiatan Keagamaan',
            'image' => 'gallery/dummy-kegiatan-keagamaan-1.jpg',
            'caption' => 'Dokumentasi kegiatan keagamaan.',
            'status' => 'published',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('images/placeholders/activity-literacy.svg', false)
            ->assertDontSee('/storage/gallery/dummy-kegiatan-keagamaan-1.jpg', false);

        $this->get('/galeri')
            ->assertOk()
            ->assertSee('images/placeholders/activity-literacy.svg', false)
            ->assertDontSee('/storage/gallery/dummy-kegiatan-keagamaan-1.jpg', false);
    }

    public function test_teachers_page_shows_profile_photos(): void
    {
        Teacher::create([
            'name' => 'Ustazah Nur Aisyah',
            'slug' => 'ustazah-nur-aisyah',
            'position' => 'Guru Kelas',
            'subject' => 'Tematik',
            'status' => 'published',
        ]);

        $academicYearStart = now()->month >= 7 ? now()->year : now()->year - 1;

        $this->get('/guru')
            ->assertOk()
            ->assertSee('Foto profil Ustazah Nur Aisyah')
            ->assertSee('images/placeholders/teacher-placeholder.svg', false)
            ->assertSee('person-card', false)
            ->assertSee('Guru Kelas')
            ->assertSee($academicYearStart.'/'.($academicYearStart + 1))
            ->assertDontSee('2026/2027')
            ->assertDontSee('images.pexels.com/photos/', false);
    }

    public function test_teachers_page_features_principal_separately(): void
    {
        Teacher::create([
            'name' => 'Bapak Kepala',
            'slug' => 'bapak-kepala',
            'position' => 'Kepala Madrasah',
            'status' => 'published',
            'sort_order' => 1,
        ]);

        Teacher::create([
            'name' => 'Ustazah Guru Kelas',
            'slug' => 'ustazah-guru-kelas',
            'position' => 'Guru Kelas',
            'status' => 'published',
            'sort_order' => 2,
        ]);

        $content = $this->get('/guru')->assertOk()->getContent();

        // Kepala madrasah tampil satu kali di blok sorotan, bukan ikut grid kartu.
        $this->assertSame(1, substr_count($content, 'Bapak Kepala'));
        $this->assertStringContainsString('Ustazah Guru Kelas', $content);
        $this->assertTrue(strpos($content, 'Bapak Kepala') < strpos($content, 'Ustazah Guru Kelas'));
    }

    public function test_extracurricular_page_shows_multiple_images(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('extracurriculars/pramuka-1.jpg', 'image one');
        Storage::disk('public')->put('extracurriculars/pramuka-2.jpg', 'image two');

        Extracurricular::create([
            'name' => 'Pramuka',
            'slug' => 'pramuka',
            'description' => 'Kegiatan pembentukan karakter siswa.',
            'coach' => 'Mukhadar, S.Pd',
            'schedule' => 'Jumat sore',
            'images' => [
                'extracurriculars/pramuka-1.jpg',
                'extracurriculars/pramuka-2.jpg',
            ],
            'status' => 'published',
        ]);

        $this->get('/ekstrakurikuler')
            ->assertOk()
            ->assertSee('Pramuka')
            ->assertSee('Mukhadar, S.Pd')
            ->assertSee('/storage/extracurriculars/pramuka-1.jpg', false)
            ->assertSee('/storage/extracurriculars/pramuka-2.jpg', false)
            ->assertSee('data-gallery-group="extracurricular-', false)
            ->assertSee('data-gallery-view', false);
    }

    public function test_sitemap_and_robots_are_accessible(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee('<urlset', false)
            ->assertSee(route('home'), false);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: '.url('/sitemap.xml'));
    }

    public function test_gallery_page_has_viewable_cards(): void
    {
        $this->get('/galeri')
            ->assertOk()
            ->assertSee('data-gallery-view', false)
            ->assertSee('data-gallery-date', false)
            ->assertSee('data-gallery-prev', false)
            ->assertSee('data-gallery-next', false)
            ->assertSee('data-gallery-counter', false)
            ->assertSee('data-gallery-group="album-0"', false)
            ->assertSee('Perbesar gambar')
            ->assertSee('gallery-media', false)
            ->assertSee('6 foto')
            ->assertSee('images/placeholders/activity-literacy.svg', false)
            ->assertDontSee('images.pexels.com/photos/', false)
            ->assertDontSee('Foto untuk album ini belum ditambahkan.')
            ->assertDontSee('Gambar Online')
            ->assertDontSee('online sementara')
            ->assertDontSee('Placeholder sementara');
    }

    public function test_gallery_page_separates_photos_by_album(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('gallery/kelas.jpg', 'dummy image');
        Storage::disk('public')->put('gallery/ibadah.jpg', 'dummy image');

        $classAlbum = GalleryAlbum::create([
            'title' => 'Kegiatan Belajar',
            'slug' => 'kegiatan-belajar',
            'description' => 'Dokumentasi kelas.',
            'status' => 'published',
        ]);

        $religiousAlbum = GalleryAlbum::create([
            'title' => 'Kegiatan Keagamaan',
            'slug' => 'kegiatan-keagamaan',
            'description' => 'Dokumentasi ibadah.',
            'status' => 'published',
        ]);

        GalleryItem::create([
            'gallery_album_id' => $classAlbum->id,
            'title' => 'Belajar Kelas',
            'image' => 'gallery/kelas.jpg',
            'caption' => 'Suasana belajar kelas.',
            'status' => 'published',
        ]);

        GalleryItem::create([
            'gallery_album_id' => $religiousAlbum->id,
            'title' => 'Doa Pagi',
            'image' => 'gallery/ibadah.jpg',
            'caption' => 'Pembiasaan doa pagi.',
            'status' => 'published',
        ]);

        $response = $this->get('/galeri')->assertOk();

        $response->assertSee('Kegiatan Belajar')
            ->assertSee('Kegiatan Keagamaan')
            ->assertSee('/storage/gallery/kelas.jpg', false)
            ->assertSee('/storage/gallery/ibadah.jpg', false);

        $this->assertTrue(strpos($response->getContent(), 'Kegiatan Belajar') < strpos($response->getContent(), 'Belajar Kelas'));
        $this->assertTrue(strpos($response->getContent(), 'Kegiatan Keagamaan') < strpos($response->getContent(), 'Doa Pagi'));
    }

    public function test_public_content_uses_contextual_date_formats(): void
    {
        Post::create([
            'title' => 'Berita Relatif',
            'slug' => 'berita-relatif',
            'excerpt' => 'Ringkasan berita relatif.',
            'body' => 'Isi berita relatif.',
            'status' => 'published',
            'published_at' => now()->subDays(2),
        ]);

        Announcement::create([
            'title' => 'Pengumuman Relatif',
            'slug' => 'pengumuman-relatif',
            'excerpt' => 'Ringkasan pengumuman relatif.',
            'body' => 'Isi pengumuman relatif.',
            'status' => 'published',
            'published_at' => now()->subDays(3),
        ]);

        $album = GalleryAlbum::create([
            'title' => 'Album Relatif',
            'slug' => 'album-relatif',
            'description' => 'Dokumentasi tanggal relatif.',
            'status' => 'published',
        ]);

        $galleryItem = GalleryItem::create([
            'gallery_album_id' => $album->id,
            'title' => 'Foto Relatif',
            'image' => 'gallery/relatif.jpg',
            'caption' => 'Dokumentasi relatif.',
            'status' => 'published',
        ]);

        $galleryItem->forceFill(['created_at' => now()->subDays(4)])->save();
        $galleryDate = now()->subDays(4)->translatedFormat('d M Y');

        $this->get('/berita')
            ->assertOk()
            ->assertSee('2 hari yang lalu');

        $this->get('/pengumuman')
            ->assertOk()
            ->assertSee('3 hari yang lalu');

        $this->get('/galeri')
            ->assertOk()
            ->assertSee($galleryDate)
            ->assertSee('data-gallery-date="'.$galleryDate.'"', false)
            ->assertDontSee('4 hari yang lalu');
    }

    public function test_post_detail_renders_rich_editor_images(): void
    {
        Post::create([
            'title' => 'Berita Dengan Gambar',
            'slug' => 'berita-dengan-gambar',
            'excerpt' => 'Ringkasan berita',
            'body' => '<p><img src="http://localhost/storage/contoh.webp" alt="lorem ipsum" onerror="alert(1)"><strong>Lorem Ipsum</strong></p><script>alert(1)</script>',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/berita/berita-dengan-gambar')
            ->assertOk()
            ->assertSee('<img src="', false)
            ->assertSee('/storage/contoh.webp', false)
            ->assertSee('<strong>Lorem Ipsum</strong>', false)
            ->assertDontSee('&lt;img', false)
            ->assertDontSee('onerror', false)
            ->assertDontSee('alert(1)', false);
    }

    public function test_plain_static_page_content_preserves_line_breaks(): void
    {
        Page::create([
            'title' => 'Visi Misi',
            'slug' => 'visi-misi',
            'excerpt' => 'Visi dan misi sekolah.',
            'body' => "Visi:\nTerwujudnya peserta didik yang berakhlak mulia.\n\nMisi:\n1. Menyelenggarakan pembelajaran aktif.\n2. Membiasakan adab harian.",
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/profil/visi-misi')
            ->assertOk()
            ->assertSee('<p>Visi:<br>', false)
            ->assertSee('</p><p>Misi:<br>', false)
            ->assertSee('1. Menyelenggarakan pembelajaran aktif.<br>', false)
            ->assertDontSee('<ol>', false);
    }

    public function test_html_static_page_content_preserves_text_node_line_breaks(): void
    {
        Page::create([
            'title' => 'Visi Misi HTML',
            'slug' => 'visi-misi-html',
            'excerpt' => 'Visi dan misi sekolah.',
            'body' => "<p>Visi:\nTerwujudnya peserta didik yang berakhlak mulia.</p><p>Misi:\n1. Menyelenggarakan pembelajaran aktif.\n2. Membiasakan adab harian.</p>",
            'status' => 'published',
            'published_at' => now(),
        ]);

        $this->get('/profil/visi-misi-html')
            ->assertOk()
            ->assertSee('<p>Visi:<br>Terwujudnya peserta didik yang berakhlak mulia.</p>', false)
            ->assertSee('<p>Misi:<br>1. Menyelenggarakan pembelajaran aktif.<br>2. Membiasakan adab harian.</p>', false);
    }

    public function test_recommended_models_record_activity_without_sensitive_ppdb_fields(): void
    {
        $post = Post::create([
            'title' => 'Audit Berita',
            'slug' => 'audit-berita',
            'excerpt' => 'Ringkasan audit.',
            'body' => 'Isi audit.',
            'status' => 'draft',
            'published_at' => now(),
        ]);

        $post->update(['status' => 'published']);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'Berita',
            'event' => 'created',
            'description' => 'Berita dibuat',
            'subject_type' => Post::class,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'Berita',
            'event' => 'updated',
            'description' => 'Berita diperbarui',
            'subject_type' => Post::class,
        ]);

        PpdbApplicant::create([
            'registration_number' => 'PPDB-'.now()->year.'-0999',
            'academic_year' => now()->year.'/'.(now()->year + 1),
            'student_name' => 'Ahmad Audit',
            'nik' => '7400000000000999',
            'nisn' => '9999999999',
            'birth_place' => 'Bombana',
            'birth_date' => now()->subYears(7)->format('Y-m-d'),
            'gender' => 'Laki-laki',
            'address' => 'Alamat sensitif',
            'previous_school' => 'RA Audit',
            'father_name' => 'Ayah Audit',
            'mother_name' => 'Ibu Audit',
            'parent_phone' => '081299999999',
        ]);

        $activity = Activity::query()
            ->where('subject_type', PpdbApplicant::class)
            ->where('event', 'created')
            ->firstOrFail();

        $properties = json_encode($activity->properties->toArray());

        $this->assertStringContainsString('PPDB-'.now()->year.'-0999', $properties);
        $this->assertStringContainsString('Ahmad Audit', $properties);
        $this->assertStringNotContainsString('7400000000000999', $properties);
        $this->assertStringNotContainsString('Alamat sensitif', $properties);
    }

    public function test_ppdb_form_rejects_empty_submission(): void
    {
        $this->post('/ppdb', [])->assertSessionHasErrors(['student_name', 'nik']);
    }

    public function test_ppdb_form_accepts_valid_submission_and_uploads_documents(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $response = $this->post('/ppdb', $this->validPpdbPayload());

        $applicant = PpdbApplicant::first();

        $response->assertRedirect(route('ppdb.success', $applicant->registration_number));
        $this->assertSame('PPDB-'.now()->year.'-0001', $applicant->registration_number);
        $this->assertCount(3, $applicant->documents);
        $applicant->documents->each(function ($document): void {
            Storage::disk('local')->assertExists($document->file_path);
            Storage::disk('public')->assertMissing($document->file_path);
        });
        $this->get(route('ppdb.success', $applicant->registration_number))
            ->assertOk()
            ->assertSee($applicant->registration_number)
            ->assertDontSee($applicant->student_name);
    }

    public function test_ppdb_accepts_optional_kindergarten_and_assistance_documents(): void
    {
        Storage::fake('local');

        $payload = $this->validPpdbPayload();
        $payload['kindergarten_certificate'] = UploadedFile::fake()->create('ijazah-tk.pdf', 128, 'application/pdf');
        $payload['assistance_card'] = UploadedFile::fake()->create('kip.pdf', 128, 'application/pdf');

        $this->post('/ppdb', $payload)->assertRedirect();

        $documents = PpdbApplicant::firstOrFail()->documents;

        $this->assertCount(5, $documents);
        $this->assertTrue($documents->contains('type', 'Ijazah TK'));
        $this->assertTrue($documents->contains('type', 'KIP/KPS/PKH'));
    }

    public function test_ppdb_rejects_invalid_file_type(): void
    {
        Storage::fake('local');

        $payload = $this->validPpdbPayload();
        $payload['photo'] = UploadedFile::fake()->create('photo.txt', 10, 'text/plain');

        $this->post('/ppdb', $payload)->assertSessionHasErrors(['photo']);
    }

    public function test_user_without_role_cannot_access_filament_panel(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $user = User::factory()->create();

        $this->assertFalse($user->canAccessPanel(Filament::getCurrentPanel()));
    }

    public function test_admin_role_can_access_filament_panel(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Role::create(['name' => 'Admin']);
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $this->assertTrue($user->canAccessPanel(Filament::getCurrentPanel()));

        $this->actingAs($user);

        $this->assertTrue(HomepageImageResource::canViewAny());
        $this->get('/admin/homepage-images')
            ->assertOk()
            ->assertSee('Tampilan Beranda');
        $this->get('/admin/homepage-images/create')
            ->assertOk()
            ->assertSee('Area tampilan')
            ->assertSee('Foto dari galeri');
    }

    public function test_guru_role_has_limited_admin_access(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Role::create(['name' => 'Guru']);

        $user = User::factory()->create();
        $user->assignRole('Guru');

        $this->actingAs($user);

        $this->assertTrue($user->canAccessPanel(Filament::getCurrentPanel()));
        $this->assertTrue(TeacherResource::canViewAny());
        $this->assertTrue(AcademicEventResource::canViewAny());
        $this->assertTrue(DownloadDocumentResource::canViewAny());
        $this->assertFalse(UserResource::canViewAny());
        $this->assertFalse(PpdbApplicantResource::canViewAny());
        $this->assertFalse(PostResource::canViewAny());
        $this->assertFalse(HomepageImageResource::canViewAny());
        $this->assertFalse(PortalAnalyticsOverview::canView());
    }

    public function test_admin_dashboard_shows_analytics_widgets(): void
    {
        Role::create(['name' => 'Admin']);

        $user = User::factory()->create();
        $user->assignRole('Admin');

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Ringkasan Portal')
            ->assertSee('Tren Pendaftar PPDB')
            ->assertSee('Pendaftar PPDB Terbaru');
    }

    private function validPpdbPayload(): array
    {
        return [
            'student_name' => 'Ahmad Fathan',
            'nik' => '7400000000000001',
            'nisn' => '1234567890',
            'birth_place' => 'Bombana',
            'birth_date' => now()->subYears(7)->format('Y-m-d'),
            'gender' => 'Laki-laki',
            'address' => 'Alamat calon siswa',
            'previous_school' => 'RA/TK Contoh',
            'father_name' => 'Bapak Ahmad',
            'mother_name' => 'Ibu Aminah',
            'guardian_name' => null,
            'parent_job' => 'Wiraswasta',
            'parent_phone' => '081234567890',
            'birth_certificate' => UploadedFile::fake()->create('akta.pdf', 128, 'application/pdf'),
            'family_card' => UploadedFile::fake()->create('kk.pdf', 128, 'application/pdf'),
            'photo' => UploadedFile::fake()->create('foto.jpg', 128, 'image/jpeg'),
        ];
    }
}
