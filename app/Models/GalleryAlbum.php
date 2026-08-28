<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryAlbum extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(GalleryItem::class);
    }
}
