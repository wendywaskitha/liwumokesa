<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DestinationController;
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

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [WisatawanAuthController::class, 'logout']);
        Route::get('profile', [WisatawanAuthController::class, 'profile']);

        // Protected destination routes (untuk fitur yang memerlukan login)
        Route::prefix('destinations')->group(function () {
            Route::post('/{id}/wishlist', [WishlistController::class, 'toggle']);
            Route::post('/{id}/reviews', [ReviewController::class, 'store']);
        });

        // Creative Economy routes
        Route::prefix('creative-economies')->group(function () {
            Route::get('/', [CreativeEconomyController::class, 'index']); // Menampilkan semua data dan pencarian
            Route::get('/{id}', [CreativeEconomyController::class, 'show']); // Menampilkan detail berdasarkan ID
        });
    });
});

