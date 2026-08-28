@extends('layouts.portal')

@section('content')
    @php
        $isPost = $type === 'post';
        $itemUrl = fn ($item): string => $isPost
            ? route('posts.show', $item->slug)
            : route('announcements.show', $item->slug);
        $placeholder = asset($isPost
            ? 'images/placeholders/news-placeholder.svg'
            : 'images/placeholders/announcement-placeholder.svg');
        $intro = $isPost
            ? 'Kabar kegiatan, prestasi, dan agenda yang berjalan di madrasah.'
            : 'Informasi resmi yang perlu segera diketahui wali murid dan calon peserta didik.';

        $featured = $items->count() >= 3 ? $items->first() : null;
        $listItems = $featured ? $items->skip(1) : $items;
    @endphp

    <section class="site-container section-pad">
        <div class="page-header mx-auto text-center flex flex-col items-center">
            <span class="badge-accent">{{ $isPost ? 'Kabar Madrasah' : 'Pemberitahuan' }}</span>
            <h1 class="page-title">{{ $title }}</h1>
            <p class="lead-text mx-auto">{{ $intro }}</p>
        </div>

        @if($featured)
            @php $featuredImage = \App\Support\PublicImage::storageUrl($featured->cover_image, $placeholder); @endphp
            <a href="{{ $itemUrl($featured) }}" class="group mt-10 grid gap-6 lg:grid-cols-[1.1fr_1fr] lg:items-center p-4 sm:p-6 border border-slate-200 bg-white hover:border-slate-300 transition">
                <span class="gallery-media block aspect-[16/10] overflow-hidden">
                    <img src="{{ $featuredImage }}" alt="Gambar sampul {{ $featured->title }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                </span>
                <span class="block">
                    <span class="clean-list-meta mt-0 block">{{ optional($featured->published_at)->diffForHumans() }}</span>
                    <h2 class="text-xl sm:text-2xl font-bold mt-2 text-slate-900 transition group-hover:text-brand-700 leading-snug">{{ $featured->title }}</h2>
                    @if($featured->excerpt)
                        <span class="mt-3 block text-xs sm:text-sm leading-relaxed text-slate-600">{{ $featured->excerpt }}</span>
                    @endif
                    <span class="link-more mt-4">Baca selengkapnya &rarr;</span>
                </span>
            </a>
        @endif

        <div class="mt-8 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($listItems as $item)
                @php $thumb = \App\Support\PublicImage::storageUrl($item->cover_image, $placeholder); @endphp
                <a href="{{ $itemUrl($item) }}" class="group flex flex-col border border-slate-200 bg-white p-4 transition hover:border-slate-300 hover:bg-slate-50/50">
                    <span class="gallery-media block aspect-[16/10] overflow-hidden w-full">
                        <img src="{{ $thumb }}" alt="Gambar sampul {{ $item->title }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                    </span>
                    <span class="mt-3 flex-1 flex flex-col">
                        <span class="clean-list-meta block mt-0">{{ optional($item->published_at)->diffForHumans() }}</span>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900 transition group-hover:text-brand-700 leading-snug mt-1.5 line-clamp-2">{{ $item->title }}</h2>
                        @if($item->excerpt)
                            <p class="mt-2 text-xs leading-relaxed text-slate-500 line-clamp-2 flex-1">{{ $item->excerpt }}</p>
                        @endif
                        <span class="link-more mt-3">Baca &rarr;</span>
                    </span>
                </a>
            @empty
                @unless($featured)
                    <p class="py-12 text-center text-xs text-slate-500 col-span-full">
                        {{ $isPost ? 'Berita akan muncul di sini setelah dipublikasikan pengelola madrasah.' : 'Belum ada pengumuman aktif dari madrasah.' }}
                    </p>
                @endunless
            @endforelse
        </div>

        @if($items->hasPages())
            <div class="mt-10 border-t border-slate-100 pt-6 flex justify-center">
                {{ $items->links() }}
            </div>
        @endif
    </section>
@endsection
