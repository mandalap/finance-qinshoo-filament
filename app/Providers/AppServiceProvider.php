<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PengajuanBarang;
use App\Observers\PengajuanBarangObserver;

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
        PengajuanBarang::observe(PengajuanBarangObserver::class);
    }
}
