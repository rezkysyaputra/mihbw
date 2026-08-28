@extends('layouts.portal')

@section('content')
    @php
        $coverImage = isset($item->cover_image)
            ? \App\Support\PublicImage::storageUrl($item->cover_image)
            : null;
        $wordCount = str($item->body)->stripTags()->wordCount();
        $readMinutes = max(1, (int) ceil($wordCount / 200));
        $shareUrl = url()->current();
        $shareTitle = $item->title;
    @endphp

    <article class="site-container section-pad">
        {{-- Header artikel rata kiri natural dalam kolom baca yang pas --}}
        <div class="prose-narrow mx-auto">
            <span class="badge-accent">{{ $label }}</span>
            <h1 class="page-title mt-2.5">{{ $item->title }}</h1>
            <p class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 font-medium">
                @if($item->published_at)
                    <time datetime="{{ $item->published_at->toDateString() }}">{{ $item->published_at->translatedFormat('l, d F Y') }}</time>
                    <span aria-hidden="true">&middot;</span>
                @endif
                <span>{{ $readMinutes }} menit baca</span>
            </p>
        </div>

        @if($coverImage)
            <div class="prose-narrow mx-auto mt-8">
                <img src="{{ $coverImage }}" alt="Gambar sampul {{ $item->title }}" loading="lazy" class="max-h-[480px] w-full border border-slate-200 object-cover">
            </div>
        @endif

        <div class="rich-content prose-narrow mx-auto mt-8 border-t border-slate-100 pt-6">
            {!! \App\Support\RichContent::render($item->body) !!}
        </div>

        {{-- Share & Copy Link Toolbar --}}
        <div class="prose-narrow mx-auto mt-8 border-y border-slate-100 py-4" id="share-toolbar">
            <div class="flex flex-wrap items-center justify-between gap-3 text-xs font-bold">
                <span class="uppercase tracking-wider text-slate-500">Bagikan {{ $label }}:</span>
                <div class="flex items-center gap-2">
                    {{-- WhatsApp --}}
                    <a href="https://api.whatsapp.com/send?text={{ rawurlencode($shareTitle . ' - ' . $shareUrl) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 border border-slate-200 bg-white px-3 py-1.5 text-slate-700 hover:border-brand-600 hover:text-brand-800 transition" title="Bagikan ke WhatsApp">
                        <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>WhatsApp</span>
                    </a>

                    {{-- Facebook --}}
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ rawurlencode($shareUrl) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 border border-slate-200 bg-white px-3 py-1.5 text-slate-700 hover:border-brand-600 hover:text-brand-800 transition" title="Bagikan ke Facebook">
                        <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        <span>Facebook</span>
                    </a>

                    {{-- Copy Link Button --}}
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $shareUrl }}'); const btn = this.querySelector('span'); const orig = btn.innerText; btn.innerText = 'Tersalin!'; setTimeout(() => btn.innerText = orig, 2000);" class="inline-flex items-center gap-1.5 border border-slate-200 bg-white px-3 py-1.5 text-slate-700 hover:border-brand-600 hover:text-brand-800 transition cursor-pointer" title="Salin Tautan Berita">
                        <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                        </svg>
                        <span>Salin Tautan</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="prose-narrow mx-auto mt-6">
            <a href="{{ $indexUrl }}" class="link-more">&larr; {{ $indexLabel }}</a>
        </div>
    </article>

    @if(($related ?? collect())->isNotEmpty())
        <section class="section-surface border-t">
            <div class="site-container section-pad py-10">
                <h2 class="section-title mt-0">Baca juga</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-3">
                    @foreach($related as $relatedItem)
                        <a href="{{ $relatedItem['url'] }}" class="border border-slate-200 bg-white p-4 hover:border-slate-300 transition">
                            <span class="clean-list-title block text-sm">{{ $relatedItem['title'] }}</span>
                            <span class="clean-list-meta block mt-2">{{ optional($relatedItem['published_at'])->diffForHumans() }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
