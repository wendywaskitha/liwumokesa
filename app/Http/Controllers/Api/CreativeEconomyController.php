<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CreativeEconomy;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CreativeEconomyController extends Controller
{
    /**
     * Display a listing of creative economy entries with search and filters
     */
    public function index(Request $request): JsonResponse
    {
        $query = CreativeEconomy::with(['district', 'category', 'galleries']);

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('short_description', 'like', "%$search%")
                  ->orWhere('products_description', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%");
            });
        }

        // Filter by category
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Filter by district
        if ($districtId = $request->input('district_id')) {
            $query->where('district_id', $districtId);
        }

        // Filter by featured status
        if ($request->has('is_featured')) {
            $query->featured();
        }

        // Filter by workshop availability
        if ($request->has('has_workshop')) {
            $query->hasWorkshop();
        }

        // Filter by direct selling
        if ($request->has('has_direct_selling')) {
            $query->hasDirectSelling();
        }

        // Filter by verification status
        if ($request->has('is_verified')) {
            $query->where('is_verified', true);
        }

        // Price range filter
        if ($minPrice = $request->input('min_price')) {
            $query->where('price_range_start', '>=', $minPrice);
        }
        if ($maxPrice = $request->input('max_price')) {
            $query->where('price_range_end', '<=', $maxPrice);
        }

        // Sort options
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');

        $allowedSorts = ['name', 'created_at', 'establishment_year', 'price_range_start'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Only show active entries
        $query->where('status', true);

        $perPage = $request->input('per_page', 10);
        $creativeEconomies = $query->paginate($perPage);

        // Transform the response to include additional computed fields
        $creativeEconomies->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'slug' => $item->slug,
                'short_description' => $item->short_description,
                'address' => $item->address,
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'phone_number' => $item->phone_number,
                'email' => $item->email,
                'website' => $item->website,
                'price_range_text' => $item->price_range_text,
                'has_workshop' => $item->has_workshop,
                'has_direct_selling' => $item->has_direct_selling,
                'is_featured' => $item->is_featured,
                'is_verified' => $item->is_verified,
                'featured_image' => $item->featured_image,
                'average_rating' => round($item->average_rating, 1),
                'district' => $item->district ? [
                    'id' => $item->district->id,
                    'name' => $item->district->name
                ] : null,
                'category' => $item->category ? [
                    'id' => $item->category->id,
                    'name' => $item->category->name
                ] : null,
                'gallery_count' => $item->galleries->count(),
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Creative economy entries retrieved successfully',
            'data' => $creativeEconomies
        ]);
    }

    /**
     * Display the specified creative economy entry
     */
    public function show($id): JsonResponse
    {
        $creativeEconomy = CreativeEconomy::with([
            'district',
            'category',
            'destinations',
            'galleries',
            'reviews' => function($query) {
                $query->where('status', 'approved')->latest();
            },
            'products' => function($query) {
                $query->where('status', 'active');
            }
        ])->findOrFail($id);

        // Get nearby destinations
        $nearbyDestinations = $creativeEconomy->getNearbyDestinations(10);

        $response = [
            'id' => $creativeEconomy->id,
            'name' => $creativeEconomy->name,
            'slug' => $creativeEconomy->slug,
            'description' => $creativeEconomy->description,
            'short_description' => $creativeEconomy->short_description,
            'address' => $creativeEconomy->address,
            'latitude' => $creativeEconomy->latitude,
            'longitude' => $creativeEconomy->longitude,
            'phone_number' => $creativeEconomy->phone_number,
            'email' => $creativeEconomy->email,
            'website' => $creativeEconomy->website,
            'social_media' => $creativeEconomy->social_media,
            'business_hours' => $creativeEconomy->business_hours,
            'owner_name' => $creativeEconomy->owner_name,
            'establishment_year' => $creativeEconomy->establishment_year,
            'employees_count' => $creativeEconomy->employees_count,
            'products_description' => $creativeEconomy->products_description,
            'price_range_start' => $creativeEconomy->price_range_start,
            'price_range_end' => $creativeEconomy->price_range_end,
            'price_range_text' => $creativeEconomy->price_range_text,
            'has_workshop' => $creativeEconomy->has_workshop,
            'workshop_information' => $creativeEconomy->workshop_information,
            'has_direct_selling' => $creativeEconomy->has_direct_selling,
            'featured_image' => $creativeEconomy->featured_image,
            'is_featured' => $creativeEconomy->is_featured,
            'is_verified' => $creativeEconomy->is_verified,
            'accepts_credit_card' => $creativeEconomy->accepts_credit_card,
            'provides_training' => $creativeEconomy->provides_training,
            'shipping_available' => $creativeEconomy->shipping_available,
            'average_rating' => round($creativeEconomy->average_rating, 1),
            'district' => $creativeEconomy->district,
            'category' => $creativeEconomy->category,
            'destinations' => $creativeEconomy->destinations,
            'galleries' => $creativeEconomy->galleries,
            'reviews' => $creativeEconomy->reviews->map(function($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'reviewer_name' => $review->reviewer_name,
                    'created_at' => $review->created_at
                ];
            }),
            'products' => $creativeEconomy->products,
            'featured_products' => $creativeEconomy->featured_products,
            'nearby_destinations' => $nearbyDestinations->take(5),
            'created_at' => $creativeEconomy->created_at,
            'updated_at' => $creativeEconomy->updated_at
        ];

        return response()->json([
            'success' => true,
            'message' => 'Creative economy entry retrieved successfully',
            'data' => $response
        ]);
    }

    /**
     * Search creative economy entries with advanced filters
     */
    public function search(Request $request): JsonResponse
    {
        $query = CreativeEconomy::with(['district', 'category']);

        // Main search query
        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('products_description', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%");
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

        $query->where('status', true);

        $results = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Search completed successfully',
            'data' => $results
        ]);
    }

    /**
     * Get featured creative economy entries
     */
    public function featured(): JsonResponse
    {
        $featured = CreativeEconomy::with(['district', 'category', 'galleries'])
            ->featured()
            ->where('status', true)
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Featured creative economy entries retrieved successfully',
            'data' => $featured
        ]);
    }
}
