<?php

namespace App\Http\Controllers;

use App\Models\AcademicEvent;
use App\Models\Announcement;
use App\Models\DownloadDocument;
use App\Models\Extracurricular;
use App\Models\GalleryAlbum;
use App\Models\GalleryItem;
use App\Models\HomepageImage;
use App\Models\Page;
use App\Models\Post;
use App\Models\SchoolSetting;
use App\Models\Teacher;
use App\Support\PublicImage;
use Illuminate\View\View;

class PublicPortalController extends Controller
{
    /**
     * Tahun ajaran berjalan. Bergulir tiap Juli, sesuai kalender madrasah.
     */
    private function academicYear(): string
    {
        $start = now()->month >= 7 ? now()->year : now()->year - 1;

        return $start.'/'.($start + 1);
    }

    public function home(): View
    {
        $principal = Teacher::query()
            ->where('status', 'published')
            ->where('position', 'Kepala Madrasah')
            ->orderBy('sort_order')
            ->first();

        return view('portal.home', [
            'posts' => Post::where('status', 'published')->latest('published_at')->take(4)->get(),
            'announcements' => Announcement::where('status', 'published')->orderByDesc('is_pinned')->latest('published_at')->take(4)->get(),
            'events' => AcademicEvent::where('status', 'published')
                ->where('starts_at', '>=', now()->startOfDay())
                ->orderBy('starts_at')
                ->take(4)
                ->get(),
            'teacherCount' => Teacher::where('status', 'published')->count(),
            'extracurricularCount' => Extracurricular::where('status', 'published')->count(),
            'academicYear' => $this->academicYear(),
            'overviewPages' => Page::query()
                ->where('status', 'published')
                ->whereIn('slug', ['profil-sekolah', 'visi-misi', 'struktur-organisasi'])
                ->get()
                ->sortBy(fn (Page $page): int => array_search($page->slug, ['profil-sekolah', 'visi-misi', 'struktur-organisasi'], true))
                ->values(),
            'galleryItems' => GalleryItem::where('status', 'published')
                ->whereNotNull('image')
                ->orderBy('sort_order')
                ->latest()
                ->take(8)
                ->get(),
            'homepageImagePlacements' => HomepageImage::query()
                ->with(['galleryItem.album'])
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->groupBy('section'),
            'principal' => $principal,
            'principalMessage' => Page::query()
                ->where('slug', 'sambutan-kepala-madrasah')
                ->where('status', 'published')
                ->first(),
            'seoTitle' => 'MI Hubbul Wathan - Portal Resmi Madrasah Ibtidaiyah',
            'seoDescription' => 'Website resmi MI Hubbul Wathan untuk profil madrasah, berita, pengumuman, galeri, kalender akademik, unduhan, dan PPDB online.',
        ]);
    }

    public function about(): View
    {
        $pages = Page::query()
            ->where('status', 'published')
            ->whereIn('slug', ['profil-sekolah', 'sejarah-singkat', 'fasilitas'])
            ->get()
            ->keyBy('slug');
        abort_if($pages->count() < 3, 404);

        return view('portal.about', [
            'profilePage' => $pages->get('profil-sekolah'),
            'historyPage' => $pages->get('sejarah-singkat'),
            'facilitiesPage' => $pages->get('fasilitas'),
            'facilityImages' => GalleryAlbum::query()
                ->where('slug', 'fasilitas-madrasah')
                ->where('status', 'published')
                ->with(['items' => fn ($query) => $query
                    ->where('status', 'published')
                    ->orderBy('sort_order')
                    ->take(6)])
                ->first()
                ?->items ?? collect(),
            'seoTitle' => 'Tentang MI Hubbul Wathan',
            'seoDescription' => $pages->get('profil-sekolah')->excerpt,
        ]);
    }

    public function page(string $slug): View
    {
        $page = Page::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $contactSettings = $slug === 'kontak'
            ? SchoolSetting::query()->where('group', 'contact')->pluck('value', 'key')->all()
            : [];

        return view('portal.page', [
            'page' => $page,
            'pageCategory' => $slug === 'kurikulum' ? 'Akademik' : 'Profil Madrasah',
            'contactSettings' => $contactSettings,
            'seoTitle' => $page->title.' - MI Hubbul Wathan',
            'seoDescription' => $page->excerpt ?: str($page->body)->stripTags()->limit(155, ''),
        ]);
    }

    public function organization(): View
    {
        $page = Page::query()
            ->where('slug', 'struktur-organisasi')
            ->where('status', 'published')
            ->firstOrFail();
        $teachers = Teacher::query()
            ->where('status', 'published')
            ->orderBy('sort_order')
            ->get();

        return view('portal.organization', [
            'page' => $page,
            'principal' => $teachers->firstWhere('position', 'Kepala Madrasah'),
            'committee' => $teachers->where('position', 'Komite Madrasah')->values(),
            'management' => $teachers->whereIn('position', [
                'Tata Usaha',
                'Operator Madrasah',
                'Urusan Kurikulum',
                'Urusan Kesiswaan',
                'Urusan Humas',
            ])->values(),
            'educators' => $teachers->whereNotIn('position', [
                'Kepala Madrasah',
                'Komite Madrasah',
                'Tata Usaha',
                'Operator Madrasah',
                'Urusan Kurikulum',
                'Urusan Kesiswaan',
                'Urusan Humas',
            ])->values(),
            'seoTitle' => 'Struktur Organisasi - MI Hubbul Wathan',
            'seoDescription' => $page->excerpt,
        ]);
    }

    public function posts(): View
    {
        return view('portal.posts', [
            'title' => 'Berita Madrasah',
            'items' => Post::where('status', 'published')->latest('published_at')->paginate(9)->withQueryString(),
            'type' => 'post',
            'seoTitle' => 'Berita Madrasah - MI Hubbul Wathan',
            'seoDescription' => 'Kumpulan berita terbaru, kegiatan, dan informasi resmi dari MI Hubbul Wathan.',
        ]);
    }

    public function announcements(): View
    {
        return view('portal.posts', [
            'title' => 'Pengumuman',
            'items' => Announcement::where('status', 'published')->orderByDesc('is_pinned')->latest('published_at')->paginate(9)->withQueryString(),
            'type' => 'announcement',
            'seoTitle' => 'Pengumuman - MI Hubbul Wathan',
            'seoDescription' => 'Pengumuman resmi MI Hubbul Wathan untuk wali murid, calon siswa, dan masyarakat.',
        ]);
    }

    public function post(string $slug): View
    {
        $item = Post::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('portal.detail', [
            'item' => $item,
            'label' => 'Berita',
            'indexUrl' => route('posts.index'),
            'indexLabel' => 'Semua berita',
            'related' => Post::query()
                ->where('status', 'published')
                ->whereKeyNot($item->getKey())
                ->latest('published_at')
                ->take(3)
                ->get()
                ->map(fn (Post $post): array => [
                    'title' => $post->title,
                    'url' => route('posts.show', $post->slug),
                    'published_at' => $post->published_at,
                ]),
            'seoTitle' => $item->title.' - MI Hubbul Wathan',
            'seoDescription' => $item->excerpt ?: str($item->body)->stripTags()->limit(155, ''),
            'seoImage' => PublicImage::storageUrl($item->cover_image, asset('images/logo-hubbul-wathan.png')),
            'seoType' => 'article',
        ]);
    }

    public function announcement(string $slug): View
    {
        $item = Announcement::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('portal.detail', [
            'item' => $item,
            'label' => 'Pengumuman',
            'indexUrl' => route('announcements.index'),
            'indexLabel' => 'Semua pengumuman',
            'related' => Announcement::query()
                ->where('status', 'published')
                ->whereKeyNot($item->getKey())
                ->latest('published_at')
                ->take(3)
                ->get()
                ->map(fn (Announcement $announcement): array => [
                    'title' => $announcement->title,
                    'url' => route('announcements.show', $announcement->slug),
                    'published_at' => $announcement->published_at,
                ]),
            'seoTitle' => $item->title.' - MI Hubbul Wathan',
            'seoDescription' => $item->excerpt ?: str($item->body)->stripTags()->limit(155, ''),
            'seoImage' => PublicImage::storageUrl($item->cover_image, asset('images/logo-hubbul-wathan.png')),
            'seoType' => 'article',
        ]);
    }

    public function teachers(): View
    {
        $teachers = Teacher::where('status', 'published')->orderBy('sort_order')->get();

        return view('portal.teachers', [
            'principal' => $teachers->firstWhere('position', 'Kepala Madrasah'),
            'teachers' => $teachers->reject(fn (Teacher $teacher): bool => $teacher->position === 'Kepala Madrasah')->values(),
            'academicYear' => $this->academicYear(),
            'seoTitle' => 'Guru dan Tenaga Pendidik - MI Hubbul Wathan',
            'seoDescription' => 'Profil guru dan tenaga pendidik MI Hubbul Wathan.',
        ]);
    }

    public function extracurriculars(): View
    {
        return view('portal.extracurriculars', [
            'items' => Extracurricular::where('status', 'published')->orderBy('name')->get(),
            'seoTitle' => 'Ekstrakurikuler - MI Hubbul Wathan',
            'seoDescription' => 'Informasi kegiatan ekstrakurikuler MI Hubbul Wathan untuk pengembangan minat, karakter, dan kemandirian siswa.',
        ]);
    }

    public function calendar(): View
    {
        $allEvents = AcademicEvent::where('status', 'published')->orderBy('starts_at')->get();
        $today = now()->startOfDay();

        return view('portal.calendar', [
            'upcoming' => $allEvents->filter(fn (AcademicEvent $event): bool => $event->starts_at >= $today)->values(),
            'past' => $allEvents->filter(fn (AcademicEvent $event): bool => $event->starts_at < $today)->sortByDesc('starts_at')->values(),
            'academicYear' => $this->academicYear(),
            'seoTitle' => 'Kalender Akademik - MI Hubbul Wathan',
            'seoDescription' => 'Jadwal kegiatan dan agenda akademik MI Hubbul Wathan.',
        ]);
    }

    public function gallery(): View
    {
        return view('portal.gallery', [
            'albums' => GalleryAlbum::where('status', 'published')
                ->with(['items' => fn ($query) => $query->where('status', 'published')->orderBy('sort_order')])
                ->latest()
                ->get(),
            'seoTitle' => 'Galeri Kegiatan - MI Hubbul Wathan',
            'seoDescription' => 'Dokumentasi kegiatan belajar, keagamaan, dan agenda madrasah MI Hubbul Wathan.',
        ]);
    }

    public function downloads(): View
    {
        return view('portal.downloads', [
            'documents' => DownloadDocument::where('status', 'published')->latest()->get(),
            'seoTitle' => 'Unduhan Dokumen - MI Hubbul Wathan',
            'seoDescription' => 'Dokumen unduhan resmi MI Hubbul Wathan seperti kalender, brosur, formulir, dan surat edaran.',
        ]);
    }
}
