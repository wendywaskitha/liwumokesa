@extends('layouts.landing')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <!-- Breadcrumb -->
            <div class="mb-4 col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('tourist.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('tourist.bookings.index') }}">Pemesanan</a>
                        </li>
                        <li class="breadcrumb-item active">Konfirmasi</li>
                    </ol>
                </nav>
            </div>

            <!-- Confirmation Card -->
            <div class="col-lg-8">
                <div class="border-0 shadow-sm card">
                    <div class="p-4 card-body">
                        @if ($booking->payment_status == 'paid')
                            <!-- Success Message -->
                            <div class="mb-4 text-center">
                                <div class="mb-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                                </div>
                                <h4>Pembayaran Berhasil!</h4>
                                <p class="text-muted">
                                    Terima kasih telah melakukan pemesanan paket wisata di Muna Barat
                                </p>
                            </div>
                        @endif

                        <!-- Booking Details -->
                        <div class="mb-4">
                            <h5 class="mb-3">Detail Pemesanan</h5>
                            <div class="p-4 rounded bg-light">
                                <div class="mb-3 row">
                                    <div class="col-md-6">
                                        <p class="mb-1 text-muted">Kode Booking</p>
                                        <p class="fw-bold">{{ $booking->booking_code }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1 text-muted">Status</p>
                                        <span
                                            class="badge bg-{{ $booking->payment_status == 'paid' ? 'success' : 'warning' }}">
                                            {{ $booking->payment_status == 'paid' ? 'Pembayaran Berhasil' : 'Menunggu Pembayaran' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <div class="col-md-6">
                                        <p class="mb-1 text-muted">Tanggal Kunjungan</p>
                                        <p class="mb-0">{{ $booking->booking_date->format('d M Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1 text-muted">Jumlah Peserta</p>
                                        <p class="mb-0">{{ $booking->quantity }} orang</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <p class="mb-1 text-muted">Total Pembayaran</p>
                                        <p class="mb-0 h5">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Package Details -->
                        <div class="mb-4">
                            <h5 class="mb-3">Detail Paket</h5>
                            <div class="p-4 rounded bg-light">
                                <h6>{{ $booking->travelPackage->name }}</h6>
                                <p class="mb-3 text-muted">{{ $booking->travelPackage->duration }}</p>

                                @if ($booking->travelPackage->inclusions)
                                    <div class="mb-3">
                                        <p class="mb-2 fw-bold">Termasuk dalam Paket:</p>
                                        <ul class="mb-0 list-unstyled">
                                            @foreach ($booking->travelPackage->inclusions as $inclusion)
                                                <li class="mb-1">
                                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                    {{ $inclusion }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="gap-2 d-flex">
                            @if ($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
                                <a href="{{ route('tourist.bookings.download-ticket', $booking) }}"
                                    class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-download me-2"></i>
                                    Download Tiket
                                </a>
                            @endif
                            <a href="{{ route('tourist.dashboard') }}" class="btn btn-outline-secondary">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
