<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TicketController extends Controller
{
    public function verify($code)
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();

        // Jika tiket sudah digunakan
        if ($booking->is_used) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket sudah digunakan pada ' . $booking->used_at->format('d M Y H:i')
            ], 400);
        }

        // Jika tiket belum dikonfirmasi atau belum dibayar
        if ($booking->booking_status !== 'confirmed' || $booking->payment_status !== 'paid') {
            return response()->json([
                'status' => 'error',
                'message' => 'Tiket tidak valid'
            ], 400);
        }

        // Update status tiket
        $booking->update([
            'is_used' => true,
            'used_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tiket berhasil diverifikasi',
            'data' => [
                'booking_code' => $booking->booking_code,
                'verified_at' => $booking->used_at->format('d M Y H:i')
            ]
        ]);
    }

    public function showVerification($code)
    {
        return view('admin.verify-ticket', compact('code'));
    }
}
