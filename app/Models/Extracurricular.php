<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'coach',
        'schedule',
        'image',
        'images',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
        ];
    }
}
