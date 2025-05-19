@extends('layouts.landing')

@section('title', $package->name . ' - Paket Wisata Muna Barat')

@section('content')
    <!-- Hero Section -->
    <section class="position-relative">
        @if ($package->featured_image)
            <img src="{{ asset('storage/' . $package->featured_image) }}" alt="{{ $package->name }}" class="w-100"
                style="height: 60vh; object-fit: cover;">

            <div class="top-0 position-absolute start-0 w-100 h-100"
                style="background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.7));">
            </div>
        @else
            <div class="bg-light w-100 d-flex align-items-center justify-content-center" style="height: 60vh;">
                <div class="text-center text-muted">
                    <i class="bi bi-image display-1"></i>
                    <p class="mt-2">Gambar belum tersedia</p>
                </div>
            </div>
        @endif

        <!-- Content overlay -->
        <div class="bottom-0 p-4 text-white position-absolute start-0 w-100">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <span class="mb-2 badge bg-primary">{{ $package->type_name }}</span>
                        <h1 class="mb-2 display-4 fw-bold">{{ $package->name }}</h1>
                        <div class="flex-wrap gap-3 d-flex align-items-center">
                            <div>
                                <i class="bi bi-clock me-2"></i>
                                {{ $package->duration_text }}
                            </div>
                            <div>
                                <i class="bi bi-people me-2"></i>
                                {{ $package->min_participants }}-{{ $package->max_participants }} Orang
                            </div>
                            @if ($package->district)
                                <div>
                                    <i class="bi bi-geo-alt me-2"></i>
                                    {{ $package->district->name }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 col-lg-4 text-lg-end mt-lg-0">
                        <button class="btn btn-outline-light" onclick="window.history.back()">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="row">
            <!-- Sidebar - Pindahkan ke atas sebelum konten utama untuk mobile -->
            <div class="mb-4 d-lg-none col-12">
                @include('landing.partials.package-booking-card')
            </div>

            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Description -->
                <div class="mb-4 border-0 shadow-sm card rounded-3">
                    <div class="card-body">
                        <h2 class="mb-4 h5">Tentang Paket</h2>
                        <div class="prose">
                            {!! $package->description !!}
                        </div>
                    </div>
                </div>

                <!-- Itinerary -->
                @if ($package->itinerary)
                    <div class="mb-4 border-0 shadow-sm card rounded-3">
                        <div class="card-body">
                            <h2 class="mb-4 h5">Itinerary</h2>
                            <div class="timeline">
                                @php
                                    $itineraryData = is_string($package->itinerary)
                                        ? json_decode($package->itinerary, true)
                                        : $package->itinerary;
                                @endphp

                                @foreach ($itineraryData ?? [] as $day => $activities)
                                    <div class="mb-4 timeline-item">
                                        <div class="timeline-marker"></div>
                                        <div class="timeline-content">
                                            <div class="p-3 bg-light rounded-3">
                                                <h5 class="mb-3 fw-bold text-primary">
                                                    Hari {{ is_numeric($day) ? $day + 1 : $day }}
                                                </h5>
                                                <div class="ps-2">
                                                    @if (is_array($activities))
                                                        <div class="activity-item">
                                                            <div class="d-flex">
                                                                <div class="flex-grow-1">
                                                                    {{ $activities['title'] ?? $activities }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="activity-item">
                                                            <div class="d-flex">
                                                                <div class="flex-grow-1">
                                                                    {{ $activities }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Inclusions & Exclusions -->
                <div class="mb-4 border-0 shadow-sm card rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <!-- Inclusions -->
                            @if ($package->inclusions)
                                <div class="mb-4 col-md-6 mb-md-0">
                                    <h2 class="mb-4 h5 text-success">
                                        <i class="bi bi-check-circle me-2"></i>
                                        Termasuk dalam Paket
                                    </h2>
                                    <ul class="mb-0 list-unstyled">
                                        @php
                                            $inclusions = is_string($package->inclusions)
                                                ? json_decode($package->inclusions, true)
                                                : $package->inclusions;
                                        @endphp

                                        @foreach ($inclusions ?? [] as $key => $value)
                                            <li class="mb-3">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-check text-success"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        @if (is_array($value))
                                                            {{ $value['description'] ?? ($value['item'] ?? '') }}
                                                        @else
                                                            {{ is_string($key) && !is_numeric($key) ? $value : $key }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Exclusions -->
                            @if ($package->exclusions)
                                <div class="col-md-6">
                                    <h2 class="mb-4 h5 text-danger">
                                        <i class="bi bi-x-circle me-2"></i>
                                        Tidak Termasuk dalam Paket
                                    </h2>
                                    <ul class="mb-0 list-unstyled">
                                        @php
                                            $exclusions = is_string($package->exclusions)
                                                ? json_decode($package->exclusions, true)
                                                : $package->exclusions;
                                        @endphp

                                        @foreach ($exclusions ?? [] as $key => $value)
                                            <li class="mb-3">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-x text-danger"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        @if (is_array($value))
                                                            {{ $value['description'] ?? ($value['item'] ?? '') }}
                                                        @else
                                                            {{ is_string($key) && !is_numeric($key) ? $value : $key }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Desktop -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="sticky-top" style="top: 2rem;">
                    @include('landing.partials.package-booking-card')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 3rem;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-marker {
            position: absolute;
            left: -3rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #0d6efd;
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px #0d6efd;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: -2.35rem;
            top: 16px;
            height: calc(100% + 1rem);
            width: 2px;
            background: #0d6efd;
        }
    </style>
@endpush
