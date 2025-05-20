<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\CreativeEconomyController;
use App\Http\Controllers\Tourist\DashboardController;

// Basic landing pages
Route::get('/', [LandingController::class, 'home'])->name('home');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');
Route::get('/privacy-policy', [LandingController::class, 'privacyPolicy'])->name('privacy-policy');
Route::post('/contact', [LandingController::class, 'storeContactMessage'])->name('contact.store');

// Destinations
Route::get('/destinations', [LandingController::class, 'destinations'])->name('destinations.index');
Route::get('/destinations/{slug}', [LandingController::class, 'showDestination'])->name('destinations.show');

// Travel Packages
Route::get('/travel-packages', [LandingController::class, 'packages'])->name('packages.index');
Route::get('/travel-packages/{slug}', [LandingController::class, 'showPackage'])->name('packages.show');
Route::get('/travel-packages/{id}/download-guide', [LandingController::class, 'downloadTourGuide'])->name('packages.download-guide');

// Events
Route::get('/events', [LandingController::class, 'events'])->name('landing.events');
Route::get('/events/{slug}', [LandingController::class, 'showEvent'])->name('landing.events.show');
Route::post('/events/{eventId}/register', [LandingController::class, 'registerEvent'])->name('events.register');

// Accommodations
Route::get('/accommodations', [LandingController::class, 'accommodations'])->name('accommodations.index');
Route::get('/accommodations/{slug}', [LandingController::class, 'showAccommodation'])->name('accommodations.show');

// Culinary
Route::get('/culinary', [LandingController::class, 'culinaries'])->name('culinaries.index');
Route::get('/culinary/{slug}', [LandingController::class, 'showCulinary'])->name('culinaries.show');

// Cultural Heritage
Route::get('/cultural-heritages', [LandingController::class, 'culturalHeritages'])->name('cultural-heritages.index');
Route::get('/cultural-heritages/{slug}', [LandingController::class, 'showCulturalHeritage'])->name('cultural-heritages.show');

// Districts
Route::get('/districts', [LandingController::class, 'districts'])->name('districts.index');
Route::get('/districts/{slug}', [LandingController::class, 'showDistrict'])->name('districts.show');

// Global Search
Route::get('/search', [LandingController::class, 'search'])->name('search');

// Newsletter subscription
Route::post('/newsletter/subscribe', [LandingController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

// User interactions - require authentication
Route::middleware(['auth'])->group(function() {
    Route::post('/reviews', [LandingController::class, 'storeReview'])->name('reviews.store');
});

// Utility routes
Route::get('/sitemap.xml', [LandingController::class, 'sitemap'])->name('sitemap');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Tourist dashboard routes (protected)
Route::middleware(['auth', 'verified', 'tourist'])->group(function () {
    Route::prefix('tourist')->name('tourist.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [TouristController::class, 'dashboard'])
            ->name('dashboard');

        // Profile
        // Profile routes
        Route::get('/profile', [TouristController::class, 'profile'])->name('profile');
        Route::put('/profile', [TouristController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/photo', [TouristController::class, 'updateProfilePhoto'])->name('profile.update-photo');

        // Booking Routes
        Route::controller(BookingController::class)->prefix('bookings')->name('bookings.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create/{package}', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{booking}', 'show')->name('show');
            Route::get('/{booking}/payment', 'payment')->name('payment');
            Route::post('/{booking}/upload-payment', 'uploadPayment')->name('upload-payment'); // Add this
            Route::get('/{booking}/confirmation', 'confirmation')->name('confirmation');
            Route::get('/{booking}/download-ticket', 'downloadTicket')->name('download-ticket');
            Route::post('/{booking}/cancel', 'cancel')->name('cancel');
        });

        // Reviews
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/', [TouristController::class, 'reviews'])
                ->name('index');
        });

        // Wishlist
        Route::prefix('wishlist')->name('wishlist.')->group(function () {
            Route::get('/', [TouristController::class, 'wishlist'])
                ->name('index');
        });

        // Itinerary routes
        Route::prefix('itinerary')->name('itinerary.')->group(function () {
            Route::get('/', [TouristController::class, 'itinerary'])
                ->name('index');
            Route::post('/', [TouristController::class, 'storeItinerary'])
                ->name('store');
            Route::put('/{itinerary}', [TouristController::class, 'updateItinerary'])->name('update');
            Route::get('/{itinerary}', [TouristController::class, 'showItinerary'])
                ->name('show');
            Route::put('/{itinerary}', [TouristController::class, 'updateItinerary'])
                ->name('update');
            Route::delete('/{itinerary}', [TouristController::class, 'destroyItinerary'])
                ->name('destroy');
        });


    });
});
