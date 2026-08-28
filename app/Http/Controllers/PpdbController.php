<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePpdbApplicantRequest;
use App\Models\PpdbApplicant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class PpdbController extends Controller
{
    public function create(): View
    {
        return view('portal.ppdb', [
            'seoTitle' => 'PPDB Online - MI Hubbul Wathan',
            'seoDescription' => 'Formulir pendaftaran peserta didik baru MI Hubbul Wathan secara online dengan upload dokumen dasar.',
        ]);
    }

    public function store(StorePpdbApplicantRequest $request): RedirectResponse
    {
        $key = 'ppdb-submit:'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['student_name' => 'Terlalu banyak percobaan pendaftaran. Silakan coba beberapa menit lagi.'])->withInput();
        }

        RateLimiter::hit($key, 300);

        $applicant = DB::transaction(function () use ($request) {
            $year = now()->year;
            $academicYear = $year.'/'.($year + 1);
            $sequence = PpdbApplicant::where('academic_year', $academicYear)->lockForUpdate()->count() + 1;

            $applicant = PpdbApplicant::create([
                ...$request->safe()->except(['birth_certificate', 'family_card', 'photo', 'kindergarten_certificate', 'assistance_card']),
                'academic_year' => $academicYear,
                'registration_number' => sprintf('PPDB-%s-%04d', $year, $sequence),
            ]);

            foreach ([
                'birth_certificate' => 'Akta Kelahiran',
                'family_card' => 'Kartu Keluarga',
                'photo' => 'Pas Foto',
                'kindergarten_certificate' => 'Ijazah TK',
                'assistance_card' => 'KIP/KPS/PKH',
            ] as $field => $label) {
                if (! $request->hasFile($field)) {
                    continue;
                }

                $file = $request->file($field);
                $path = $file->store('ppdb/'.$applicant->registration_number, 'local');

                $applicant->documents()->create([
                    'type' => $label,
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }

            return $applicant;
        });

        return redirect()->route('ppdb.success', $applicant->registration_number);
    }

    public function success(string $registrationNumber): View
    {
        $applicant = PpdbApplicant::where('registration_number', $registrationNumber)->firstOrFail();

        return view('portal.ppdb-success', [
            'applicant' => $applicant,
            'seoTitle' => 'Pendaftaran PPDB Berhasil - MI Hubbul Wathan',
            'seoDescription' => 'Halaman konfirmasi pendaftaran PPDB MI Hubbul Wathan.',
            'robots' => 'noindex, nofollow',
        ]);
    }
}
