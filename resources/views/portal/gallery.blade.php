@extends('layouts.portal')

@section('content')
    @php
        $fallbackImages = [
            [
                'src' => asset('images/placeholders/activity-literacy.svg'),
                'alt' => 'Kegiatan literasi madrasah',
                'title' => 'Literasi Perpustakaan',
            ],
            [
                'src' => asset('images/placeholders/activity-class.svg'),
                'alt' => 'Kegiatan belajar di kelas',
                'title' => 'Belajar Bersama',
            ],
            [
                'src' => asset('images/placeholders/activity-religious.svg'),
                'alt' => 'Pembiasaan keagamaan siswa',
                'title' => 'Pendampingan Guru',
            ],
            [
                'src' => asset('images/placeholders/activity-creative.svg'),
                'alt' => 'Aktivitas kreativitas siswa',
                'title' => 'Kelas Aktif',
            ],
            [
                'src' => asset('images/placeholders/activity-literacy.svg'),
                'alt' => 'Literasi dan numerasi',
                'title' => 'Belajar Terarah',
            ],
            [
                'src' => asset('images/placeholders/activity-creative.svg'),
                'alt' => 'Pengembangan minat bakat',
                'title' => 'Aktivitas Kreatif',
            ],
        ];

        $galleryImageUrl = fn ($item, int $index): string => \App\Support\PublicImage::storageUrl(
            $item->image,
            $fallbackImages[$index % count($fallbackImages)]['src']
        );

        $fallbackAlbum = [
            'title' => 'Dokumentasi Kegiatan',
            'description' => null,
            'photos' => collect($fallbackImages)->map(fn ($image) => [
                'src' => $image['src'],
                'alt' => $image['alt'],
                'title' => $image['title'],
                'date' => null,
            ]),
        ];

        $galleryAlbums = $albums
            ->map(fn ($album) => [
                'title' => $album->title,
                'description' => $album->description,
                'photos' => $album->items->map(fn ($item, $index) => [
                    'src' => $galleryImageUrl($item, $index),
                    'alt' => $item->caption ?: $item->title ?: 'Dokumentasi kegiatan MI Hubbul Wathan',
                    'title' => $item->title,
                    'date' => optional($item->created_at)->translatedFormat('d M Y'),
                ])->values(),
            ])
            ->filter(fn ($album) => $album['photos']->isNotEmpty())
            ->values();

        if ($galleryAlbums->isEmpty()) {
            $galleryAlbums = collect([$fallbackAlbum]);
        }
    @endphp

    <section class="site-container section-pad">
        <div class="page-header">
            <span class="badge-accent">Dokumentasi</span>
            <h1 class="page-title">Galeri Kegiatan</h1>
            <p class="lead-text">Dokumentasi kegiatan belajar mengajar, pembiasaan islami, dan agenda madrasah.</p>
        </div>

        <div class="mt-10 grid gap-12">
            @foreach($galleryAlbums as $album)
                @php
                    $albumIndex = $loop->index;
                @endphp
                <section class="rounded-lg border border-slate-200 bg-white p-5 sm:p-6">
                    <div class="flex flex-wrap items-end justify-between gap-3 border-b border-slate-100 pb-3">
                        <div>
                            <h2 class="text-lg font-bold text-slate-900">{{ $album['title'] }}</h2>
                            @if(filled($album['description']))
                                <p class="mt-1 max-w-2xl text-xs sm:text-sm text-slate-500">{{ $album['description'] }}</p>
                            @endif
                        </div>
                        <span class="text-xs font-bold text-brand-700">{{ $album['photos']->count() }} Foto</span>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach($album['photos'] as $photo)
                            <button type="button" class="gallery-item group text-left cursor-zoom-in" data-gallery-view data-gallery-group="album-{{ $albumIndex }}" data-gallery-src="{{ $photo['src'] }}" data-gallery-alt="{{ $photo['alt'] }}" data-gallery-title="{{ $photo['title'] }}" data-gallery-date="{{ $photo['date'] ?? '' }}" data-gallery-description="{{ $photo['alt'] }}" aria-label="Perbesar gambar {{ $photo['title'] ?: 'dokumentasi madrasah' }}">
                                <span class="gallery-media block aspect-[4/3]">
                                    <img src="{{ $photo['src'] }}" alt="{{ $photo['alt'] }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                </span>
                                @if(filled($photo['title']))
                                    <span class="gallery-caption line-clamp-1 text-xs">{{ $photo['title'] }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    </section>
@endsection
