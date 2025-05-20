<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TravelPackage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with('travelPackage')
            ->latest()
            ->paginate(10);

        return view('tourist.bookings.index', compact('bookings'));
    }

    public function create(TravelPackage $package)
    {
        return view('tourist.bookings.create', compact('package'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'travel_package_id' => 'required|exists:travel_packages,id',
            'booking_date' => 'required|date|after:today',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500'
        ]);

        // Get package
        $package = TravelPackage::findOrFail($validated['travel_package_id']);

        // Calculate total price
        $totalPrice = $package->price * $validated['quantity'];

        // Generate booking code
        $bookingCode = 'BK' . date('Ymd') . strtoupper(Str::random(4));

        // Create booking
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'travel_package_id' => $validated['travel_package_id'],
            'booking_code' => $bookingCode,
            'booking_date' => $validated['booking_date'],
            'quantity' => $validated['quantity'],
            'total_price' => $totalPrice,
            'notes' => $validated['notes'],
            'booking_status' => 'pending',
            'payment_status' => 'unpaid'
        ]);

        return redirect()->route('tourist.bookings.payment', $booking)
            ->with('success', 'Pemesanan berhasil dibuat');
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);
        return view('tourist.bookings.show', compact('booking'));
    }

    public function payment(Booking $booking)
    {
        $this->authorize('view', $booking);
        return view('tourist.bookings.payment', compact('booking'));
    }

    public function uploadPayment(Request $request, Booking $booking)
    {
        $this->authorize('update', $booking);

        $request->validate([
            'payment_proof' => 'required|image|max:2048'
        ]);

        if ($request->hasFile('payment_proof')) {
            if ($booking->payment_proof) {
                Storage::delete('public/' . $booking->payment_proof);
            }

            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            $booking->update([
                'payment_proof' => $path,
                'payment_status' => 'pending'
            ]);

            return redirect()->route('tourist.bookings.confirmation', $booking)
                ->with('success', 'Bukti pembayaran berhasil diunggah');
        }

        return back()->with('error', 'Terjadi kesalahan saat mengunggah bukti pembayaran');
    }

    public function downloadTicket(Booking $booking)
    {
        // Authorize that the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if booking is paid and confirmed
        if ($booking->payment_status !== 'paid' || $booking->booking_status !== 'confirmed') {
            return back()->with('error', 'Tiket hanya bisa diunduh setelah pembayaran dikonfirmasi');
        }

        $data = [
            'booking' => $booking,
            'package' => $booking->travelPackage,
            'user' => auth()->user(),
            'generated_at' => Carbon::now()->format('d M Y H:i')
        ];

        $pdf = PDF::loadView('tourist.bookings.ticket', $data);

        return $pdf->download('ticket-' . $booking->booking_code . '.pdf');
    }

    public function confirmation(Booking $booking)
    {
        $this->authorize('view', $booking);
        return view('tourist.bookings.confirmation', compact('booking'));
    }
}
