<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class GalleryItem extends Model
{
    use LogsActivity, RecordsActivity;

    protected $fillable = [
        'gallery_album_id',
        'title',
        'image',
        'caption',
        'sort_order',
        'status',
    ];

    public function album()
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }

    public function homepageImages()
    {
        return $this->hasMany(HomepageImage::class);
    }

    protected function activityLogName(): string
    {
        return 'Foto Galeri';
    }
}
