<?php

namespace App\Providers;

use App\Models\SchoolSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Di Vercel serverless, arahkan path compiled view dan storage ke /tmp
        if (isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || config('app.env') === 'production') {
            $this->app->useStoragePath('/tmp/storage');
            config([
                'view.compiled' => '/tmp/storage/framework/views',
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale(config('app.locale'));

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
