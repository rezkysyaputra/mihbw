@extends('layouts.portal')

@section('content')
    <section class="site-container section-pad">
        <div class="page-header mx-auto text-center flex flex-col items-center">
            <span class="badge-accent">Pengembangan Diri</span>
            <h1 class="page-title">Ekstrakurikuler</h1>
            <p class="lead-text mx-auto">Kegiatan di luar jam pelajaran untuk melatih minat, bakat, dan kemandirian peserta didik.</p>
        </div>

        <div class="mt-10 mx-auto max-w-5xl grid gap-5">
            @forelse($items as $item)
                @php
                    $imagePaths = collect($item->images ?: [])
                        ->when(empty($item->images) && filled($item->image), fn ($images) => $images->push($item->image))
                        ->filter()
                        ->values();
                    $imageUrls = $imagePaths
                        ->map(fn ($path) => \App\Support\PublicImage::storageUrl($path))
                        ->filter()
                        ->values();
                @endphp
                <article class="panel p-5 sm:p-6">
                    <div class="grid gap-6 lg:grid-cols-[1fr_1fr] lg:items-center">
                        <div>
                            <h2 class="card-title text-lg">{{ $item->name }}</h2>
                            @if($item->description)
                                <p class="mt-2 text-xs sm:text-sm leading-relaxed text-slate-600">{{ $item->description }}</p>
                            @endif
                            @if($item->coach || $item->schedule)
                                <div class="mt-4 flex flex-wrap gap-2 text-xs">
                                    @if($item->coach)
                                        <span class="inline-flex items-center bg-slate-100 px-2.5 py-1 font-semibold text-slate-700">
                                            Pembina: {{ $item->coach }}
                                        </span>
                                    @endif
                                    @if($item->schedule)
                                        <span class="inline-flex items-center bg-brand-50 px-2.5 py-1 font-semibold text-brand-800 border border-brand-100">
                                            Jadwal: {{ $item->schedule }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if($imageUrls->isNotEmpty())
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($imageUrls as $imageIndex => $imageUrl)
                                    <button type="button" class="gallery-item group text-left cursor-zoom-in" data-gallery-view data-gallery-group="extracurricular-{{ $item->id }}" data-gallery-src="{{ $imageUrl }}" data-gallery-alt="Foto kegiatan {{ $item->name }}" data-gallery-title="{{ $item->name }}" data-gallery-date="" data-gallery-description="{{ $item->description }}" aria-label="Perbesar foto {{ $item->name }} {{ $imageIndex + 1 }}">
                                        <span class="gallery-media block aspect-square">
                                            <img src="{{ $imageUrl }}" alt="Foto kegiatan {{ $item->name }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <p class="py-12 text-center text-xs text-slate-500">Daftar ekstrakurikuler akan tampil setelah dilengkapi pengelola madrasah.</p>
            @endforelse
        </div>
    </section>
@endsection
