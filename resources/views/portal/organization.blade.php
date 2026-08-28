@extends('layouts.portal')

@section('content')
    @php
        $teacherPhoto = fn ($teacher) => \App\Support\PublicImage::storageUrl(
            $teacher?->photo,
            asset('images/placeholders/teacher-placeholder.svg')
        );
    @endphp

    <section class="site-container section-pad">
        <div class="page-header mx-auto text-center flex flex-col items-center">
            <span class="badge-accent">Struktur Organisasi</span>
            <h1 class="page-title">{{ $page->title }}</h1>
            <p class="lead-text mx-auto">{{ $page->excerpt }}</p>
        </div>

        <div class="mt-10 mx-auto max-w-5xl grid gap-8">
            @if($principal)
                <div class="mx-auto w-full max-w-xs text-center">
                    <div class="person-card">
                        <img src="{{ $teacherPhoto($principal) }}" alt="Foto {{ $principal->name }}" class="person-photo">
                        <div class="p-4">
                            <p class="person-role">{{ $principal->position }}</p>
                            <h2 class="person-name text-base mt-1">{{ $principal->name }}</h2>
                        </div>
                    </div>
                </div>
            @endif

            @if($committee->isNotEmpty())
                <div class="mx-auto grid w-full max-w-2xl gap-4 sm:grid-cols-2">
                    @foreach($committee as $teacher)
                        <div class="panel p-4 text-center">
                            <p class="person-role">{{ $teacher->position }}</p>
                            <p class="person-name mt-1">{{ $teacher->name }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($management->isNotEmpty())
                <section class="mt-2">
                    <h2 class="text-base font-bold text-slate-900 text-center sm:text-left border-b border-slate-100 pb-2">Pengelola Madrasah</h2>
                    <div class="mt-4 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($management as $teacher)
                            <div class="content-card">
                                <p class="person-role">{{ $teacher->position }}</p>
                                <h3 class="person-name mt-1">{{ $teacher->name }}</h3>
                                @if($teacher->subject)
                                    <p class="person-subject">{{ $teacher->subject }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($educators->isNotEmpty())
                <section class="mt-2">
                    <h2 class="text-base font-bold text-slate-900 text-center sm:text-left border-b border-slate-100 pb-2">Guru dan Layanan Pendukung</h2>
                    <div class="mt-4 overflow-x-auto border border-slate-200 bg-white">
                        <table class="w-full min-w-[30rem] text-left text-xs sm:text-sm">
                            <thead>
                                <tr class="border-b border-slate-100 bg-slate-50">
                                    <th scope="col" class="py-3 px-4 font-bold text-slate-800">Nama</th>
                                    <th scope="col" class="py-3 px-4 font-bold text-slate-800">Jabatan</th>
                                    <th scope="col" class="py-3 px-4 font-bold text-slate-800">Tugas / Mata Pelajaran</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($educators as $teacher)
                                    <tr class="hover:bg-slate-50/70 transition-colors">
                                        <td class="py-2.5 px-4 font-bold text-slate-900">{{ $teacher->name }}</td>
                                        <td class="py-2.5 px-4 text-slate-600">{{ $teacher->position }}</td>
                                        <td class="py-2.5 px-4 text-slate-600">{{ $teacher->subject ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 text-center sm:text-left">
                        <a href="{{ route('teachers') }}" class="link-more">Lihat profil guru lengkap &rarr;</a>
                    </div>
                </section>
            @endif
        </div>
    </section>
@endsection
