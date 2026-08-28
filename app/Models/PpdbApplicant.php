<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PpdbApplicant extends Model
{
    use LogsActivity, RecordsActivity;

    protected $fillable = [
        'registration_number',
        'academic_year',
        'student_name',
        'nik',
        'nisn',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'previous_school',
        'father_name',
        'mother_name',
        'guardian_name',
        'parent_job',
        'parent_phone',
        'status',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date'];
    }

    public function documents()
    {
        return $this->hasMany(PpdbDocument::class);
    }

    protected function activityLogName(): string
    {
        return 'Pendaftar PPDB';
    }

    protected function activityLogAttributes(): array
    {
        return ['registration_number', 'academic_year', 'student_name'];
    }
}
