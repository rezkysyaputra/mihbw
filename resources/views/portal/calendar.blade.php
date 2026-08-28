@extends('layouts.portal')

@section('content')
    <section class="site-container section-pad">
        <div class="page-header mx-auto text-center flex flex-col items-center">
            <span class="badge-accent">Agenda Madrasah</span>
            <h1 class="page-title">Kalender Akademik</h1>
            <p class="lead-text mx-auto">Jadwal kegiatan akademik dan hari penting tahun pelajaran {{ $academicYear }}.</p>
        </div>

        <div class="mt-10 mx-auto max-w-5xl grid gap-8 lg:grid-cols-[1.3fr_0.7fr]">
            <div>
                <h2 class="text-sm sm:text-base font-bold text-slate-900 border-b border-slate-200 pb-2.5">Agenda Mendatang</h2>
                @forelse($upcoming->groupBy(fn ($event) => $event->starts_at->translatedFormat('F Y')) as $month => $monthEvents)
                    <div class="mt-6">
                        <h3 class="eyebrow">{{ $month }}</h3>
                        <div class="mt-2">
                            @foreach($monthEvents as $event)
                                @include('portal.partials.event-row', ['event' => $event])
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="mt-6 text-xs text-slate-500">Belum ada agenda mendatang yang dijadwalkan.</p>
                @endforelse
            </div>

            @if($past->isNotEmpty())
                <aside class="h-fit">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 border-b border-slate-200 pb-2.5">Sudah Berlangsung</h2>
                    <div class="mt-4">
                        @foreach($past->take(8) as $event)
                            <div class="border-b border-slate-100 py-3 text-xs">
                                <p class="font-bold text-slate-800">{{ $event->title }}</p>
                                <p class="clean-list-meta mt-1">{{ $event->starts_at->translatedFormat('d F Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                </aside>
            @endif
        </div>
    </section>
@endsection
