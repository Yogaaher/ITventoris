<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Track;        // Import model Track
use App\Observers\TrackObserver; 

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
        Track::observe(TrackObserver::class);
    }
}
