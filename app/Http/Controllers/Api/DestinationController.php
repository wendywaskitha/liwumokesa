<?php

namespace App\Http\Controllers\Api;

use App\Models\Destination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DestinationResource;

class DestinationController extends Controller
{
    /**
     * Display a listing of destinations
     */
    public function index(Request $request)
    {
        $query = Destination::with([
            'category',
            'district',
            'galleries',
            'reviews'
        ])->where('status', true);

        // Filter berdasarkan kategori
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter berdasarkan district
        if ($request->has('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        // Filter berdasarkan type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Search berdasarkan nama
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter featured destinations
        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $destinations = $query->paginate($request->get('per_page', 10));

        // Add wishlist status for authenticated user
        if (Auth::check()) {
            $destinations->getCollection()->transform(function ($destination) {
                $destination->is_wished = $destination->isWishedBy(Auth::user());
                return $destination;
            });
        }

        return response()->json([
            'success' => true,
            'data' => $destinations
        ]);

        return DestinationResource::collection($destinations);
    }

    /**
     * Display the specified destination
     */
    public function show($id)
    {
        $destination = Destination::with([
            'category',
            'district',
            'accommodations',
            'transportations',
            'culinaries',
            'creativeEconomies',
            'tourGuides',
            'amenities',
            'galleries',
            'reviews.user'
        ])->where('status', true)->find($id);

        if (!$destination) {
            return response()->json([
                'success' => false,
                'message' => 'Destinasi tidak ditemukan'
            ], 404);
        }

        // Add wishlist status for authenticated user
        if (Auth::check()) {
            $destination->is_wished = $destination->isWishedBy(Auth::user());
        }

        return response()->json([
            'success' => true,
            'data' => $destination
        ]);

        return new DestinationResource($destination);
    }

    /**
     * Get nearby destinations
     */
    public function nearby(Request $request, $id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json([
                'success' => false,
                'message' => 'Destinasi tidak ditemukan'
            ], 404);
        }

        // Jika ada koordinat, gunakan perhitungan jarak
        if ($destination->latitude && $destination->longitude) {
            $nearbyDestinations = Destination::select('*')
                ->selectRaw('
                    (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) + sin(radians(?)) *
                    sin(radians(latitude)))) AS distance
                ', [$destination->latitude, $destination->longitude, $destination->latitude])
                ->where('id', '!=', $id)
                ->where('status', true)
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->having('distance', '<', 50) // dalam radius 50 km
                ->orderBy('distance')
                ->with(['category', 'district', 'galleries'])
                ->limit(5)
                ->get();
        } else {
            // Fallback ke district
            $nearbyDestinations = Destination::where('id', '!=', $id)
                ->where('district_id', $destination->district_id)
                ->where('status', true)
                ->with(['category', 'district', 'galleries'])
                ->limit(5)
                ->get();
        }

        return response()->json([
            'success' => true,
            'data' => $nearbyDestinations
        ]);
    }



    /**
     * Get destination reviews
     */
    public function reviews($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json([
                'success' => false,
                'message' => 'Destinasi tidak ditemukan'
            ], 404);
        }

        $reviews = $destination->reviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    /**
     * Get destination galleries
     */
    public function galleries($id)
    {
        $destination = Destination::find($id);

        if (!$destination) {
            return response()->json([
                'success' => false,
                'message' => 'Destinasi tidak ditemukan'
            ], 404);
        }

        $galleries = $destination->galleries()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $galleries
        ]);
    }
}
