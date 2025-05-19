{{-- resources/views/tourist/dashboard/index.blade.php --}}
@extends('layouts.tourist-dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="mb-4 row g-3">
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="mb-2 card-subtitle text-muted">Total Kunjungan</h6>
                    <h2 class="mb-0 card-title">{{ $stats['total_visits'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="mb-2 card-subtitle text-muted">Ulasan</h6>
                    <h2 class="mb-0 card-title">{{ $stats['total_reviews'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="mb-2 card-subtitle text-muted">Wishlist</h6>
                    <h2 class="mb-0 card-title">{{ $stats['wishlist_count'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3">
            <div class="card stats-card">
                <div class="card-body">
                    <h6 class="mb-2 card-subtitle text-muted">Rencana Perjalanan</h6>
                    <h2 class="mb-0 card-title">{{ $stats['planned_trips'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Bookings -->
    <div class="mb-4 card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 card-title">Pemesanan yang Akan Datang</h5>
            <a href="{{ route('tourist.bookings.index') }}" class="btn btn-sm btn-primary">
                Lihat Semua
            </a>
        </div>
        <div class="card-body">
            @if($upcomingBookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Paket Wisata</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingBookings as $booking)
                                <tr>
                                    <td>{{ $booking->travelPackage->name }}</td>
                                    <td>{{ $booking->booking_date->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $booking->booking_status === 'confirmed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($booking->booking_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('tourist.bookings.show', $booking) }}"
                                           class="btn btn-sm btn-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="my-3 text-center text-muted">
                    Tidak ada pemesanan yang akan datang
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
