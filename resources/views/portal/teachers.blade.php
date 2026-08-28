@extends('layouts.portal')

@section('content')
    @php
        $teacherPhotoUrl = fn ($teacher): string => \App\Support\PublicImage::storageUrl(
            $teacher->photo,
            asset('images/placeholders/teacher-placeholder.svg')
        );
    @endphp

    <section class="site-container section-pad">
        <div class="page-header mx-auto text-center flex flex-col items-center">
            <span class="badge-accent">Tenaga Pendidik</span>
            <h1 class="page-title">Guru dan Tenaga Kependidikan</h1>
            <p class="lead-text mx-auto">Struktur guru dan staf MI Hubbul Wathan tahun pelajaran {{ $academicYear }}.</p>
        </div>

        @if($principal)
            <div class="mt-10 mx-auto max-w-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6">
                <div class="grid gap-6 sm:grid-cols-[8rem_1fr] sm:items-center">
                    <img src="{{ $teacherPhotoUrl($principal) }}" alt="Foto profil {{ $principal->name }}" loading="lazy" class="aspect-[3/4] w-28 sm:w-full object-cover object-top bg-white border border-slate-200 mx-auto">
                    <div class="text-center sm:text-left">
                        <p class="text-xs font-bold uppercase tracking-wider text-brand-700">{{ $principal->position }}</p>
                        <h2 class="text-xl font-bold text-slate-900 mt-1">{{ $principal->name }}</h2>
                        @if($principal->subject)
                            <p class="mt-2 text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $principal->subject }}</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-10 grid gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse($teachers as $teacher)
                <article class="person-card">
                    <img src="{{ $teacherPhotoUrl($teacher) }}" alt="Foto profil {{ $teacher->name }}" loading="lazy" class="person-photo">
                    <div class="p-4">
                        <h2 class="person-name">{{ $teacher->name }}</h2>
                        <p class="person-role">{{ $teacher->position }}</p>
                        @if($teacher->subject)
                            <p class="person-subject">{{ $teacher->subject }}</p>
                        @endif
                    </div>
                </article>
            @empty
                <p class="py-12 text-center text-xs text-slate-500 col-span-full">Data guru akan tampil setelah dilengkapi pengelola madrasah.</p>
            @endforelse
        </div>
    </section>
@endsection
