<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class DownloadDocument extends Model
{
    use LogsActivity, RecordsActivity;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'status',
    ];

    protected function activityLogName(): string
    {
        return 'Dokumen Unduhan';
    }
}
