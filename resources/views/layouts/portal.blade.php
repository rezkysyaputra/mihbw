<!DOCTYPE html>
<html lang="id">
<head>
    @php
        $siteName = config('app.name', 'MI Hubbul Wathan');
        $defaultDescription = 'Portal resmi MI Hubbul Wathan berisi profil madrasah, informasi akademik, berita sekolah, pengumuman, galeri, unduhan, dan PPDB online.';
        $seoTitle = $seoTitle ?? $title ?? $siteName;
        $seoDescription = str($seoDescription ?? $defaultDescription)->limit(160, '');
        $seoUrl = $seoUrl ?? url()->current();
        $seoType = $seoType ?? 'website';
        $siteLogo = asset('images/logo-hubbul-wathan.png');
        $seoImage = $seoImage ?? $siteLogo;
        $robots = $robots ?? 'index, follow';
        $portalPhone = $portalSettings['phone'] ?? '085396590157';
        $portalEmail = $portalSettings['email'] ?? 'yppm.hubbulwathan@gmail.com';
        $portalInstagram = ltrim($portalSettings['instagram'] ?? 'yppm.hubbulwathan', '@');
        $portalFacebook = ltrim($portalSettings['facebook'] ?? 'yppm.hubbulwathan', '@');
        $portalAddress = $portalSettings['address'] ?? null;
        $portalWhatsapp = 'https://wa.me/62'.ltrim($portalPhone, '0');
        $portalMapsQuery = $portalSettings['maps_query'] ?? $portalAddress;
        $portalMapsLink = filled($portalMapsQuery)
            ? 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($portalMapsQuery)
            : null;
        $socialProfiles = collect([
            'https://www.instagram.com/'.$portalInstagram,
            'https://www.facebook.com/'.$portalFacebook,
        ])->filter()->values()->all();
        $schema = $schema ?? [
            '@context' => 'https://schema.org',
            '@type' => 'ElementarySchool',
            'name' => $siteName,
            'url' => url('/'),
            'description' => $defaultDescription,
            'inLanguage' => 'id-ID',
            'address' => $portalAddress,
            'telephone' => $portalPhone,
            'email' => $portalEmail,
            'sameAs' => $socialProfiles,
        ];
        $activeNav = fn (array|string $patterns): string => request()->routeIs(...(array) $patterns) ? ' aria-current="page"' : '';
        $currentPageSlug = request()->routeIs('pages.show') ? request()->route('slug') : null;
        $profileLinks = [
            ['label' => 'Tentang Madrasah', 'url' => route('about'), 'active' => request()->routeIs('about')],
            ['label' => 'Visi Misi', 'url' => route('pages.show', 'visi-misi'), 'active' => $currentPageSlug === 'visi-misi'],
            ['label' => 'Struktur Organisasi', 'url' => route('organization'), 'active' => request()->routeIs('organization')],
            ['label' => 'Kontak', 'url' => route('pages.show', 'kontak'), 'active' => $currentPageSlug === 'kontak'],
        ];
        $academicLinks = [
            ['label' => 'Kurikulum', 'url' => route('pages.show', 'kurikulum'), 'active' => $currentPageSlug === 'kurikulum'],
            ['label' => 'Guru', 'url' => route('teachers'), 'active' => request()->routeIs('teachers')],
            ['label' => 'Ekstrakurikuler', 'url' => route('extracurriculars'), 'active' => request()->routeIs('extracurriculars')],
            ['label' => 'Kalender Akademik', 'url' => route('calendar'), 'active' => request()->routeIs('calendar')],
        ];
        $profileMenuActive = collect($profileLinks)->contains('active', true);
        $academicMenuActive = collect($academicLinks)->contains('active', true);
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $robots }}">
    <meta name="author" content="{{ $siteName }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" type="image/png" href="{{ $siteLogo }}">
    <link rel="apple-touch-icon" href="{{ $siteLogo }}">
    <link rel="canonical" href="{{ $seoUrl }}">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">

    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:type" content="{{ $seoType }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoUrl }}">
    <meta property="og:image" content="{{ $seoImage }}">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:image" content="{{ $seoImage }}">

    <script type="application/ld+json">
        {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-white text-slate-900 selection:bg-brand-100 selection:text-brand-900">
    <a href="#konten" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-brand-800 focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-white">Lompat ke konten</a>

    <header class="portal-header sticky top-0 z-40">
        <div class="site-container py-3">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
                    <img src="{{ $siteLogo }}" alt="Logo MI Hubbul Wathan" class="h-9 w-9 shrink-0 object-contain sm:h-10 sm:w-10">
                    <span class="min-w-0">
                        <span class="block truncate text-base font-extrabold text-slate-900 sm:text-lg">MI Hubbul Wathan</span>
                        <span class="block text-[0.65rem] font-bold uppercase tracking-widest text-slate-400">Madrasah Ibtidaiyah</span>
                    </span>
                </a>

                <div class="flex items-center gap-2">
                    <nav class="hidden items-center gap-1 lg:flex" aria-label="Navigasi utama">
                        @foreach([
                            ['label' => 'Profil', 'links' => $profileLinks, 'active' => $profileMenuActive, 'caption' => 'Profil Madrasah'],
                            ['label' => 'Akademik', 'links' => $academicLinks, 'active' => $academicMenuActive, 'caption' => 'Informasi Akademik'],
                        ] as $menu)
                            <div class="group relative py-2">
                                <button type="button" class="nav-link inline-flex items-center gap-1" aria-haspopup="true" @if($menu['active']) aria-current="page" @endif>
                                    <span>{{ $menu['label'] }}</span>
                                    <svg class="h-3.5 w-3.5 opacity-60 transition duration-150 group-hover:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>

                                {{-- Submenu Dropdown Muncul Otomatis Saat di-Hover --}}
                                <div class="desktop-submenu invisible absolute left-0 top-full z-50 w-52 opacity-0 transition-all duration-150 group-hover:visible group-hover:opacity-100">
                                    <div class="border-b border-slate-100 px-3 py-2">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">{{ $menu['caption'] }}</p>
                                    </div>
                                    <div class="p-1">
                                        @foreach($menu['links'] as $link)
                                            <a href="{{ $link['url'] }}"{!! $link['active'] ? ' aria-current="page"' : '' !!} class="desktop-submenu-link">
                                                <span>{{ $link['label'] }}</span>
                                                <svg class="h-3.5 w-3.5 opacity-30" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6" />
                                                </svg>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <a class="nav-link" href="{{ route('posts.index') }}"{!! $activeNav('posts.*') !!}>Berita</a>
                        <a class="nav-link" href="{{ route('announcements.index') }}"{!! $activeNav('announcements.*') !!}>Pengumuman</a>
                        <a class="nav-link" href="{{ route('gallery') }}"{!! $activeNav('gallery') !!}>Galeri</a>
                        <a class="nav-link" href="{{ route('downloads') }}"{!! $activeNav('downloads') !!}>Unduhan</a>
                        <a class="nav-cta" href="{{ route('ppdb.create') }}"{!! $activeNav('ppdb.*') !!}>Daftar PPDB</a>
                    </nav>

                    <button type="button" class="portal-icon-button lg:hidden" data-menu-button aria-controls="mobile-nav" aria-expanded="false">
                        <span class="sr-only">Buka navigasi</span>
                        <svg class="h-5 w-5" data-menu-open-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" />
                        </svg>
                        <svg class="hidden h-5 w-5" data-menu-close-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <nav id="mobile-nav" class="mobile-nav-panel mt-3 hidden lg:hidden" data-mobile-menu aria-label="Navigasi seluler">
                <a class="mobile-nav-link" href="{{ route('home') }}"{!! $activeNav('home') !!}>Beranda</a>

                <button type="button" class="mobile-profile-toggle" data-mobile-profile-button aria-expanded="{{ $profileMenuActive ? 'true' : 'false' }}" aria-controls="mobile-profile-menu">
                    <span>Profil</span>
                    <svg class="{{ $profileMenuActive ? 'rotate-180' : '' }} h-4 w-4 transition-transform duration-150" data-mobile-profile-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div id="mobile-profile-menu" class="{{ $profileMenuActive ? '' : 'hidden' }} mobile-profile-menu" data-mobile-profile-menu>
                    @foreach($profileLinks as $link)
                        <a href="{{ $link['url'] }}"{!! $link['active'] ? ' aria-current="page"' : '' !!} class="mobile-subnav-link">{{ $link['label'] }}</a>
                    @endforeach
                </div>

                <button type="button" class="mobile-profile-toggle" data-mobile-academic-button aria-expanded="{{ $academicMenuActive ? 'true' : 'false' }}" aria-controls="mobile-academic-menu">
                    <span>Akademik</span>
                    <svg class="{{ $academicMenuActive ? 'rotate-180' : '' }} h-4 w-4 transition-transform duration-150" data-mobile-academic-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
                <div id="mobile-academic-menu" class="{{ $academicMenuActive ? '' : 'hidden' }} mobile-profile-menu" data-mobile-academic-menu>
                    @foreach($academicLinks as $link)
                        <a href="{{ $link['url'] }}"{!! $link['active'] ? ' aria-current="page"' : '' !!} class="mobile-subnav-link">{{ $link['label'] }}</a>
                    @endforeach
                </div>

                <div class="my-2 border-t border-slate-100"></div>

                <a class="mobile-nav-link" href="{{ route('posts.index') }}"{!! $activeNav('posts.*') !!}>Berita</a>
                <a class="mobile-nav-link" href="{{ route('announcements.index') }}"{!! $activeNav('announcements.*') !!}>Pengumuman</a>
                <a class="mobile-nav-link" href="{{ route('gallery') }}"{!! $activeNav('gallery') !!}>Galeri</a>
                <a class="mobile-nav-link" href="{{ route('downloads') }}"{!! $activeNav('downloads') !!}>Unduhan</a>
                <a class="btn-primary mt-2.5 w-full text-center" href="{{ route('ppdb.create') }}">Daftar PPDB</a>
            </nav>
        </div>
    </header>

    <main id="konten" class="min-h-[60vh]">
        @yield('content')
    </main>

    {{-- FULL VIEWPORT GALLERY PREVIEW (Tombol navigasi di tepi layar) --}}
    <div class="fixed inset-0 z-50 hidden select-none bg-black/95 backdrop-blur-md" data-gallery-modal role="dialog" aria-modal="true" aria-label="Tampilan galeri" aria-hidden="true">
        <button type="button" class="absolute inset-0 z-0 h-full w-full cursor-default" data-gallery-close aria-label="Tutup tampilan gambar"></button>

        {{-- Tombol Kiri (Tepi Layar) --}}
        <button type="button" class="gallery-control fixed left-4 top-1/2 -translate-y-1/2 z-30" data-gallery-prev aria-label="Gambar sebelumnya">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </button>

        {{-- Tombol Kanan (Tepi Layar) --}}
        <button type="button" class="gallery-control fixed right-4 top-1/2 -translate-y-1/2 z-30" data-gallery-next aria-label="Gambar berikutnya">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </button>

        {{-- Tombol Tutup & Counter --}}
        <div class="fixed left-4 top-4 z-30 bg-black/70 px-3 py-1 text-xs font-bold text-white border border-white/10" data-gallery-counter aria-live="polite"></div>
        <button type="button" class="gallery-control fixed right-4 top-4 z-30" data-gallery-close aria-label="Tutup tampilan gambar">
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>

        {{-- Gambar Utama --}}
        <div class="relative z-10 flex h-full w-full items-center justify-center p-4 sm:p-12 pointer-events-none">
            <img src="" alt="" class="pointer-events-auto max-h-[82vh] max-w-[85vw] object-contain shadow-2xl transition-opacity duration-200" data-gallery-modal-image>

            <div class="pointer-events-none fixed inset-x-0 bottom-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent px-4 pb-6 pt-12 text-center text-white">
                <p class="text-[11px] font-bold uppercase tracking-widest text-brand-100" data-gallery-modal-date></p>
                <h2 class="mt-0.5 text-base font-bold sm:text-lg text-white" data-gallery-modal-title></h2>
                <p class="mt-0.5 hidden max-w-xl mx-auto text-xs text-slate-300 sm:block" data-gallery-modal-description></p>
            </div>
        </div>
    </div>

    <footer class="portal-footer mt-16">
        <div class="site-container grid gap-8 py-12 lg:grid-cols-[1.4fr_0.8fr_0.8fr_1fr]">
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ $siteLogo }}" alt="Logo MI Hubbul Wathan" class="h-9 w-9 object-contain">
                    <div class="text-base font-bold text-white">MI Hubbul Wathan</div>
                </div>
                <p class="mt-3 max-w-sm text-xs leading-relaxed text-slate-400">Madrasah Ibtidaiyah di bawah naungan YPPM Hubbul Wathan. Membina akhlak, literasi, numerasi, dan kemandirian peserta didik.</p>
            </div>

            <div>
                <div class="footer-heading">Profil</div>
                <div class="mt-3 grid gap-1.5">
                    @foreach($profileLinks as $link)
                        <a class="footer-link" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="footer-heading">Akademik</div>
                <div class="mt-3 grid gap-1.5">
                    @foreach($academicLinks as $link)
                        <a class="footer-link" href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                    @endforeach
                </div>
            </div>

            <div>
                <div class="footer-heading">Kontak &amp; Alamat</div>
                <div class="mt-3 grid gap-1.5 text-xs">
                    @if(filled($portalAddress))
                        <p class="text-slate-400 leading-relaxed">{{ $portalAddress }}</p>
                        @if($portalMapsLink)
                            <a class="footer-link font-bold text-brand-100 mt-0.5 block" href="{{ $portalMapsLink }}" target="_blank" rel="noopener">Buka Google Maps &rarr;</a>
                        @endif
                    @endif
                    <a class="footer-link mt-1 block" href="{{ $portalWhatsapp }}" target="_blank" rel="noopener">WhatsApp: {{ $portalPhone }}</a>
                    <a class="footer-link block" href="mailto:{{ $portalEmail }}">{{ $portalEmail }}</a>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800">
            <div class="site-container flex flex-col gap-2 py-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ now()->year }} MI Hubbul Wathan. Hak Cipta Dilindungi.</p>
                <a class="footer-link text-xs" href="{{ route('ppdb.create') }}">Penerimaan Peserta Didik Baru (PPDB)</a>
            </div>
        </div>
    </footer>
</body>
</html>
