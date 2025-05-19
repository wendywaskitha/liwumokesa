@extends('layouts.tourist-dashboard')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <a href="{{ route('tourist.bookings.index') }}" class="mb-4 btn btn-link text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pemesanan
    </a>

    <div class="row">
        <!-- Booking Details -->
        <div class="col-lg-8">
            <div class="mb-4 card">
                <div class="bg-white card-header">
                    <h5 class="mb-0 card-title">Detail Pemesanan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4 row">
                        <div class="col-md-3">
                            <img src="{{ asset('storage/' . $booking->travelPackage->image) }}"
                                 class="rounded img-fluid"
                                 alt="{{ $booking->travelPackage->name }}">
                        </div>
                        <div class="col-md-9">
                            <h5>{{ $booking->travelPackage->name }}</h5>
                            <p class="mb-1 text-muted">
                                Kode Booking: {{ $booking->booking_code }}
                            </p>
                            <p class="mb-1">
                                <i class="bi bi-calendar me-2"></i>
                                {{ $booking->booking_date->format('d M Y') }}
                            </p>
                            <p class="mb-0">
                                <i class="bi bi-people me-2"></i>
                                {{ $booking->travelPackage->duration }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <!-- Status Timeline -->
                    <div class="mb-4 booking-timeline">
                        <div class="d-flex justify-content-between">
                            <div class="timeline-item {{ $booking->booking_status != 'cancelled' ? 'active' : '' }}">
                                <div class="timeline-point"></div>
                                <div class="timeline-content">
                                    <h6>Pemesanan</h6>
                                    <small>{{ $booking->created_at->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                            <div class="timeline-item {{ $booking->payment_status == 'paid' ? 'active' : '' }}">
                                <div class="timeline-point"></div>
                                <div class="timeline-content">
                                    <h6>Pembayaran</h6>
                                    <small>{{ $booking->payment_status == 'paid' ? $booking->updated_at->format('d M Y H:i') : '-' }}</small>
                                </div>
                            </div>
                            <div class="timeline-item {{ $booking->booking_status == 'confirmed' ? 'active' : '' }}">
                                <div class="timeline-point"></div>
                                <div class="timeline-content">
                                    <h6>Konfirmasi</h6>
                                    <small>{{ $booking->booking_status == 'confirmed' ? $booking->updated_at->format('d M Y H:i') : '-' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Details -->
                    <div class="price-details">
                        <h6>Rincian Harga</h6>
                        <div class="mb-2 d-flex justify-content-between">
                            <span>Harga Paket</span>
                            <span>Rp {{ number_format($booking->travelPackage->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span>Jumlah Peserta</span>
                            <span>1 orang</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total</strong>
                            <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="col-lg-4">
            @if($booking->payment_status == 'unpaid')
            <div class="mb-4 card">
                <div class="bg-white card-header">
                    <h5 class="mb-0 card-title">Pembayaran</h5>
                </div>
                <div class="card-body">
                    <!-- Payment Instructions -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Instruksi Pembayaran:</h6>
                        <p class="mb-0">Silakan transfer ke rekening berikut:</p>
                        <hr>
                        <p class="mb-1">Bank BCA</p>
                        <p class="mb-1">1234567890</p>
                        <p class="mb-0">a.n. PT Wisata Muna</p>
                    </div>

                    <!-- Upload Payment Proof -->
                    <form action="{{ route('tourist.bookings.upload-payment', $booking) }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Pembayaran</label>
                            <input type="file"
                                   class="form-control @error('payment_proof') is-invalid @enderror"
                                   name="payment_proof"
                                   accept="image/*">
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            Upload Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Contact Info -->
            <div class="card">
                <div class="bg-white card-header">
                    <h5 class="mb-0 card-title">Informasi Kontak</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        {{ $booking->travelPackage->contact_phone }}
                    </p>
                    <p class="mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        {{ $booking->travelPackage->contact_email }}
                    </p>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->travelPackage->contact_phone) }}"
                       class="btn btn-success w-100"
                       target="_blank">
                        <i class="bi bi-whatsapp me-2"></i>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Timeline Styling */
.booking-timeline {
    position: relative;
    padding: 20px 0;
}

.booking-timeline::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background: #e2e8f0;
    z-index: 1;
}

.timeline-item {
    position: relative;
    z-index: 2;
    text-align: center;
    background: white;
}

.timeline-point {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #e2e8f0;
    margin: 0 auto 10px;
    border: 4px solid white;
}

.timeline-item.active .timeline-point {
    background: var(--primary-color);
}
</style>
@endpush
@endsection
