<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WisatawanAuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/api/statistics/visitors', [StatisticsController::class, 'visitors']);

Route::prefix('wisatawan')->group(function () {
    Route::post('login', [WisatawanAuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [WisatawanAuthController::class, 'logout']);
        Route::get('profile', [WisatawanAuthController::class, 'profile']);
    });
});

