@extends('layouts.portal')

@section('content')
    <section class="site-container section-pad">
        <div class="page-header mx-auto text-center flex flex-col items-center">
            <span class="badge-accent">Profil Madrasah</span>
            <h1 class="page-title">Tentang MI Hubbul Wathan</h1>
            <p class="lead-text mx-auto">{{ $profilePage->excerpt }}</p>
        </div>

        <div class="mt-10 mx-auto max-w-5xl grid gap-8 lg:grid-cols-[1.6fr_1fr] lg:gap-12">
            <article class="rich-content">
                {!! \App\Support\RichContent::render($profilePage->body) !!}
            </article>

            <aside class="panel h-fit p-5 sm:p-6">
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-900 border-b border-slate-100 pb-2.5">Sekilas Madrasah</h2>
                <dl class="mt-3.5 grid gap-3 text-xs sm:text-sm">
                    <div>
                        <dt class="font-bold text-slate-800 text-[11px] uppercase tracking-wider">Jenjang Pendidikan</dt>
                        <dd class="mt-0.5 text-slate-600">Madrasah Ibtidaiyah (Setara SD)</dd>
                    </div>
                    <div>
                        <dt class="font-bold text-slate-800 text-[11px] uppercase tracking-wider">Lembaga Naungan</dt>
                        <dd class="mt-0.5 text-slate-600">YPPM Hubbul Wathan</dd>
                    </div>
                    @if(filled($portalSettings['address'] ?? null))
                        <div>
                            <dt class="font-bold text-slate-800 text-[11px] uppercase tracking-wider">Alamat</dt>
                            <dd class="mt-0.5 text-slate-600 leading-relaxed">{{ $portalSettings['address'] }}</dd>
                        </div>
                    @endif
                    @if(filled($portalSettings['office_hours'] ?? null))
                        <div>
                            <dt class="font-bold text-slate-800 text-[11px] uppercase tracking-wider">Jam Pelayanan</dt>
                            <dd class="mt-0.5 text-slate-600">{{ $portalSettings['office_hours'] }}</dd>
                        </div>
                    @endif
                </dl>
                <div class="mt-5 grid gap-1.5 border-t border-slate-100 pt-3.5 text-xs font-bold">
                    <a href="{{ route('pages.show', 'visi-misi') }}" class="link-more">Visi &amp; Misi &rarr;</a>
                    <a href="{{ route('organization') }}" class="link-more">Struktur Organisasi &rarr;</a>
                    <a href="{{ route('teachers') }}" class="link-more">Guru &amp; Tenaga Pendidik &rarr;</a>
                </div>
            </aside>
        </div>
    </section>

    <section class="section-surface border-y">
        <div class="site-container section-pad">
            <div class="mx-auto max-w-4xl text-center flex flex-col items-center">
                <span class="eyebrow">Perjalanan</span>
                <h2 class="section-title mt-1">{{ $historyPage->title }}</h2>
                <article class="rich-content text-left mt-4 w-full">
                    {!! \App\Support\RichContent::render($historyPage->body) !!}
                </article>
            </div>
        </div>
    </section>

    <section class="site-container section-pad">
        <div class="mx-auto max-w-4xl text-center flex flex-col items-center">
            <span class="eyebrow">Sarana</span>
            <h2 class="section-title mt-1">{{ $facilitiesPage->title }}</h2>
            <article class="rich-content text-left mt-4 w-full">
                {!! \App\Support\RichContent::render($facilitiesPage->body) !!}
            </article>
        </div>

        @if($facilityImages->isNotEmpty())
            <div class="mt-8 mx-auto max-w-5xl grid grid-cols-2 gap-3.5 sm:grid-cols-3">
                @foreach($facilityImages as $image)
                    @php
                        $imageUrl = \App\Support\PublicImage::storageUrl(
                            $image->image,
                            asset('images/placeholders/activity-class.svg')
                        );
                    @endphp
                    <button
                        type="button"
                        class="gallery-item group text-left cursor-zoom-in"
                        data-gallery-view
                        data-gallery-group="about-facilities"
                        data-gallery-src="{{ $imageUrl }}"
                        data-gallery-alt="{{ $image->caption ?: $image->title }}"
                        data-gallery-title="{{ $image->title ?: 'Fasilitas Madrasah' }}"
                        data-gallery-date=""
                        data-gallery-description="{{ $image->caption }}"
                        aria-label="Perbesar gambar {{ $image->title ?: 'fasilitas madrasah' }}"
                    >
                        <span class="gallery-media block aspect-[16/11]">
                            <img src="{{ $imageUrl }}" alt="{{ $image->caption ?: $image->title }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                        </span>
                        <span class="gallery-caption">{{ $image->title }}</span>
                    </button>
                @endforeach
            </div>
        @endif
    </section>
@endsection
