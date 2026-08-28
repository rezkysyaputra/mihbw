<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Post extends Model
{
    use LogsActivity, RecordsActivity;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'cover_image',
        'status',
        'published_at',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    protected function activityLogName(): string
    {
        return 'Berita';
    }

    protected function activityLogAttributes(): array
    {
        return ['title', 'slug', 'excerpt', 'cover_image', 'status', 'published_at'];
    }
}
