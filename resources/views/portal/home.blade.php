@extends('layouts.portal')

@section('content')
    @php
        $fallbackImages = [
            [
                'src' => asset('images/placeholders/activity-literacy.svg'),
                'alt' => 'Kegiatan literasi madrasah',
                'label' => 'Literasi',
            ],
            [
                'src' => asset('images/placeholders/activity-class.svg'),
                'alt' => 'Kegiatan belajar di kelas',
                'label' => 'Kelas Aktif',
            ],
            [
                'src' => asset('images/placeholders/activity-religious.svg'),
                'alt' => 'Pembiasaan keagamaan siswa',
                'label' => 'Religius',
            ],
            [
                'src' => asset('images/placeholders/activity-creative.svg'),
                'alt' => 'Aktivitas kreativitas siswa',
                'label' => 'Kreatif',
            ],
        ];

        $galleryImageUrl = fn ($item, int $index): string => \App\Support\PublicImage::storageUrl(
            $item?->image,
            $fallbackImages[$index % count($fallbackImages)]['src']
        );

        $defaultImages = $galleryItems->map(fn ($item, $index) => [
            'src' => $galleryImageUrl($item, $index),
            'alt' => $item->caption ?: $item->title ?: 'Dokumentasi kegiatan MI Hubbul Wathan',
            'label' => $item->title ?: 'Dokumentasi Kegiatan',
            'date' => optional($item->created_at)->translatedFormat('d M Y'),
        ])->values();

        if ($defaultImages->isEmpty()) {
            $defaultImages = collect($fallbackImages);
        }

        $imagesForSection = function (string $section, int $limit, $fallback) use ($homepageImagePlacements, $galleryImageUrl) {
            $placements = $homepageImagePlacements->get($section, collect());

            if ($placements->isEmpty()) {
                return collect($fallback)->take($limit)->values();
            }

            $selected = $placements
                ->where('is_active', true)
                ->filter(fn ($placement) => $placement->galleryItem?->status === 'published')
                ->map(fn ($placement, $index) => [
                    'src' => $galleryImageUrl($placement->galleryItem, $index),
                    'alt' => $placement->alt_text
                        ?: $placement->galleryItem->caption
                        ?: $placement->galleryItem->title
                        ?: 'Dokumentasi kegiatan MI Hubbul Wathan',
                    'label' => $placement->galleryItem->title ?: 'Dokumentasi Kegiatan',
                    'date' => optional($placement->galleryItem->created_at)->translatedFormat('d M Y'),
                ])
                ->take($limit)
                ->values();

            return $selected->isNotEmpty()
                ? $selected
                : collect($fallback)->take($limit)->values();
        };

        $heroSlides = $imagesForSection(\App\Models\HomepageImage::SECTION_HERO, 5, $defaultImages);
        $activityImages = $imagesForSection(\App\Models\HomepageImage::SECTION_ACTIVITIES, 4, $defaultImages);

        $overviewUrl = fn ($page) => match ($page->slug) {
            'profil-sekolah' => route('about'),
            'struktur-organisasi' => route('organization'),
            default => route('pages.show', $page->slug),
        };
    @endphp

    {{-- 1. Sharp Flat Clean Hero Section --}}
    <section class="relative bg-white pt-6 pb-8 sm:pt-8 sm:pb-12 border-b border-slate-200">
        <div class="site-container">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:gap-10 lg:items-center">
                {{-- Teks Hero --}}
                <div class="w-full">
                    <span class="badge-accent mb-2.5 inline-block">Penerimaan Siswa Baru {{ $academicYear }}</span>
                    <h1 class="display-title text-slate-900">
                        Membentuk Generasi Berakhlak, Cerdas, dan Mandiri
                    </h1>
                    <p class="lead-text mt-3">
                        Selamat datang di portal resmi MI Hubbul Wathan Desa Toli-Toli. Kami berkomitmen memberikan lingkungan pendidikan dasar Islam yang unggul, terpadu, dan peduli pada tumbuh kembang karakter siswa.
                    </p>
                    <div class="mt-5 flex flex-wrap items-center gap-2.5">
                        <a href="{{ route('ppdb.create') }}" class="btn-primary w-full sm:w-auto">Daftar PPDB Online &rarr;</a>
                        <a href="{{ route('about') }}" class="btn-secondary w-full sm:w-auto">Profil Madrasah</a>
                    </div>

                    {{-- Bilah Statistik Flat Tegas --}}
                    <div class="stat-bar mt-6">
                        <div class="stat-item">
                            <div class="stat-value">{{ $teacherCount }}</div>
                            <span class="stat-label">Guru &amp; Tendik</span>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $extracurricularCount }}</div>
                            <span class="stat-label">Ekstrakurikuler</span>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $academicYear }}</div>
                            <span class="stat-label">Tahun Ajaran</span>
                        </div>
                    </div>
                </div>

                {{-- Foto Slider Asli Terang & Kotak Flat --}}
                <div class="relative w-full overflow-hidden border border-slate-200 bg-slate-100" data-hero-carousel>
                    <div class="relative aspect-[4/3] w-full overflow-hidden">
                        @foreach($heroSlides as $index => $slide)
                            <div class="{{ $index === 0 ? 'opacity-100' : 'opacity-0 pointer-events-none' }} absolute inset-0 transition-opacity duration-500 ease-out" data-hero-slide>
                                <img src="{{ $slide['src'] }}" alt="{{ $slide['alt'] }}" class="h-full w-full object-cover">
                            </div>
                        @endforeach

                        @if($heroSlides->count() > 1)
                            <div class="absolute bottom-3 right-3 z-20 flex items-center gap-1.5 bg-black/60 px-2.5 py-1">
                                @foreach($heroSlides as $index => $slide)
                                    <button type="button" class="{{ $index === 0 ? 'w-4 bg-white' : 'w-1.5 bg-white/50' }} h-1 transition-all cursor-pointer border-0" data-hero-indicator="{{ $index }}" aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 2. Sambutan Kepala Madrasah --}}
    @if($principalMessage)
        @php
            $principalPhoto = \App\Support\PublicImage::storageUrl(
                $principal?->photo,
                asset('images/placeholders/teacher-placeholder.svg')
            );
        @endphp
        <section class="site-container section-pad">
            <div class="grid gap-6 lg:grid-cols-[minmax(11rem,0.28fr)_minmax(0,0.72fr)] lg:gap-10 lg:items-center">
                <div class="mx-auto w-full max-w-xs text-center lg:mx-0 lg:max-w-none">
                    <div class="border border-slate-200 bg-white p-2">
                        <img src="{{ $principalPhoto }}" alt="Foto {{ $principal?->name ?? 'Kepala Madrasah' }}" class="aspect-[3/4] w-full object-cover object-top bg-slate-50 border border-slate-100">
                        <div class="pt-2.5 pb-1">
                            <p class="font-bold text-slate-900 text-sm">{{ $principal?->name ?? 'Kepala Madrasah' }}</p>
                            <p class="text-[11px] font-bold text-brand-700 uppercase tracking-wider mt-0.5">{{ $principal?->position ?? 'Kepala Madrasah' }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="eyebrow">Sambutan</span>
                    <h2 class="section-title mt-0.5">Membangun Karakter Santun &amp; Berprestasi</h2>
                    <article class="rich-content mt-2.5">
                        {!! \App\Support\RichContent::render($principalMessage->body) !!}
                    </article>
                </div>
            </div>
        </section>
    @endif

    {{-- 3. Galeri Foto Kegiatan --}}
    <section class="section-surface border-y">
        <div class="site-container section-pad">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="eyebrow">Dokumentasi</span>
                    <h2 class="section-title mt-0.5">Suasana Kegiatan Madrasah</h2>
                </div>
                <a href="{{ route('gallery') }}" class="link-more">Lihat semua album &rarr;</a>
            </div>

            <div class="mt-5 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
                @foreach($activityImages as $image)
                    <button type="button" class="gallery-item group text-left cursor-zoom-in" data-gallery-view data-gallery-group="home-activities" data-gallery-src="{{ $image['src'] }}" data-gallery-alt="{{ $image['alt'] }}" data-gallery-title="{{ $image['label'] }}" data-gallery-date="{{ $image['date'] ?? '' }}" data-gallery-description="{{ $image['alt'] }}">
                        <span class="gallery-media block aspect-[4/3]">
                            <img src="{{ $image['src'] }}" alt="{{ $image['alt'] }}" loading="lazy" class="h-full w-full object-cover">
                        </span>
                        <span class="gallery-caption">{{ $image['label'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 4. Berita & Pengumuman --}}
    <section class="site-container section-pad">
        <div class="grid gap-8 lg:grid-cols-[1.4fr_1fr] lg:gap-10">
            <div>
                <div class="flex items-end justify-between border-b border-slate-200 pb-2">
                    <h2 class="section-title mt-0">Berita Terbaru</h2>
                    <a href="{{ route('posts.index') }}" class="link-more">Semua berita &rarr;</a>
                </div>
                <div class="mt-2">
                    @forelse($posts as $post)
                        @include('portal.partials.news-list-item', ['post' => $post])
                    @empty
                        <p class="py-5 text-xs text-slate-500">Belum ada berita yang dipublikasikan.</p>
                    @endforelse
                </div>

                @if($overviewPages->isNotEmpty())
                    <div class="mt-6 border border-slate-200 bg-slate-50/70 p-4">
                        <h3 class="card-title text-xs uppercase tracking-wider text-slate-700">Profil &amp; Struktur Madrasah</h3>
                        <div class="mt-2 grid gap-1">
                            @foreach($overviewPages as $overviewPage)
                                <a href="{{ $overviewUrl($overviewPage) }}" class="flex items-center justify-between bg-white px-3 py-2 border border-slate-200 hover:border-slate-300 transition">
                                    <span class="text-xs font-bold text-slate-800">{{ $overviewPage->title }}</span>
                                    <span class="text-[11px] font-bold text-brand-700">Lihat &rarr;</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div>
                <div class="flex items-end justify-between border-b border-slate-200 pb-2">
                    <h2 class="section-title mt-0">Pengumuman</h2>
                    <a href="{{ route('announcements.index') }}" class="link-more">Semua &rarr;</a>
                </div>
                <div class="mt-2">
                    @forelse($announcements as $announcement)
                        <a href="{{ route('announcements.show', $announcement->slug) }}" class="clean-list-item">
                            <div class="min-w-0 flex-1">
                                <h3 class="clean-list-title">{{ $announcement->title }}</h3>
                                <p class="clean-list-meta">{{ optional($announcement->published_at)->diffForHumans() }}</p>
                            </div>
                        </a>
                    @empty
                        <p class="py-5 text-xs text-slate-500">Belum ada pengumuman aktif.</p>
                    @endforelse
                </div>

                {{-- Agenda Terdekat --}}
                @if($events->isNotEmpty())
                    <div class="mt-5 border border-slate-200 p-4 bg-white">
                        <h3 class="card-title text-xs uppercase tracking-wider text-slate-700">Agenda Terdekat</h3>
                        <div class="mt-2">
                            @foreach($events as $event)
                                @include('portal.partials.event-row', ['event' => $event])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- 5. Bersih & Minimalist CTA PPDB --}}
    @php $ppdbPhone = $portalSettings['phone'] ?? '085396590157'; @endphp
    <section class="site-container pb-12">
        <div class="border border-slate-200 bg-slate-50 p-6 sm:p-8">
            <div class="max-w-2xl">
                <span class="badge-accent">Penerimaan Siswa Baru</span>
                <h2 class="display-title text-xl sm:text-2xl text-slate-900 mt-2">Mari Bergabung Bersama MI Hubbul Wathan</h2>
                <p class="lead-text mt-1.5 text-slate-600">Pendaftaran tahun ajaran {{ $academicYear }} telah dibuka. Daftarkan putra-putri Anda sekarang secara online atau hubungi panitia untuk informasi lebih lanjut.</p>
                <div class="mt-5 flex flex-wrap gap-2.5">
                    <a href="{{ route('ppdb.create') }}" class="btn-primary w-full sm:w-auto">Daftar Sekarang</a>
                    <a href="https://wa.me/62{{ ltrim($ppdbPhone, '0') }}" target="_blank" rel="noopener" class="btn-secondary w-full sm:w-auto text-center">Konsultasi via WhatsApp</a>
                </div>
            </div>
        </div>
    </section>
@endsection
