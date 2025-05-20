@extends('layouts.landing')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('tourist.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('tourist.bookings.index') }}">Pemesanan</a>
                    </li>
                    <li class="breadcrumb-item active">Buat Pemesanan</li>
                </ol>
            </nav>

            <!-- Booking Form Card -->
            <div class="border-0 shadow-sm card">
                <div class="p-4 card-body">
                    <h4 class="mb-4 card-title">Form Pemesanan</h4>
                    @include('tourist.bookings.partials._booking-form')
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            @include('tourist.bookings.partials._order-summary')
        </div>
    </div>
</div>
@endsection
