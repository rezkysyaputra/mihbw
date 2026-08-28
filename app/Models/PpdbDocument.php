<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbDocument extends Model
{
    protected $fillable = [
        'ppdb_applicant_id',
        'type',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
    ];

    public function applicant()
    {
        return $this->belongsTo(PpdbApplicant::class, 'ppdb_applicant_id');
    }
}
