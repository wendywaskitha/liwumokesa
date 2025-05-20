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
                        <div class="gap-2 d-flex align-items-center">
                            @if ($booking->booking_status === 'confirmed' && $booking->payment_status === 'paid')
                                @if (!$booking->is_used)
                                    <a href="{{ route('tourist.bookings.download-ticket', $booking) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="bi bi-download me-2"></i>
                                        Download E-Ticket
                                    </a>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Tiket Sudah Digunakan
                                    </span>
                                @endif
                            @endif
                            <span
                                class="badge bg-{{ $booking->booking_status === 'confirmed' ? 'success' : ($booking->booking_status === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($booking->booking_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Package Info -->
                        @include('tourist.bookings.partials._booking-details')

                        <hr>

                        @if ($booking->is_used)
                            <div class="mb-4 alert alert-secondary">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill fs-4 me-2"></i>
                                    <div>
                                        <h6 class="mb-1 alert-heading">Tiket Telah Digunakan</h6>
                                        <p class="mb-0">Tiket ini telah diverifikasi pada
                                            {{ $booking->used_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Status Timeline -->
                        @include('tourist.bookings.partials._booking-timeline')

                        <!-- Order Summary -->
                        @include('tourist.bookings.partials._order-summary')

                        <!-- Tambahkan setelah Order Summary -->
                        @if ($booking->booking_status === 'confirmed' && $booking->payment_status === 'paid')
                            <div class="mt-4 card">
                                <div class="card-body">
                                    <div class="text-center">
                                        @if (!$booking->is_used)
                                            <h5 class="mb-4 card-title">E-Ticket QR Code</h5>
                                            <div class="mb-3 d-flex justify-content-center">
                                                <div class="p-3 bg-white rounded shadow-sm">
                                                    {!! DNS2D::getBarcodeHTML($booking->booking_code, 'QRCODE', 5, 5) !!}
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <p class="mb-1 text-muted small">Tunjukkan QR Code ini saat check-in</p>
                                                <p class="mb-0"><strong>{{ $booking->booking_code }}</strong></p>
                                            </div>
                                            <div class="alert alert-info" role="alert">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Pastikan untuk menyimpan e-ticket ini. QR code hanya bisa digunakan satu
                                                kali.
                                            </div>
                                            <div class="gap-2 d-grid d-md-flex justify-content-md-center">
                                                <a href="{{ route('tourist.bookings.download-ticket', $booking) }}"
                                                    class="btn btn-primary">
                                                    <i class="bi bi-download me-2"></i>
                                                    Download E-Ticket
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-center">
                                                <div class="mb-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                                                    style="width: 64px; height: 64px;">
                                                    <i class="bi bi-check-circle text-success fs-1"></i>
                                                </div>
                                                <h5>Tiket Sudah Digunakan</h5>
                                                <p class="mb-0 text-muted">
                                                    Tiket ini telah diverifikasi pada<br>
                                                    {{ $booking->used_at->format('d M Y H:i') }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                @if ($booking->payment_status == 'unpaid')
                    @include('tourist.bookings.partials._payment-methods')
                @endif

                <!-- Contact Info -->
                <div class="border-0 shadow-sm card">
                    <div class="bg-white border-0 card-header">
                        <h5 class="mb-0 card-title">Informasi Kontak</h5>
                    </div>
                    <div class="card-body">
                        <!-- District Info -->
                        @if ($booking->travelPackage->district)
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
                        @if ($booking->travelPackage->meeting_point)
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
                            <a href="https://wa.me/6282346338821" class="btn btn-success" target="_blank">
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

        <style>
            /* QR Code styling */
            .qrcode {
                display: inline-block;
                padding: 15px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .qrcode svg {
                display: block;
                width: 150px !important;
                height: 150px !important;
            }
        </style>
    @endpush

    @push('scripts')
        @include('tourist.bookings.partials._image-preview-script')
    @endpush


@endsection
