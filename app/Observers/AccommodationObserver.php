<?php

namespace App\Observers;

use App\Models\Accommodation;
use Illuminate\Support\Facades\Cache;

class AccommodationObserver
{
    /**
     * Handle the Accommodation "created" event.
     */
    public function created(Accommodation $accommodation): void
    {
        // Clear any relevant cache
        Cache::forget('featured_accommodations');
        Cache::forget('accommodation_count');
    }

    /**
     * Handle the Accommodation "updated" event.
     */
    public function updated(Accommodation $accommodation): void
    {
        // Clear any relevant cache
        Cache::forget('featured_accommodations');
        Cache::forget('accommodation_count');

        if ($accommodation->isDirty('district_id')) {
            Cache::forget('accommodations_by_district');
        }
    }

    /**
     * Handle the Accommodation "deleted" event.
     */
    public function deleted(Accommodation $accommodation): void
    {
        // Clear any relevant cache
        Cache::forget('featured_accommodations');
        Cache::forget('accommodation_count');
        Cache::forget('accommodations_by_district');

        // Clean up related records if needed
    }
}
