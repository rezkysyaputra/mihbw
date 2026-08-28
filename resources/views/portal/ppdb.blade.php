@extends('layouts.portal')

@section('content')
    @php $ppdbPhone = $portalSettings['phone'] ?? '085396590157'; @endphp

    <section class="site-container section-pad">
        <div class="page-header mx-auto text-center flex flex-col items-center">
            <span class="badge-accent">Penerimaan Siswa Baru</span>
            <h1 class="page-title">Formulir PPDB Online</h1>
            <p class="lead-text mx-auto">Silakan lengkapi formulir pendaftaran calon siswa dengan data yang valid dan benar.</p>
        </div>

        @if($errors->any())
            <div class="mt-6 mx-auto max-w-5xl border border-red-200 bg-red-50 p-4 text-xs sm:text-sm text-red-800" role="alert">
                <p class="font-bold">Mohon periksa kembali isian dan berkas pendaftaran:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach($errors->unique() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mt-8 mx-auto max-w-5xl grid gap-8 lg:grid-cols-[1.6fr_1fr] lg:gap-10">
            <form method="POST" action="{{ route('ppdb.store') }}" enctype="multipart/form-data" class="grid gap-5">
                @csrf

                <fieldset class="step-panel">
                    <legend class="step-legend">
                        <span class="step-number">1</span>
                        <span class="card-title text-sm sm:text-base">Data Calon Siswa</span>
                    </legend>
                    <div class="mt-3 grid gap-3.5 sm:grid-cols-2">
                        @include('portal.partials.input', ['name' => 'student_name', 'label' => 'Nama Calon Siswa', 'required' => true])
                        @include('portal.partials.input', ['name' => 'nik', 'label' => 'NIK', 'required' => true, 'hint' => '16 digit sesuai kartu keluarga'])
                        @include('portal.partials.input', ['name' => 'nisn', 'label' => 'NISN', 'hint' => 'Kosongkan bila belum ada'])
                        @include('portal.partials.input', ['name' => 'birth_place', 'label' => 'Tempat Lahir', 'required' => true])
                        @include('portal.partials.input', ['name' => 'birth_date', 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => true])
                        <label class="form-label">
                            <span>Jenis Kelamin <span class="text-red-600" aria-hidden="true">*</span></span>
                            <select name="gender" class="form-control" required>
                                <option value="">Pilih</option>
                                <option @selected(old('gender') === 'Laki-laki')>Laki-laki</option>
                                <option @selected(old('gender') === 'Perempuan')>Perempuan</option>
                            </select>
                            @error('gender')<span class="field-error">{{ $message }}</span>@enderror
                        </label>
                    </div>
                    <div class="mt-3.5">
                        @include('portal.partials.textarea', ['name' => 'address', 'label' => 'Alamat Tempat Tinggal', 'required' => true])
                    </div>
                </fieldset>

                <fieldset class="step-panel">
                    <legend class="step-legend">
                        <span class="step-number">2</span>
                        <span class="card-title text-sm sm:text-base">Data Orang Tua / Wali</span>
                    </legend>
                    <div class="mt-3 grid gap-3.5 sm:grid-cols-2">
                        @include('portal.partials.input', ['name' => 'father_name', 'label' => 'Nama Ayah', 'required' => true])
                        @include('portal.partials.input', ['name' => 'mother_name', 'label' => 'Nama Ibu', 'required' => true])
                        @include('portal.partials.input', ['name' => 'parent_phone', 'label' => 'Nomor WhatsApp Orang Tua/Wali', 'required' => true, 'hint' => 'Untuk konfirmasi & info panitia'])
                        @include('portal.partials.input', ['name' => 'parent_job', 'label' => 'Pekerjaan Orang Tua/Wali', 'hint' => 'Opsional'])
                        @include('portal.partials.input', ['name' => 'guardian_name', 'label' => 'Nama Wali', 'hint' => 'Opsional (bila diasuh wali)'])
                        @include('portal.partials.input', ['name' => 'previous_school', 'label' => 'Asal TK/RA', 'hint' => 'Opsional'])
                    </div>
                </fieldset>

                <fieldset class="step-panel">
                    <legend class="step-legend">
                        <span class="step-number">3</span>
                        <span class="card-title text-sm sm:text-base">Unggah Berkas</span>
                    </legend>
                    <p class="mt-1.5 text-xs text-slate-500">Format yang diterima: JPG, PNG, WebP, dan PDF.</p>
                    <div class="mt-3.5 grid gap-3.5 sm:grid-cols-2">
                        @include('portal.partials.input', ['name' => 'birth_certificate', 'label' => 'Akta Kelahiran', 'type' => 'file', 'required' => true])
                        @include('portal.partials.input', ['name' => 'family_card', 'label' => 'Kartu Keluarga', 'type' => 'file', 'required' => true])
                        @include('portal.partials.input', ['name' => 'photo', 'label' => 'Pas Foto Calon Siswa', 'type' => 'file', 'required' => true])
                        @include('portal.partials.input', ['name' => 'kindergarten_certificate', 'label' => 'Ijazah TK/RA', 'type' => 'file', 'hint' => 'Opsional'])
                        @include('portal.partials.input', ['name' => 'assistance_card', 'label' => 'KIP/KPS/PKH', 'type' => 'file', 'hint' => 'Opsional'])
                    </div>
                </fieldset>

                <div class="pt-1">
                    <button class="btn-primary w-full sm:w-auto">Kirim Formulir Pendaftaran &rarr;</button>
                </div>
            </form>

            <aside class="h-fit">
                <div class="panel p-5 sm:p-6">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800 border-b border-slate-100 pb-2.5">Panduan Berkas</h2>
                    <ul class="mt-3.5 grid gap-2.5 text-xs text-slate-600 leading-relaxed">
                        <li class="flex gap-2"><span class="mt-1.5 h-1 w-1 shrink-0 bg-brand-700"></span>Foto / scan dokumen harus jelas dan dapat dibaca panitia.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1 w-1 shrink-0 bg-brand-700"></span>Pastikan nomor WhatsApp aktif untuk menerima kabar verifikasi.</li>
                        <li class="flex gap-2"><span class="mt-1.5 h-1 w-1 shrink-0 bg-brand-700"></span>Bawa dokumen asli dan fotokopi ke madrasah saat tahap verifikasi fisik.</li>
                    </ul>
                    <div class="mt-5 border-t border-slate-100 pt-4">
                        <p class="text-xs text-slate-500 font-medium">Butuh bantuan pendaftaran?</p>
                        <a href="https://wa.me/62{{ ltrim($ppdbPhone, '0') }}" target="_blank" rel="noopener" class="btn-secondary mt-2.5 w-full text-center text-xs">Hubungi Panitia PPDB</a>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection
