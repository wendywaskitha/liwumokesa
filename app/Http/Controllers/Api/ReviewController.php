<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Store a newly created review
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'reviewable_id' => 'required|integer',
            'reviewable_type' => 'required|string|in:App\Models\Destination,App\Models\CreativeEconomy,App\Models\Accommodation',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Check if user already reviewed this item
            $existingReview = Review::where([
                'user_id' => Auth::id(),
                'reviewable_id' => $request->reviewable_id,
                'reviewable_type' => $request->reviewable_type
            ])->first();

            if ($existingReview) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have already reviewed this item'
                ], 409);
            }

            // Create review
            $review = Review::create([
                'user_id' => Auth::id(),
                'reviewable_id' => $request->reviewable_id,
                'reviewable_type' => $request->reviewable_type,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => 'pending' // Default status pending approval
            ]);

            // Handle image uploads
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('reviews', 'public');

                    ReviewImage::create([
                        'review_id' => $review->id,
                        'image_path' => $imagePath,
                        'original_name' => $image->getClientOriginalName()
                    ]);
                }
            }

            // Load review with relations
            $review->load(['user', 'images']);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully and is pending approval',
                'data' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'status' => $review->status,
                    'user' => [
                        'id' => $review->user->id,
                        'name' => $review->user->name
                    ],
                    'images' => $review->images->map(function($image) {
                        return [
                            'id' => $image->id,
                            'image_url' => Storage::url($image->image_path),
                            'original_name' => $image->original_name
                        ];
                    }),
                    'created_at' => $review->created_at
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews for a specific item
     */
    public function getReviews(Request $request, $reviewableType, $reviewableId): JsonResponse
    {
        $validator = Validator::make([
            'reviewable_type' => $reviewableType,
            'reviewable_id' => $reviewableId
        ], [
            'reviewable_type' => 'required|string|in:destinations,creative-economies,accommodations',
            'reviewable_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid parameters',
                'errors' => $validator->errors()
            ], 422);
        }

        // Convert route parameter to model class
        $modelMap = [
            'destinations' => 'App\Models\Destination',
            'creative-economies' => 'App\Models\CreativeEconomy',
            'accommodations' => 'App\Models\Accommodation'
        ];

        $modelClass = $modelMap[$reviewableType];

        $query = Review::with(['user', 'images', 'responses'])
            ->where('reviewable_type', $modelClass)
            ->where('reviewable_id', $reviewableId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc');

        // Filter by rating if specified
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->paginate($request->input('per_page', 10));

        // Transform response
        $reviews->getCollection()->transform(function ($review) {
            return [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'status' => $review->status,
                'user' => [
                    'id' => $review->user->id,
                    'name' => $review->user->name
                ],
                'images' => $review->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'image_url' => Storage::url($image->image_path),
                        'original_name' => $image->original_name
                    ];
                }),
                'responses' => $review->responses,
                'created_at' => $review->created_at,
                'updated_at' => $review->updated_at
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Reviews retrieved successfully',
            'data' => $reviews
        ]);
    }

    /**
     * Get user's reviews
     */
    public function getUserReviews(Request $request): JsonResponse
    {
        $reviews = Review::with(['reviewable', 'images'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 10));

        $reviews->getCollection()->transform(function ($review) {
            return [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'status' => $review->status,
                'reviewable' => [
                    'id' => $review->reviewable->id,
                    'name' => $review->reviewable->name,
                    'type' => class_basename($review->reviewable_type)
                ],
                'images' => $review->images->map(function($image) {
                    return [
                        'id' => $image->id,
                        'image_url' => Storage::url($image->image_path)
                    ];
                }),
                'created_at' => $review->created_at
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'User reviews retrieved successfully',
            'data' => $reviews
        ]);
    }

    /**
     * Update user's review
     */
    public function update(Request $request, $id): JsonResponse
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found or unauthorized'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|string|min:10|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $review->update($request->only(['rating', 'comment']));
            $review->status = 'pending'; // Reset to pending after update
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Review updated successfully',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete user's review
     */
    public function destroy($id): JsonResponse
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found or unauthorized'
            ], 404);
        }

        try {
            // Delete associated images
            foreach ($review->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
