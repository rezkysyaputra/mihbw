@extends('layouts.portal')

@section('content')
    @php $ppdbPhone = $portalSettings['phone'] ?? '085396590157'; @endphp

    <section class="site-container section-pad">
        <div class="mx-auto max-w-xl">
            <div class="panel overflow-hidden border border-slate-200">
                <div class="border-b border-slate-100 bg-slate-50 p-6 text-center sm:p-8">
                    <span class="mx-auto grid h-10 w-10 place-items-center rounded-full bg-brand-800 text-white">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </span>
                    <p class="badge-accent mt-4">Pendaftaran Berhasil</p>
                    <h1 class="mt-2 break-words text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $applicant->registration_number }}</h1>
                    <p class="mt-1 text-xs font-semibold text-slate-500">Tahun Ajaran {{ $applicant->academic_year }}</p>
                </div>

                <div class="p-6 sm:p-8 text-xs sm:text-sm">
                    <p class="leading-relaxed text-slate-600">Simpan atau tangkap layar (screenshot) nomor pendaftaran di atas sebagai bukti pendaftaran online.</p>

                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 border-b border-slate-100 pb-2 mt-6">Tahap Selanjutnya</h2>
                    <ol class="mt-3 grid gap-2.5 list-decimal pl-4 text-slate-600 leading-relaxed">
                        <li>Siapkan berkas fotokopi (Akta Kelahiran, KK, dan Pas Foto) dalam map.</li>
                        <li>Konfirmasi via WhatsApp ke panitia PPDB madrasah.</li>
                        <li>Lakukan verifikasi berkas fisik langsung ke kantor madrasah sesuai jadwal.</li>
                    </ol>

                    <div class="mt-6 flex flex-wrap gap-2.5 border-t border-slate-100 pt-5">
                        <a href="https://wa.me/62{{ ltrim($ppdbPhone, '0') }}?text={{ rawurlencode('Assalamualaikum, saya telah mendaftar PPDB online dengan nomor '.$applicant->registration_number) }}" target="_blank" rel="noopener" class="btn-primary text-xs">Konfirmasi via WhatsApp</a>
                        <a href="{{ route('home') }}" class="btn-secondary text-xs">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
