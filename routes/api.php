<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\CulinaryController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\DestinationController;
use App\Http\Controllers\Api\AccommodationController;
use App\Http\Controllers\Api\WisatawanAuthController;
use App\Http\Controllers\Api\CreativeEconomyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/api/statistics/visitors', [StatisticsController::class, 'visitors']);

Route::prefix('wisatawan')->group(function () {
    // Public routes
    Route::post('login', [WisatawanAuthController::class, 'login']);

    // Destinasi routes (public access)
    Route::prefix('destinations')->group(function () {
        Route::get('/', [DestinationController::class, 'index']);
        Route::get('/{id}', [DestinationController::class, 'show']);
        Route::get('/{id}/nearby', [DestinationController::class, 'nearby']);
        Route::get('/{id}/reviews', [DestinationController::class, 'reviews']);
        Route::get('/{id}/galleries', [DestinationController::class, 'galleries']);
    });

    // Accommodation routes (public access)
    Route::prefix('accommodations')->group(function () {
        Route::get('/', [AccommodationController::class, 'index']);
        Route::get('/search', [AccommodationController::class, 'search']);
        Route::get('/types', [AccommodationController::class, 'getTypes']);
        Route::get('/facilities', [AccommodationController::class, 'getFacilities']);
        Route::get('/nearby', [AccommodationController::class, 'getNearby']);
        Route::get('/type/{type}', [AccommodationController::class, 'getByType']);
        Route::get('/district/{districtId}', [AccommodationController::class, 'getByDistrict']);
        Route::get('/{id}', [AccommodationController::class, 'show']);
    });

    // Culinary routes (public access) - TAMBAHAN BARU
    Route::prefix('culinaries')->group(function () {
        Route::get('/', [CulinaryController::class, 'index']);
        Route::get('/search', [CulinaryController::class, 'search']);
        Route::get('/types', [CulinaryController::class, 'getTypes']);
        Route::get('/recommended', [CulinaryController::class, 'getRecommended']);
        Route::get('/nearby', [CulinaryController::class, 'getNearby']);
        Route::get('/type/{type}', [CulinaryController::class, 'getByType']);
        Route::get('/district/{districtId}', [CulinaryController::class, 'getByDistrict']);
        Route::get('/{id}', [CulinaryController::class, 'show']);
        Route::get('/{id}/nearby', [CulinaryController::class, 'getNearbyById']);
    });


    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [WisatawanAuthController::class, 'logout']);
        Route::get('profile', [WisatawanAuthController::class, 'profile']);

        // Review routes
        Route::prefix('reviews')->group(function () {
            Route::post('/', [ReviewController::class, 'store']);
            Route::get('/my-reviews', [ReviewController::class, 'getUserReviews']);
            Route::put('/{id}', [ReviewController::class, 'update']);
            Route::delete('/{id}', [ReviewController::class, 'destroy']);
        });

        // Wishlist routes
        Route::prefix('wishlist')->group(function () {
            Route::get('/', [WishlistController::class, 'index']);
            Route::post('/toggle', [WishlistController::class, 'toggle']);
            Route::get('/check', [WishlistController::class, 'check']);
            Route::get('/stats', [WishlistController::class, 'stats']);
            Route::put('/{id}', [WishlistController::class, 'update']);
            Route::delete('/{id}', [WishlistController::class, 'destroy']);
        });

        // Legacy routes for backward compatibility
        Route::post('{type}/{id}/reviews', [ReviewController::class, 'store']);
        Route::post('{type}/{id}/wishlist', [WishlistController::class, 'toggle']);

        // Protected destination routes (untuk fitur yang memerlukan login)
        Route::prefix('destinations')->group(function () {
            Route::post('/{id}/wishlist', [WishlistController::class, 'toggle']);
            Route::post('/{id}/reviews', [ReviewController::class, 'store']);
        });

        Route::prefix('culinaries')->group(function () {
            Route::post('/{id}/reviews', [ReviewController::class, 'store']);
            Route::post('/{id}/wishlist', [WishlistController::class, 'toggle']);
        });

        // Creative Economy routes
        Route::prefix('creative-economies')->group(function () {
            Route::get('/', [CreativeEconomyController::class, 'index']); // Menampilkan semua data dan pencarian
            Route::get('/{id}', [CreativeEconomyController::class, 'show']); // Menampilkan detail berdasarkan ID
        });
        Route::prefix('accommodations')->group(function () {
            Route::post('/{id}/reviews', [ReviewController::class, 'store']);
            Route::post('/{id}/wishlist', [WishlistController::class, 'toggle']);
        });
    });
});

