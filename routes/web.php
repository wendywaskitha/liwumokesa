<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\CreativeEconomyController;
use App\Http\Controllers\EconomyCreativeController;
use App\Http\Controllers\CulturalHeritageController;
use App\Http\Controllers\Tourist\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('verify-ticket/{code}', [TicketController::class, 'showVerification'])
        ->name('verify.ticket');

    Route::get('api/verify-ticket/{code}', [TicketController::class, 'verify'])
        ->name('api.verify.ticket');
});


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

// Economy Creative Routes
Route::get('economy-creative', [EconomyCreativeController::class, 'index'])
    ->name('economy-creative.index');

Route::get('economy-creative/{creativeEconomy:slug}', [EconomyCreativeController::class, 'show'])
    ->name('economy-creative.show');

// Cultural Heritage Routes
Route::prefix('warisan-budaya')->name('landing.cultural-heritage.')->group(function () {
    Route::get('/', [CulturalHeritageController::class, 'index'])->name('index');
    Route::get('/{slug}', [CulturalHeritageController::class, 'show'])->name('show');
});

// Districts
Route::get('/districts', [LandingController::class, 'districts'])->name('districts.index');
Route::get('/districts/{slug}', [LandingController::class, 'showDistrict'])->name('districts.show');

// Map Sebaran Wisata
Route::get('/sebaran-wisata', [MapController::class, 'index'])->name('landing.map');

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
        Route::put('/profile/password', [TouristController::class, 'updatePassword'])
            ->name('profile.password.update');

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
        // Reviews routes
        Route::prefix('reviews')->name('reviews.')->group(function () {
            Route::get('/', [TouristController::class, 'reviews'])->name('index');
            Route::put('/{review}', [TouristController::class, 'updateReview'])->name('update');
            Route::delete('/{review}', [TouristController::class, 'deleteReview'])->name('destroy');
        });

        // Wishlist routes
            Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
            Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
            Route::patch('/wishlist/{wishlist}/priority', [WishlistController::class, 'updatePriority'])->name('wishlist.priority');
            Route::patch('/wishlist/{wishlist}/notes', [WishlistController::class, 'updateNotes'])->name('wishlist.notes');


        // Itinerary routes
        Route::controller(ItineraryController::class)->prefix('itinerary')->name('itinerary.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::get('/{itinerary}', 'show')->name('show');
            Route::put('/{itinerary}', 'update')->name('update');
            Route::delete('/{itinerary}', 'destroy')->name('destroy');

            // Itinerary items routes
            Route::post('{itinerary}/items', [ItineraryController::class, 'storeItem'])->name('items.store');
            Route::put('{itinerary}/items/{item}', [ItineraryController::class, 'updateItem'])->name('items.update');
            Route::delete('{itinerary}/items/{item}', [ItineraryController::class, 'destroyItem'])->name('items.destroy');
        });
    });
})->withoutMiddleware(['verified']);
