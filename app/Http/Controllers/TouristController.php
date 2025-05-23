<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Booking;
use App\Models\Wishlist;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use App\Models\TravelPackage;
use Flasher\Prime\FlasherInterface;
use Illuminate\Support\Facades\Storage;

class TouristController extends Controller
{

    public function dashboard()
    {
        $user = auth()->user();

        // Update statistik sesuai dengan yang ditampilkan di view
        $stats = [
            'total_visits' => Booking::where('user_id', $user->id)
                ->where('booking_status', 'confirmed')
                ->count(),

            'total_reviews' => Review::where('user_id', $user->id)
                ->count(),

            'wishlist_count' => Wishlist::where('user_id', $user->id)
                ->count(),

            'planned_trips' => Booking::where('user_id', $user->id)
                ->where('booking_status', 'pending')
                ->where('booking_date', '>=', now())
                ->count(), // Menghitung rencana perjalanan yang akan datang
        ];

        // Get upcoming bookings
        $upcomingBookings = Booking::where('user_id', $user->id)
            ->where('booking_date', '>=', now())
            ->with('travelPackage')
            ->latest()
            ->take(5)
            ->get();

        // Get latest reviews
        $latestReviews = Review::where('user_id', $user->id)
            ->with('reviewable')
            ->latest()
            ->take(3)
            ->get();

        return view('tourist.dashboard.index', compact(
            'stats',
            'upcomingBookings',
            'latestReviews'
        ));
    }

    public function profile()
    {
        $user = auth()->user();

        // Get travel history from bookings
        $travelHistory = Booking::where('user_id', $user->id)
            ->where('booking_status', 'completed')
            ->with('travelPackage')
            ->latest()
            ->get()
            ->map(function($booking) {
                return (object)[
                    'destination_name' => $booking->travelPackage->name,
                    'visit_date' => $booking->booking_date
                ];
            });

        // Get user stats
        $stats = [
            'total_visits' => Booking::where('user_id', $user->id)
                                ->where('booking_status', 'completed')
                                ->count(),
            'total_reviews' => Review::where('user_id', $user->id)
                                ->count(),
            'total_photos' => ReviewImage::whereHas('review', function($query) use ($user) {
                                $query->where('user_id', $user->id);
                            })->count()
        ];

        return view('tourist.profile.index', compact('user', 'travelHistory', 'stats'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'profile_image.required' => 'Silakan pilih foto terlebih dahulu.',
            'profile_image.image' => 'File harus berupa gambar.',
            'profile_image.mimes' => 'Format foto harus jpeg, png, atau jpg.',
            'profile_image.max' => 'Ukuran foto maksimal 2MB.'
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile_image')) {
            // Hapus foto lama jika ada
            if ($user->profile_image) {
                Storage::delete('public/' . $user->profile_image);
            }

            // Upload foto baru
            $path = $request->file('profile_image')->store('profile-photos', 'public');
            $user->update(['profile_image' => $path]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Foto profil berhasil diperbarui!',
                    'photo_url' => $user->profile_photo_url
                ]);
            }

            flash()->success('Foto profil berhasil diperbarui!');
            return back();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupload foto.'
            ], 422);
        }

        flash()->error('Terjadi kesalahan saat mengupload foto.');
        return back();
    }


    public function reviews()
    {
        $reviews = Review::where('user_id', auth()->id())
            ->with('reviewable')
            ->latest()
            ->paginate(10);

        return view('tourist.reviews.index', compact('reviews'));
    }

    public function updateReview(Request $request, Review $review)
    {
        // Validasi kepemilikan review
        if ($review->user_id !== auth()->id()) {
            flash()->error('Anda tidak memiliki akses untuk mengubah ulasan ini.');
            return back();
        }

        // Validasi status review
        if ($review->status === 'approved') {
            flash()->warning('Ulasan yang sudah disetujui tidak dapat diubah.');
            return back();
        }

        try {
            // Validasi input
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|min:10|max:1000',
            ]);

            // Update review
            $review->update($validated);

            flash()->success('Ulasan berhasil diperbarui!');
            return back();

        } catch (\Exception $e) {
            flash()->error('Terjadi kesalahan saat memperbarui ulasan.');
            return back();
        }
    }

    public function deleteReview(Review $review)
    {
        // Validasi kepemilikan review
        if ($review->user_id !== auth()->id()) {
            flash()->error('Anda tidak memiliki akses untuk menghapus ulasan ini.');
            return back();
        }

        // Validasi status review
        if ($review->status === 'approved') {
            flash()->warning('Ulasan yang sudah disetujui tidak dapat dihapus.');
            return back();
        }

        try {
            // Hapus review
            $review->delete();

            flash()->success('Ulasan berhasil dihapus!');
            return back();

        } catch (\Exception $e) {
            flash()->error('Terjadi kesalahan saat menghapus ulasan.');
            return back();
        }
    }


    public function wishlist()
    {
        $wishlistItems = Wishlist::where('user_id', auth()->id())
            ->with('travelPackage')
            ->latest()
            ->paginate(10);

        return view('tourist.wishlist.index', compact('wishlistItems'));
    }

    public function itinerary()
    {
        $itineraries = auth()->user()
            ->itineraries()
            ->with('travelPackages')
            ->latest()
            ->paginate(10);

        // Pastikan query mengambil paket yang aktif
        $travelPackages = TravelPackage::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'duration']); // Ambil field yang diperlukan saja

        return view('tourist.itinerary.index', compact('itineraries', 'travelPackages'));
    }


    public function storeItinerary(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
            'travel_package_ids' => 'required|array',
            'travel_package_ids.*' => 'exists:travel_packages,id'
        ]);

        $itinerary = auth()->user()->itineraries()->create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'notes' => $validated['notes']
        ]);

        $itinerary->travelPackages()->attach($validated['travel_package_ids']);

        return redirect()->route('tourist.itinerary.index')
            ->with('success', 'Rencana perjalanan berhasil dibuat');
    }

}
