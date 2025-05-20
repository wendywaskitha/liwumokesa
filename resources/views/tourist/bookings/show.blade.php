@extends('layouts.tourist-dashboard')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <a href="{{ route('tourist.bookings.index') }}" class="mb-4 btn btn-link text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pemesanan
    </a>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="mb-4 card">
                <div class="bg-white card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title">Detail Pemesanan</h5>
                    <span class="badge bg-{{ $booking->booking_status === 'confirmed' ? 'success' : ($booking->booking_status === 'cancelled' ? 'danger' : 'warning') }}">
                        {{ ucfirst($booking->booking_status) }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Package Info -->
                    @include('tourist.bookings.partials._booking-details')

                    <hr>

                    <!-- Status Timeline -->
                    @include('tourist.bookings.partials._booking-timeline')

                    <!-- Order Summary -->
                    @include('tourist.bookings.partials._order-summary')
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            @if($booking->payment_status == 'unpaid')
                @include('tourist.bookings.partials._payment-methods')
            @endif

            <!-- Contact Info -->
            <div class="border-0 shadow-sm card">
                <div class="bg-white border-0 card-header">
                    <h5 class="mb-0 card-title">Informasi Kontak</h5>
                </div>
                <div class="card-body">
                    <!-- District Info -->
                    @if($booking->travelPackage->district)
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="p-2 bg-light rounded-circle">
                                        <i class="bi bi-geo-alt text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <small class="text-muted d-block">Lokasi</small>
                                    <span>{{ $booking->travelPackage->district->name }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Duration -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="p-2 bg-light rounded-circle">
                                    <i class="bi bi-clock text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <small class="text-muted d-block">Durasi</small>
                                <span>{{ $booking->travelPackage->duration }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Meeting Point -->
                    @if($booking->travelPackage->meeting_point)
                        <div class="mb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="p-2 bg-light rounded-circle">
                                        <i class="bi bi-pin-map text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <small class="text-muted d-block">Titik Kumpul</small>
                                    <span>{{ $booking->travelPackage->meeting_point }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Contact Buttons -->
                    <div class="gap-2 d-grid">
                        <a href="https://wa.me/6282346338821"
                           class="btn btn-success"
                           target="_blank">
                            <i class="bi bi-whatsapp me-2"></i>
                            Hubungi Customer Service
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    @include('tourist.bookings.partials._timeline-styles')
@endpush

@push('scripts')
    @include('tourist.bookings.partials._image-preview-script')
@endpush
@endsection
