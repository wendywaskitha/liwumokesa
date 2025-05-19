<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Destination;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics for the authenticated user
        $stats = [
            'total_visits' => Booking::where('user_id', auth()->id())
                ->where('status', 'completed')
                ->count(),

            'total_reviews' => Review::where('user_id', auth()->id())
                ->count(),

            'wishlist_count' => Wishlist::where('user_id', auth()->id())
                ->count(),

            'planned_trips' => Itinerary::where('user_id', auth()->id())
                ->count(),
        ];

        // Get upcoming bookings
        $upcomingBookings = Booking::where('user_id', auth()->id())
            ->where('date', '>=', now())
            ->with('destination')
            ->latest()
            ->take(5)
            ->get();

        // Get latest reviews
        $latestReviews = Review::where('user_id', auth()->id())
            ->with('reviewable')
            ->latest()
            ->take(3)
            ->get();

        return view('tourist.dashboard', compact('stats', 'upcomingBookings', 'latestReviews'));
    }
}
