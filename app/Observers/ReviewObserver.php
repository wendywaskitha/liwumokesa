<?php

namespace App\Observers;

use App\Models\Review;
use App\Notifications\ReviewStatusChanged;

class ReviewObserver
{
    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        // If the status has changed
        if ($review->isDirty('status')) {
            // Notify the user
            $review->user->notify(new ReviewStatusChanged($review));
        }
    }
}
