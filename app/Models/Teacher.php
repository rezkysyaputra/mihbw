<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Teacher extends Model
{
    use LogsActivity, RecordsActivity;

    protected $fillable = [
        'name',
        'nip',
        'slug',
        'position',
        'subject',
        'employment_status',
        'photo',
        'status',
        'sort_order',
    ];

    protected function activityLogName(): string
    {
        return 'Guru';
    }
}
