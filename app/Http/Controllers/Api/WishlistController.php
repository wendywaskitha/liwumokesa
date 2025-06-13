<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    /**
     * Get user's wishlist
     */
    public function index(Request $request): JsonResponse
    {
        $query = Wishlist::with(['wishable'])
            ->where('user_id', Auth::id());

        // Filter by type if specified
        if ($request->has('type')) {
            $typeMap = [
                'destinations' => 'App\Models\Destination',
                'creative-economies' => 'App\Models\CreativeEconomy',
                'accommodations' => 'App\Models\Accommodation'
            ];

            if (isset($typeMap[$request->type])) {
                $query->where('wishable_type', $typeMap[$request->type]);
            }
        }

        // Sort by priority or created date
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        if ($sortBy === 'priority') {
            $query->orderBy('priority', $sortOrder)->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        $wishlists = $query->paginate($request->input('per_page', 10));

        // Transform response
        $wishlists->getCollection()->transform(function ($wishlist) {
            return [
                'id' => $wishlist->id,
                'notes' => $wishlist->notes,
                'priority' => $wishlist->priority,
                'wishable' => [
                    'id' => $wishlist->wishable->id,
                    'name' => $wishlist->wishable->name,
                    'type' => class_basename($wishlist->wishable_type),
                    'featured_image' => $wishlist->wishable->featured_image ?? null,
                    'address' => $wishlist->wishable->address ?? null,
                    'average_rating' => method_exists($wishlist->wishable, 'getAverageRatingAttribute')
                        ? $wishlist->wishable->average_rating
                        : null
                ],
                'created_at' => $wishlist->created_at,
                'updated_at' => $wishlist->updated_at
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Wishlist retrieved successfully',
            'data' => $wishlists
        ]);
    }

    /**
     * Toggle item in wishlist (add/remove)
     */
    public function toggle(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'wishable_id' => 'required|integer',
            'wishable_type' => 'required|string|in:destinations,creative-economies,accommodations',
            'notes' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Convert route parameter to model class
        $modelMap = [
            'destinations' => 'App\Models\Destination',
            'creative-economies' => 'App\Models\CreativeEconomy',
            'accommodations' => 'App\Models\Accommodation'
        ];

        $modelClass = $modelMap[$request->wishable_type];

        try {
            // Check if item exists in wishlist
            $existingWishlist = Wishlist::where([
                'user_id' => Auth::id(),
                'wishable_id' => $request->wishable_id,
                'wishable_type' => $modelClass
            ])->first();

            if ($existingWishlist) {
                // Remove from wishlist
                $existingWishlist->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed from wishlist',
                    'action' => 'removed',
                    'in_wishlist' => false
                ]);
            } else {
                // Add to wishlist
                $wishlist = Wishlist::create([
                    'user_id' => Auth::id(),
                    'wishable_id' => $request->wishable_id,
                    'wishable_type' => $modelClass,
                    'notes' => $request->notes,
                    'priority' => $request->priority ?? 3
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Item added to wishlist',
                    'action' => 'added',
                    'in_wishlist' => true,
                    'data' => [
                        'id' => $wishlist->id,
                        'notes' => $wishlist->notes,
                        'priority' => $wishlist->priority,
                        'created_at' => $wishlist->created_at
                    ]
                ], 201);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle wishlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if item is in user's wishlist
     */
    public function check(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'wishable_id' => 'required|integer',
            'wishable_type' => 'required|string|in:destinations,creative-economies,accommodations'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $modelMap = [
            'destinations' => 'App\Models\Destination',
            'creative-economies' => 'App\Models\CreativeEconomy',
            'accommodations' => 'App\Models\Accommodation'
        ];

        $modelClass = $modelMap[$request->wishable_type];

        $inWishlist = Wishlist::where([
            'user_id' => Auth::id(),
            'wishable_id' => $request->wishable_id,
            'wishable_type' => $modelClass
        ])->exists();

        return response()->json([
            'success' => true,
            'message' => 'Wishlist status checked',
            'in_wishlist' => $inWishlist
        ]);
    }

    /**
     * Update wishlist item notes or priority
     */
    public function update(Request $request, $id): JsonResponse
    {
        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist item not found or unauthorized'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'notes' => 'nullable|string|max:255',
            'priority' => 'nullable|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $wishlist->update($request->only(['notes', 'priority']));

            return response()->json([
                'success' => true,
                'message' => 'Wishlist item updated successfully',
                'data' => $wishlist
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update wishlist item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove specific item from wishlist
     */
    public function destroy($id): JsonResponse
    {
        $wishlist = Wishlist::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$wishlist) {
            return response()->json([
                'success' => false,
                'message' => 'Wishlist item not found or unauthorized'
            ], 404);
        }

        try {
            $wishlist->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from wishlist'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from wishlist',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get wishlist statistics
     */
    public function stats(): JsonResponse
    {
        $userId = Auth::id();

        $stats = [
            'total_items' => Wishlist::where('user_id', $userId)->count(),
            'by_type' => [
                'destinations' => Wishlist::where('user_id', $userId)
                    ->where('wishable_type', 'App\Models\Destination')->count(),
                'creative_economies' => Wishlist::where('user_id', $userId)
                    ->where('wishable_type', 'App\Models\CreativeEconomy')->count(),
                'accommodations' => Wishlist::where('user_id', $userId)
                    ->where('wishable_type', 'App\Models\Accommodation')->count(),
            ],
            'by_priority' => Wishlist::where('user_id', $userId)
                ->selectRaw('priority, COUNT(*) as count')
                ->groupBy('priority')
                ->pluck('count', 'priority')
                ->toArray()
        ];

        return response()->json([
            'success' => true,
            'message' => 'Wishlist statistics retrieved successfully',
            'data' => $stats
        ]);
    }
}
