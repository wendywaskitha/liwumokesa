<?php

namespace App\Http\Controllers\Api;

use App\Models\Culinary;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class CulinaryController extends Controller
{
    /**
     * Display a listing of culinaries
     */
    public function index(Request $request)
    {
        try {
            $query = Culinary::with(['district', 'galleries', 'reviews'])
                ->where('status', true);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function (Builder $q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('type', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
                });
            }

            // Filter by type
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Filter by district
            if ($request->filled('district_id')) {
                $query->where('district_id', $request->district_id);
            }

            // Filter by price range
            if ($request->filled('min_price')) {
                $query->where('price_range_start', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('price_range_end', '<=', $request->max_price);
            }

            // Filter by features
            if ($request->filled('has_vegetarian')) {
                $query->where('has_vegetarian_option', $request->boolean('has_vegetarian'));
            }

            if ($request->filled('halal_certified')) {
                $query->where('halal_certified', $request->boolean('halal_certified'));
            }

            if ($request->filled('has_delivery')) {
                $query->where('has_delivery', $request->boolean('has_delivery'));
            }

            if ($request->filled('is_recommended')) {
                $query->where('is_recommended', $request->boolean('is_recommended'));
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');

            switch ($sortBy) {
                case 'rating':
                    $query->withCount(['reviews as average_rating' => function ($query) {
                        $query->select(\DB::raw('coalesce(avg(rating),0)'));
                    }])->orderBy('average_rating', $sortOrder);
                    break;
                case 'price':
                    $query->orderBy('price_range_start', $sortOrder);
                    break;
                case 'created_at':
                    $query->orderBy('created_at', $sortOrder);
                    break;
                default:
                    $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = min($request->get('per_page', 10), 50);
            $culinaries = $query->paginate($perPage);

            // Transform data
            $culinaries->getCollection()->transform(function ($culinary) {
                return $this->transformCulinary($culinary);
            });

            return response()->json([
                'success' => true,
                'message' => 'Culinaries retrieved successfully',
                'data' => $culinaries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve culinaries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified culinary
     */
    public function show($id)
    {
        try {
            $culinary = Culinary::with([
                'district',
                'galleries',
                'reviews' => function ($query) {
                    $query->where('status', 'approved')
                          ->with('user:id,name')
                          ->latest();
                },
                'destinations' => function ($query) {
                    $query->select('destinations.id', 'destinations.name', 'destinations.slug', 'destinations.featured_image')
                          ->where('destinations.status', true);
                }
            ])->where('status', true)->findOrFail($id);

            $transformedCulinary = $this->transformCulinary($culinary, true);

            return response()->json([
                'success' => true,
                'message' => 'Culinary retrieved successfully',
                'data' => $transformedCulinary
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Culinary not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get culinaries by type
     */
    public function getByType($type)
    {
        try {
            $culinaries = Culinary::with(['district', 'galleries'])
                ->where('type', $type)
                ->where('status', true)
                ->paginate(10);

            $culinaries->getCollection()->transform(function ($culinary) {
                return $this->transformCulinary($culinary);
            });

            return response()->json([
                'success' => true,
                'message' => "Culinaries of type '{$type}' retrieved successfully",
                'data' => $culinaries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve culinaries by type',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get culinaries by district
     */
    public function getByDistrict($districtId)
    {
        try {
            $district = District::findOrFail($districtId);

            $culinaries = Culinary::with(['district', 'galleries'])
                ->where('district_id', $districtId)
                ->where('status', true)
                ->paginate(10);

            $culinaries->getCollection()->transform(function ($culinary) {
                return $this->transformCulinary($culinary);
            });

            return response()->json([
                'success' => true,
                'message' => "Culinaries in {$district->name} retrieved successfully",
                'data' => $culinaries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve culinaries by district',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recommended culinaries
     */
    public function getRecommended(Request $request)
    {
        try {
            $perPage = min($request->get('per_page', 10), 20);

            $culinaries = Culinary::with(['district', 'galleries'])
                ->where('is_recommended', true)
                ->where('status', true)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);

            $culinaries->getCollection()->transform(function ($culinary) {
                return $this->transformCulinary($culinary);
            });

            return response()->json([
                'success' => true,
                'message' => 'Recommended culinaries retrieved successfully',
                'data' => $culinaries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve recommended culinaries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get nearby culinaries
     */
    public function getNearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'distance' => 'nullable|numeric|min:1|max:50'
        ]);

        try {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $distance = $request->get('distance', 10); // default 10km

            $culinaries = Culinary::with(['district', 'galleries'])
                ->select('*')
                ->selectRaw('
                    ( 6371 * acos( cos( radians(?) ) *
                    cos( radians( latitude ) ) *
                    cos( radians( longitude ) - radians(?) ) +
                    sin( radians(?) ) *
                    sin( radians( latitude ) ) ) ) AS distance',
                    [$latitude, $longitude, $latitude]
                )
                ->where('status', true)
                ->having('distance', '<', $distance)
                ->orderBy('distance')
                ->paginate(10);

            $culinaries->getCollection()->transform(function ($culinary) {
                return $this->transformCulinary($culinary);
            });

            return response()->json([
                'success' => true,
                'message' => 'Nearby culinaries retrieved successfully',
                'data' => $culinaries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve nearby culinaries',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get culinary types
     */
    public function getTypes()
    {
        try {
            $types = Culinary::where('status', true)
                ->distinct()
                ->pluck('type')
                ->filter()
                ->values();

            return response()->json([
                'success' => true,
                'message' => 'Culinary types retrieved successfully',
                'data' => $types
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve culinary types',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search culinaries
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
            'type' => 'nullable|string',
            'district_id' => 'nullable|exists:districts,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
        ]);

        try {
            $query = Culinary::with(['district', 'galleries'])
                ->where('status', true);

            // Search in multiple fields
            $searchTerm = $request->q;
            $query->where(function (Builder $q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('type', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%");
            });

            // Apply additional filters
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('district_id')) {
                $query->where('district_id', $request->district_id);
            }

            if ($request->filled('min_price')) {
                $query->where('price_range_start', '>=', $request->min_price);
            }

            if ($request->filled('max_price')) {
                $query->where('price_range_end', '<=', $request->max_price);
            }

            $culinaries = $query->paginate(10);

            $culinaries->getCollection()->transform(function ($culinary) {
                return $this->transformCulinary($culinary);
            });

            return response()->json([
                'success' => true,
                'message' => 'Search results retrieved successfully',
                'data' => $culinaries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Transform culinary data
     */
    private function transformCulinary($culinary, $detailed = false)
    {
        $data = [
            'id' => $culinary->id,
            'name' => $culinary->name,
            'slug' => $culinary->slug,
            'type' => $culinary->type,
            'description' => $culinary->description,
            'address' => $culinary->address,
            'latitude' => $culinary->latitude,
            'longitude' => $culinary->longitude,
            'price_range_start' => $culinary->price_range_start,
            'price_range_end' => $culinary->price_range_end,
            'price_range_text' => $culinary->price_range_text,
            'opening_hours' => $culinary->opening_hours,
            'contact_person' => $culinary->contact_person,
            'phone_number' => $culinary->phone_number,
            'featured_image' => $culinary->featured_image,
            'has_vegetarian_option' => $culinary->has_vegetarian_option,
            'halal_certified' => $culinary->halal_certified,
            'has_delivery' => $culinary->has_delivery,
            'is_recommended' => $culinary->is_recommended,
            'featured_menu' => $culinary->featured_menu,
            'average_rating' => round($culinary->average_rating, 1),
            'reviews_count' => $culinary->reviews_count,
            'district' => $culinary->district ? [
                'id' => $culinary->district->id,
                'name' => $culinary->district->name
            ] : null,
            'galleries' => $culinary->galleries->map(function ($gallery) {
                return [
                    'id' => $gallery->id,
                    'image_path' => $gallery->image_path,
                    'caption' => $gallery->caption,
                    'type' => $gallery->type ?? 'image'
                ];
            }),
            'created_at' => $culinary->created_at,
            'updated_at' => $culinary->updated_at
        ];

        if ($detailed) {
            $data['social_media'] = $culinary->social_media;
            $data['reviews'] = $culinary->reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'user_name' => $review->user->name ?? 'Anonymous',
                    'created_at' => $review->created_at,
                    'helpful_count' => $review->helpful_count ?? 0
                ];
            });
            $data['destinations'] = $culinary->destinations->map(function ($destination) {
                return [
                    'id' => $destination->id,
                    'name' => $destination->name,
                    'slug' => $destination->slug,
                    'featured_image' => $destination->featured_image,
                    'pivot' => [
                        'service_type' => $destination->pivot->service_type,
                        'is_recommended' => $destination->pivot->is_recommended,
                        'notes' => $destination->pivot->notes
                    ]
                ];
            });
        }

        // Add distance if available
        if (isset($culinary->distance)) {
            $data['distance'] = round($culinary->distance, 2);
        }

        return $data;
    }

    /**
     * Get nearby culinaries by culinary ID
     */
    public function getNearbyById($id)
    {
        try {
            $culinary = Culinary::findOrFail($id);

            if (!$culinary->latitude || !$culinary->longitude) {
                return response()->json([
                    'success' => true,
                    'message' => 'No coordinates available for nearby search',
                    'data' => []
                ]);
            }

            $distance = 10; // 10km radius

            $nearbyCulinaries = Culinary::with(['district', 'galleries'])
                ->select('*')
                ->selectRaw('
                    ( 6371 * acos( cos( radians(?) ) *
                    cos( radians( latitude ) ) *
                    cos( radians( longitude ) - radians(?) ) +
                    sin( radians(?) ) *
                    sin( radians( latitude ) ) ) ) AS distance',
                    [$culinary->latitude, $culinary->longitude, $culinary->latitude]
                )
                ->where('status', true)
                ->where('id', '!=', $id) // Exclude current culinary
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->having('distance', '<', $distance)
                ->orderBy('distance')
                ->limit(10)
                ->get();

            $transformedCulinaries = $nearbyCulinaries->map(function ($nearbyCulinary) {
                return $this->transformCulinary($nearbyCulinary);
            });

            return response()->json([
                'success' => true,
                'message' => 'Nearby culinaries retrieved successfully',
                'data' => $transformedCulinaries
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve nearby culinaries',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
