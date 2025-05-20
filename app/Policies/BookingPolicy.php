<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin can view all bookings, wisatawan can only view their own
        return $user->isAdmin() || $user->isWisatawan();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Booking $booking): bool
    {
        // Admin can view any booking, wisatawan can only view their own
        return $user->isAdmin() || ($user->isWisatawan() && $user->id === $booking->user_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only wisatawan can create bookings
        return $user->isWisatawan();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Booking $booking): bool
    {
        // Admin can update any booking
        if ($user->isAdmin()) {
            return true;
        }

        // Wisatawan can only update their own pending bookings
        return $user->isWisatawan() &&
               $user->id === $booking->user_id &&
               $booking->booking_status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Booking $booking): bool
    {
        // Only admin can delete bookings
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Booking $booking): bool
    {
        // Only admin can restore bookings
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Booking $booking): bool
    {
        // Only admin can force delete bookings
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can upload payment proof.
     */
    public function uploadPayment(User $user, Booking $booking): bool
    {
        // Only the booking owner can upload payment proof for pending bookings
        return $user->isWisatawan() &&
               $user->id === $booking->user_id &&
               $booking->booking_status === 'pending' &&
               $booking->payment_status === 'unpaid';
    }

    /**
     * Determine whether the user can confirm payment.
     */
    public function confirmPayment(User $user, Booking $booking): bool
    {
        // Only admin can confirm payments
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can cancel booking.
     */
    public function cancel(User $user, Booking $booking): bool
    {
        // Admin can cancel any booking
        if ($user->isAdmin()) {
            return true;
        }

        // Wisatawan can only cancel their own pending bookings
        return $user->isWisatawan() &&
               $user->id === $booking->user_id &&
               $booking->booking_status === 'pending';
    }
}
