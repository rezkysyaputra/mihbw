<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePpdbApplicantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_name' => ['required', 'string', 'max:160'],
            'nik' => ['required', 'string', 'max:32'],
            'nisn' => ['nullable', 'string', 'max:32'],
            'birth_place' => ['required', 'string', 'max:120'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'address' => ['required', 'string', 'max:1000'],
            'previous_school' => ['nullable', 'string', 'max:160'],
            'father_name' => ['required', 'string', 'max:160'],
            'mother_name' => ['required', 'string', 'max:160'],
            'guardian_name' => ['nullable', 'string', 'max:160'],
            'parent_job' => ['nullable', 'string', 'max:160'],
            'parent_phone' => ['required', 'string', 'max:40'],
            'birth_certificate' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'family_card' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'photo' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'kindergarten_certificate' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
            'assistance_card' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
        ];
    }

    public function attributes(): array
    {
        return [
            'student_name' => 'nama calon siswa',
            'birth_certificate' => 'akta kelahiran',
            'family_card' => 'kartu keluarga',
            'photo' => 'pas foto',
            'kindergarten_certificate' => 'ijazah TK',
            'assistance_card' => 'KIP, KPS, atau PKH',
        ];
    }
}
