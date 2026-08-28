@extends('layouts.portal')

@section('content')
    @php
        $isContactPage = $page->slug === 'kontak';
        $mapQuery = $contactSettings['maps_query'] ?? $contactSettings['address'] ?? null;
        $mapEmbedUrl = filled($contactSettings['maps_embed_url'] ?? null)
            ? $contactSettings['maps_embed_url']
            : (filled($mapQuery) ? 'https://www.google.com/maps?q='.rawurlencode($mapQuery).'&output=embed' : null);
        $mapLink = filled($mapQuery)
            ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($mapQuery)
            : null;
        $contactPhone = $contactSettings['phone'] ?? null;
        $contactEmail = $contactSettings['email'] ?? null;
        $contactInstagram = $contactSettings['instagram'] ?? null;
        $contactFacebook = $contactSettings['facebook'] ?? null;
        $contactHours = $contactSettings['office_hours'] ?? null;
    @endphp

    <section class="site-container section-pad">
        <div class="page-header prose-narrow mx-auto text-center flex flex-col items-center">
            <span class="badge-accent">{{ $pageCategory ?? 'Profil Madrasah' }}</span>
            <h1 class="page-title">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="lead-text mx-auto">{{ $page->excerpt }}</p>
            @endif
        </div>

        <article class="rich-content prose-narrow mx-auto mt-8">
            {!! \App\Support\RichContent::render($page->body) !!}
        </article>

        @if($isContactPage && (filled($contactSettings['address'] ?? null) || $mapEmbedUrl))
            <div class="mt-12 mx-auto max-w-4xl grid gap-8 border-t border-slate-100 pt-8 lg:grid-cols-[1fr_1.15fr] lg:gap-10">
                <div>
                    <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Hubungi &amp; Kunjungi</h2>
                    <dl class="mt-4 grid gap-3.5 text-xs sm:text-sm">
                        @if(filled($contactSettings['address'] ?? null))
                            <div>
                                <dt class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Alamat</dt>
                                <dd class="mt-0.5 leading-relaxed text-slate-600">{{ $contactSettings['address'] }}</dd>
                            </div>
                        @endif
                        @if(filled($contactHours))
                            <div>
                                <dt class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Jam Pelayanan</dt>
                                <dd class="mt-0.5 leading-relaxed text-slate-600">{{ $contactHours }}</dd>
                            </div>
                        @endif
                        @if(filled($contactPhone))
                            <div>
                                <dt class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">WhatsApp</dt>
                                <dd class="mt-0.5"><a class="link-more" href="https://wa.me/62{{ ltrim($contactPhone, '0') }}" target="_blank" rel="noopener">{{ $contactPhone }} &rarr;</a></dd>
                            </div>
                        @endif
                        @if(filled($contactEmail))
                            <div>
                                <dt class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Email</dt>
                                <dd class="mt-0.5"><a class="link-more" href="mailto:{{ $contactEmail }}">{{ $contactEmail }} &rarr;</a></dd>
                            </div>
                        @endif
                        @if(filled($contactInstagram) || filled($contactFacebook))
                            <div>
                                <dt class="font-bold text-slate-900 uppercase tracking-wider text-[11px]">Media Sosial</dt>
                                <dd class="mt-1 flex flex-wrap gap-3">
                                    @if(filled($contactInstagram))
                                        <a class="link-more" href="https://www.instagram.com/{{ ltrim($contactInstagram, '@') }}" target="_blank" rel="noopener">Instagram &rarr;</a>
                                    @endif
                                    @if(filled($contactFacebook))
                                        <a class="link-more" href="https://www.facebook.com/{{ ltrim($contactFacebook, '@') }}" target="_blank" rel="noopener">Facebook &rarr;</a>
                                    @endif
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                @if($mapEmbedUrl)
                    <div>
                        <div class="overflow-hidden border border-slate-200 bg-slate-50">
                            <iframe
                                src="{{ $mapEmbedUrl }}"
                                title="Peta lokasi MI Hubbul Wathan"
                                class="h-64 w-full sm:h-72"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                        @if($mapLink)
                            <a href="{{ $mapLink }}" target="_blank" rel="noopener" class="btn-secondary mt-3 text-xs">Buka di Google Maps</a>
                        @endif
                    </div>
                @endif
            </div>
        @endif
    </section>
@endsection
