<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlists()
            ->with('wishable')
            ->latest()
            ->paginate(12);

        return view('tourist.wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'wishable_type' => 'required|string',
            'wishable_id' => 'required|integer',
        ]);

        $wishlist = Wishlist::where([
            'user_id' => auth()->id(),
            'wishable_type' => $validated['wishable_type'],
            'wishable_id' => $validated['wishable_id']
        ])->first();

        if ($wishlist) {
            $wishlist->delete();
            flash()->success('Item berhasil dihapus dari wishlist!');
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'wishable_type' => $validated['wishable_type'],
                'wishable_id' => $validated['wishable_id']
            ]);
            flash()->success('Item berhasil ditambahkan ke wishlist!');
        }

        return back();
    }

    public function updateNotes(Request $request, Wishlist $wishlist)
    {
        // Validasi kepemilikan wishlist
        if ($wishlist->user_id !== auth()->id()) {
            flash()->error('Anda tidak memiliki akses untuk mengubah wishlist ini.');
            return back();
        }

        // Validasi input
        $validated = $request->validate([
            'notes' => 'nullable|string|max:255',
            'priority' => 'required|integer|min:1|max:5'
        ]);

        // Update wishlist
        $wishlist->update($validated);

        // Refresh model untuk memastikan data terbaru
        $wishlist->refresh();

        flash()->success('Wishlist berhasil diperbarui!');
        return back();
    }

}
