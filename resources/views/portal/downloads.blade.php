@extends('layouts.portal')

@section('content')
    @php
        $fileLabel = function (?string $mime, ?string $name): string {
            $extension = str($name ?? '')->afterLast('.')->lower()->value();

            if (filled($extension) && strlen($extension) <= 4) {
                return strtoupper($extension);
            }

            return match (true) {
                str_contains((string) $mime, 'pdf') => 'PDF',
                str_contains((string) $mime, 'word') => 'DOC',
                str_contains((string) $mime, 'sheet'), str_contains((string) $mime, 'excel') => 'XLS',
                str_contains((string) $mime, 'image') => 'IMG',
                default => 'FILE',
            };
        };

        $humanSize = function (?int $bytes): ?string {
            if (! $bytes) {
                return null;
            }

            return $bytes >= 1048576
                ? round($bytes / 1048576, 1).' MB'
                : max(1, (int) round($bytes / 1024)).' KB';
        };
    @endphp

    <section class="site-container section-pad">
        <div class="page-header mx-auto text-center flex flex-col items-center">
            <span class="badge-accent">Pusat Berkas</span>
            <h1 class="page-title">Unduhan Dokumen</h1>
            <p class="lead-text mx-auto">Brosur madrasah, formulir, kalender akademik, dan surat edaran resmi.</p>
        </div>

        <div class="mt-10 mx-auto max-w-4xl grid gap-3.5">
            @forelse($documents as $document)
                @php
                    $size = $humanSize($document->file_size ? (int) $document->file_size : null);
                @endphp
                <div class="doc-row">
                    <span class="doc-icon" aria-hidden="true">{{ $fileLabel($document->mime_type, $document->original_name) }}</span>
                    <div class="min-w-0 flex-1">
                        <h2 class="card-title text-sm sm:text-base">{{ $document->title }}</h2>
                        @if($document->description)
                            <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ $document->description }}</p>
                        @endif
                        @if($size)
                            <p class="clean-list-meta mt-1">{{ $size }}</p>
                        @endif
                    </div>
                    <a class="btn-secondary shrink-0 text-xs px-3.5 py-2" href="{{ asset('storage/' . $document->file_path) }}" download>Unduh Dokumen &rarr;</a>
                </div>
            @empty
                <p class="py-12 text-center text-xs text-slate-500">Dokumen unduhan akan tampil di sini setelah diunggah pengelola madrasah.</p>
            @endforelse
        </div>
    </section>
@endsection
