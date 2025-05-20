<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function verify($code)
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();

        // Check if ticket is valid
        if ($booking->booking_status !== 'confirmed' || $booking->payment_status !== 'paid') {
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket tidak valid'
            ], 400);
        }

        // Check if ticket has been used
        if ($booking->is_used) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket sudah pernah digunakan pada ' . $booking->used_at->format('d M Y H:i'),
                'used_at' => $booking->used_at
            ], 400);
        }

        // Update ticket status
        $booking->update([
            'is_used' => true,
            'used_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tiket berhasil diverifikasi',
            'data' => [
                'booking_code' => $booking->booking_code,
                'package_name' => $booking->travelPackage->name,
                'user_name' => $booking->user->name,
                'verified_at' => $booking->used_at->format('d M Y H:i')
            ]
        ]);
    }
}
