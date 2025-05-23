<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\ItineraryItem;
use App\Models\TravelPackage;

class ItineraryController extends Controller
{
    public function index()
    {
        $itineraries = auth()->user()
            ->itineraries()
            ->with('travelPackages') // Eager load travel packages
            ->latest()
            ->paginate(10);

        // Get active travel packages for create form
        $travelPackages = TravelPackage::where('status', true)
            ->orderBy('name')
            ->get();

        return view('tourist.itinerary.index', compact('itineraries', 'travelPackages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'travel_package_ids' => 'required|array',
            'travel_package_ids.*' => 'exists:travel_packages,id'
        ]);

        // Create itinerary
        $itinerary = auth()->user()->itineraries()->create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'notes' => $validated['notes']
        ]);

        // Attach travel packages
        $itinerary->travelPackages()->attach($validated['travel_package_ids']);

        flash()->success('Rencana perjalanan berhasil dibuat!');
        return redirect()->route('tourist.itinerary.show', $itinerary);
    }

    public function storeItem(Request $request, Itinerary $itinerary)
    {
        // Authorize
        if ($itinerary->user_id !== auth()->id()) {
            flash()->error('Anda tidak memiliki akses untuk menambah item ke rencana perjalanan ini.');
            return back();
        }

        // Validate request
        $validated = $request->validate([
            'day' => 'required|integer|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'itemable_type' => 'required|string',
            'itemable_id' => 'required|integer',
            'notes' => 'nullable|string',
            'estimated_cost' => 'nullable|numeric|min:0'
        ]);

        // Get max order for the day
        $maxOrder = $itinerary->itineraryItems()
            ->where('day', $validated['day'])
            ->max('order') ?? 0;

        // Create item
        $item = $itinerary->itineraryItems()->create([
            'day' => $validated['day'],
            'order' => $maxOrder + 1,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'itemable_type' => $validated['itemable_type'],
            'itemable_id' => $validated['itemable_id'],
            'notes' => $validated['notes'],
            'estimated_cost' => $validated['estimated_cost']
        ]);

        flash()->success('Item berhasil ditambahkan ke rencana perjalanan!');
        return back();
    }

    public function show(Itinerary $itinerary)
{
    // Authorize view
    if ($itinerary->user_id !== auth()->id()) {
        flash()->error('Anda tidak memiliki akses ke rencana perjalanan ini.');
        return redirect()->route('tourist.itinerary.index');
    }

    // Get destinations for add item form
    $destinations = Destination::orderBy('name')->get();

    $itinerary->load(['itineraryItems.itemable']);

    return view('tourist.itinerary.show', compact('itinerary', 'destinations'));
}

    public function update(Request $request, Itinerary $itinerary)
    {
        // Authorize update
        if ($itinerary->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk mengubah rencana perjalanan ini.'
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string'
        ]);

        $itinerary->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Rencana perjalanan berhasil diperbarui!'
            ]);
        }

        flash()->success('Rencana perjalanan berhasil diperbarui!');
        return back();
    }



    public function destroy(Itinerary $itinerary)
    {
        // Authorize delete
        if ($itinerary->user_id !== auth()->id()) {
            flash()->error('Anda tidak memiliki akses untuk menghapus rencana perjalanan ini.');
            return back();
        }

        // Delete related items first
        $itinerary->itineraryItems()->delete();

        // Delete the itinerary
        $itinerary->delete();

        flash()->success('Rencana perjalanan berhasil dihapus!');
        return redirect()->route('tourist.itinerary.index');
    }

    public function destroyItem(Itinerary $itinerary, ItineraryItem $item)
    {
        // Authorize delete
        if ($itinerary->user_id !== auth()->id()) {
            flash()->error('Anda tidak memiliki akses untuk menghapus item dari rencana perjalanan ini.');
            return back();
        }

        // Verify item belongs to itinerary
        if ($item->itinerary_id !== $itinerary->id) {
            flash()->error('Item tidak ditemukan dalam rencana perjalanan ini.');
            return back();
        }

        $item->delete();

        flash()->success('Item berhasil dihapus dari rencana perjalanan!');
        return back();
    }
}
