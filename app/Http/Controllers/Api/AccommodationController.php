<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AccommodationController extends Controller
{
    /**
     * Display a listing of accommodations with search and filters
     */
    public function index(Request $request): JsonResponse
    {
        $query = Accommodation::with(['district', 'galleries']);

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%")
                  ->orWhere('contact_person', 'like', "%$search%");
            });
        }

        // Filter by type
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // Filter by district
        if ($districtId = $request->input('district_id')) {
            $query->where('district_id', $districtId);
        }

        // Price range filter
        if ($minPrice = $request->input('min_price')) {
            $query->where('price_range_start', '>=', $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('price_range_end', '<=', $maxPrice);
        }

        // Filter by facilities
        if ($facilities = $request->input('facilities')) {
            $facilitiesArray = is_array($facilities) ? $facilities : explode(',', $facilities);
            foreach ($facilitiesArray as $facility) {
                $query->whereJsonContains('facilities', trim($facility));
            }
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->boolean('status'));
        } else {
            // Default: only show active accommodations
            $query->where('status', true);
        }

        // Sort options
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');

        $allowedSorts = ['name', 'created_at', 'price_range_start', 'price_range_end', 'type'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->input('per_page', 10);
        $accommodations = $query->paginate($perPage);

        // Transform the response to include additional computed fields
        $accommodations->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'type' => $item->type,
                'description' => $item->description,
                'address' => $item->address,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'district' => $item->district ? [
                    'id' => $item->district->id,
                    'name' => $item->district->name
                ] : null,
                'price_range_start' => $item->price_range_start,
                'price_range_end' => $item->price_range_end,
                'price_range_text' => $item->price_range_start && $item->price_range_end
                    ? 'Rp ' . number_format($item->price_range_start, 0, ',', '.') . ' - ' .
                      'Rp ' . number_format($item->price_range_end, 0, ',', '.')
                    : null,
                'facilities' => $item->facilities,
                'contact_person' => $item->contact_person,
                'phone_number' => $item->phone_number,
                'email' => $item->email,
                'website' => $item->website,
                'booking_link' => $item->booking_link,
                'featured_image' => $item->featured_image,
                'status' => $item->status,
                'average_rating' => round($item->average_rating, 1),
                'approved_reviews_count' => $item->approved_reviews_count,
                'gallery_count' => $item->galleries->count(),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Accommodations retrieved successfully',
            'data' => $accommodations
        ]);
    }

    /**
     * Display the specified accommodation
     */
    public function show($id): JsonResponse
    {
        $accommodation = Accommodation::with([
            'district',
            'galleries',
            'reviews' => function($query) {
                $query->where('status', 'approved')->latest();
            },
            'destinations',
            'culturalHeritages'
        ])->findOrFail($id);

        // Get nearby destinations
        $nearbyDestinations = $accommodation->nearbyDestinations(10);

        $response = [
            'id' => $accommodation->id,
            'name' => $accommodation->name,
            'slug' => $accommodation->slug,
            'type' => $accommodation->type,
            'description' => $accommodation->description,
            'address' => $accommodation->address,
            'latitude' => $accommodation->latitude,
            'longitude' => $accommodation->longitude,
            'district' => $accommodation->district,
            'price_range_start' => $accommodation->price_range_start,
            'price_range_end' => $accommodation->price_range_end,
            'price_range_text' => $accommodation->price_range_start && $accommodation->price_range_end
                ? 'Rp ' . number_format($accommodation->price_range_start, 0, ',', '.') . ' - ' .
                  'Rp ' . number_format($accommodation->price_range_end, 0, ',', '.')
                : null,
            'facilities' => $accommodation->facilities,
            'contact_person' => $accommodation->contact_person,
            'phone_number' => $accommodation->phone_number,
            'email' => $accommodation->email,
            'website' => $accommodation->website,
            'booking_link' => $accommodation->booking_link,
            'featured_image' => $accommodation->featured_image,
            'status' => $accommodation->status,
            'average_rating' => round($accommodation->average_rating, 1),
            'approved_reviews_count' => $accommodation->approved_reviews_count,
            'galleries' => $accommodation->galleries,
            'reviews' => $accommodation->reviews->map(function($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'reviewer_name' => $review->reviewer_name,
                    'created_at' => $review->created_at
                ];
            }),
            'destinations' => $accommodation->destinations,
            'cultural_heritages' => $accommodation->culturalHeritages,
            'nearby_destinations' => $nearbyDestinations->take(5),
            'created_at' => $accommodation->created_at,
            'updated_at' => $accommodation->updated_at
        ];

        return response()->json([
            'success' => true,
            'message' => 'Accommodation retrieved successfully',
            'data' => $response
        ]);
    }

    /**
     * Search accommodations with advanced filters
     */
    public function search(Request $request): JsonResponse
    {
        $query = Accommodation::with(['district']);

        // Main search query
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%")
                  ->orWhere('contact_person', 'like', "%$search%");
            });
        }

        // Location-based search
        if ($lat = $request->input('latitude') && $lng = $request->input('longitude')) {
            $radius = $request->input('radius', 10); // default 10km
            $query->selectRaw('
                *, ( 6371 * acos( cos( radians(?) ) *
                cos( radians( latitude ) ) *
                cos( radians( longitude ) - radians(?) ) +
                sin( radians(?) ) *
                sin( radians( latitude ) ) ) ) AS distance',
                [$lat, $lng, $lat]
            )
            ->having('distance', '<', $radius)
            ->orderBy('distance');
        }

        // Filter by type
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // Filter by district
        if ($districtId = $request->input('district_id')) {
            $query->where('district_id', $districtId);
        }

        // Price range filter
        if ($minPrice = $request->input('min_price')) {
            $query->where('price_range_start', '>=', $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('price_range_end', '<=', $maxPrice);
        }

        // Filter by facilities
        if ($facilities = $request->input('facilities')) {
            $facilitiesArray = is_array($facilities) ? $facilities : explode(',', $facilities);
            foreach ($facilitiesArray as $facility) {
                $query->whereJsonContains('facilities', trim($facility));
            }
        }

        $query->where('status', true);

        $results = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Search completed successfully',
            'data' => $results
        ]);
    }

    /**
     * Get accommodations by type
     */
    public function getByType($type): JsonResponse
    {
        $accommodations = Accommodation::with(['district', 'galleries'])
            ->where('type', $type)
            ->where('status', true)
            ->orderBy('name')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => "Accommodations of type '$type' retrieved successfully",
            'data' => $accommodations
        ]);
    }

    /**
     * Get accommodations by district
     */
    public function getByDistrict($districtId): JsonResponse
    {
        $accommodations = Accommodation::with(['district', 'galleries'])
            ->where('district_id', $districtId)
            ->where('status', true)
            ->orderBy('name')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Accommodations by district retrieved successfully',
            'data' => $accommodations
        ]);
    }

    /**
     * Get nearby accommodations based on coordinates
     */
    public function getNearby(Request $request): JsonResponse
    {
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        $radius = $request->input('radius', 5); // default 5km

        if (!$lat || !$lng) {
            return response()->json([
                'success' => false,
                'message' => 'Latitude and longitude are required'
            ], 400);
        }

        $accommodations = Accommodation::with(['district'])
            ->selectRaw('
                *, ( 6371 * acos( cos( radians(?) ) *
                cos( radians( latitude ) ) *
                cos( radians( longitude ) - radians(?) ) +
                sin( radians(?) ) *
                sin( radians( latitude ) ) ) ) AS distance',
                [$lat, $lng, $lat]
            )
            ->having('distance', '<', $radius)
            ->where('status', true)
            ->orderBy('distance')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Nearby accommodations retrieved successfully',
            'data' => $accommodations
        ]);
    }

    /**
     * Get accommodation types
     */
    public function getTypes(): JsonResponse
    {
        $types = Accommodation::where('status', true)
            ->distinct()
            ->pluck('type')
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'message' => 'Accommodation types retrieved successfully',
            'data' => $types
        ]);
    }

    /**
     * Get popular facilities
     */
    public function getFacilities(): JsonResponse
    {
        $accommodations = Accommodation::where('status', true)
            ->whereNotNull('facilities')
            ->get();

        $allFacilities = [];
        foreach ($accommodations as $accommodation) {
            if (is_array($accommodation->facilities)) {
                $allFacilities = array_merge($allFacilities, $accommodation->facilities);
            }
        }

        $facilityCounts = array_count_values($allFacilities);
        arsort($facilityCounts);

        $popularFacilities = array_keys(array_slice($facilityCounts, 0, 20, true));

        return response()->json([
            'success' => true,
            'message' => 'Popular facilities retrieved successfully',
            'data' => $popularFacilities
        ]);
    }
}
