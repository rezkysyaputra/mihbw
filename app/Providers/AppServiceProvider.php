<?php

namespace App\Providers;

use App\Models\SchoolSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));

        // Di lingkungan Vercel serverless / Production, paksa seluruh asset dan URL menggunakan HTTPS
        if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || config('app.env') === 'production' || str_contains((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        View::composer('layouts.portal', function ($view): void {
            $portalSettings = [];

            try {
                if (Schema::hasTable('school_settings')) {
                    $portalSettings = SchoolSetting::query()->pluck('value', 'key')->all();
                }
            } catch (\Throwable $e) {
                // Jangan crash jika koneksi database sedang proses inisialisasi
            }

            $view->with('portalSettings', $portalSettings);
        });
    }
}
