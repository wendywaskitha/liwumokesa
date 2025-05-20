@extends('layouts.tourist-dashboard')

@section('title', 'Pembayaran')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <a href="{{ route('tourist.bookings.show', $booking) }}" class="mb-4 btn btn-link text-decoration-none">
        <i class="bi bi-arrow-left"></i> Kembali ke Detail Pemesanan
    </a>

    <div class="row">
        <!-- Payment Methods -->
        <div class="col-lg-8">
            <div class="border-0 shadow-sm card">
                <div class="bg-white card-header">
                    <h5 class="mb-0 card-title">Pembayaran</h5>
                </div>
                <div class="card-body">
                    @include('tourist.bookings.partials._payment-methods')
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="border-0 shadow-sm card">
                <div class="bg-white card-header">
                    <h5 class="mb-0 card-title">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    @include('tourist.bookings.partials._order-summary')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
