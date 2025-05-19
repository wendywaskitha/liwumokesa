<?php

namespace App\Livewire\Tourist;

use Livewire\Component;
use App\Models\Booking;

class UpcomingBookings extends Component
{
    public $bookings;
    public $showDetailModal = false;
    public $selectedBooking = null;

    protected $listeners = [
        'bookingUpdated' => '$refresh',
        'showBookingDetail' => 'showDetail'
    ];

    public function mount()
    {
        $this->loadBookings();
    }

    public function loadBookings()
    {
        $this->bookings = auth()->user()
            ->bookings()
            ->where('date', '>=', now())
            ->with(['destination', 'payment'])
            ->latest()
            ->take(5)
            ->get();
    }

    public function showDetail($bookingId)
    {
        $this->selectedBooking = Booking::with(['destination', 'payment'])
            ->findOrFail($bookingId);
        $this->showDetailModal = true;
    }

    public function cancelBooking($bookingId)
    {
        $booking = Booking::find($bookingId);

        if ($booking && $booking->user_id === auth()->id() && $booking->canBeCancelled()) {
            $booking->update(['status' => 'cancelled']);

            $this->notification()->success(
                $title = 'Pemesanan Dibatalkan',
                $description = 'Pemesanan berhasil dibatalkan'
            );

            $this->loadBookings();
        }
    }

    public function render()
    {
        return view('livewire.tourist.upcoming-bookings');
    }
}
