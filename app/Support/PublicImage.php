<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class PublicImage
{
    public static function storageUrl(?string $path, ?string $fallback = null): ?string
    {
        if (blank($path)) {
            return $fallback;
        }

        if (str($path)->startsWith(['http://', 'https://', '//'])) {
            return $path;
        }

        $disk = config('filesystems.default') === 's3' ? 's3' : 'public';

        try {
            if (! Storage::disk($disk)->exists($path)) {
                return $fallback;
            }

            return Storage::disk($disk)->url($path);
        } catch (\Throwable $e) {
            return $fallback;
        }
    }
}
