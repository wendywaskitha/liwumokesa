<?php

namespace App\Providers;

use App\Models\Review;
use App\Helpers\Settings;
use App\Models\Accommodation;
use App\Observers\ReviewObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Observers\AccommodationObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register a global helper
        // if (!function_exists('setting')) {
        //     function setting($key, $default = null) {
        //         return \App\Helpers\SettingsHelper::get($key, $default);
        //     }
        // }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Review::observe(ReviewObserver::class);
        Accommodation::observe(AccommodationObserver::class);

        Blade::component('tourist-layout', \App\View\Components\TouristLayout::class);
    }
}
