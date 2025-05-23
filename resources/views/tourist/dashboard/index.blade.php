@extends('layouts.tourist-dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="mb-4 row">
            <div class="col-12">
                <div class="text-white card bg-primary">
                    <div class="py-4 card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"
                                    class="rounded-circle" width="60">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="mb-1">Selamat datang, {{ auth()->user()->name }}!</h4>
                                <p class="mb-0">Apa rencana perjalanan Anda hari ini?</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="mb-4 row g-4">
            <!-- Active Bookings -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 rounded bg-primary bg-opacity-10">
                                    <i class="mb-0 bi bi-calendar-check text-primary h3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 text-muted">Pemesanan Aktif</h6>
                                <h4 class="mb-0">{{ $activeBookings }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Travel Plans -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 rounded bg-success bg-opacity-10">
                                    <i class="mb-0 bi bi-map text-success h3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 text-muted">Rencana Perjalanan</h6>
                                <h4 class="mb-0">{{ $itineraryCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wishlists -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 rounded bg-warning bg-opacity-10">
                                    <i class="mb-0 bi bi-heart text-warning h3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 text-muted">Wishlist</h6>
                                <h4 class="mb-0">{{ $wishlistCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="p-3 rounded bg-info bg-opacity-10">
                                    <i class="mb-0 bi bi-star text-info h3"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 text-muted">Ulasan</h6>
                                <h4 class="mb-0">{{ $reviewCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row">
            <!-- Upcoming Bookings -->
            <div class="mb-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Pemesanan Mendatang</h5>
                        <a href="{{ route('tourist.bookings.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        @forelse($upcomingBookings as $booking)
                            <div class="mb-3 d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="p-2 rounded bg-light">
                                        <i class="mb-0 bi bi-calendar-event h5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">
                                        @if ($booking->travelPackage)
                                            {{ $booking->travelPackage->name }}
                                        @else
                                            Paket tidak tersedia
                                        @endif
                                    </h6>
                                    <p class="mb-0 small text-muted">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-{{ $booking->status_color }}">
                                        {{ $booking->status_text }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="py-4 text-center">
                                <i class="bi bi-calendar-x h1 text-muted"></i>
                                <p class="mt-2 mb-0">Belum ada pemesanan mendatang</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>

            <!-- Recent Reviews -->
            <div class="mb-4 col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Ulasan Terbaru</h5>
                        <a href="{{ route('tourist.reviews.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        @forelse($recentReviews as $review)
                            <div class="mb-3 d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="p-2 rounded bg-light">
                                        <i class="mb-0 bi bi-star h5"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">
                                        @if ($review->reviewable)
                                            {{ $review->reviewable->name }}
                                        @else
                                            Item tidak tersedia
                                        @endif
                                    </h6>
                                    <div class="mb-1 text-warning small">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill{{ $i <= $review->rating ? '' : ' text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-0 small text-muted">{{ Str::limit($review->comment, 100) }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-4 text-center">
                                <i class="bi bi-star h1 text-muted"></i>
                                <p class="mt-2 mb-0">Belum ada ulasan</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
