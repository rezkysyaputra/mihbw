<?php

namespace App\Models;

use App\Models\Concerns\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class HomepageImage extends Model
{
    use LogsActivity, RecordsActivity;

    public const SECTION_HERO = 'hero';

    public const SECTION_ACTIVITIES = 'activities';

    public const SECTION_HIGHLIGHTS = 'highlights';

    public const SECTION_CTA = 'cta';

    protected $fillable = [
        'gallery_item_id',
        'section',
        'alt_text',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function galleryItem()
    {
        return $this->belongsTo(GalleryItem::class);
    }

    public static function sectionOptions(): array
    {
        return [
            self::SECTION_HERO => 'Carousel Utama',
            self::SECTION_ACTIVITIES => 'Visual Kegiatan',
            self::SECTION_HIGHLIGHTS => 'Galeri Singkat',
            self::SECTION_CTA => 'Gambar Ajakan PPDB',
        ];
    }

    protected function activityLogName(): string
    {
        return 'Tampilan Beranda';
    }
}
