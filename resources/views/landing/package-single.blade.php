@extends('layouts.landing')

@section('title', $package->name . ' - Paket Wisata Muna Barat')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section position-relative">
        @if ($package->featured_image)
            <img src="{{ asset('storage/' . $package->featured_image) }}" alt="{{ $package->name }}" class="w-100 hero-image">
        @else
            <div class="bg-light w-100 d-flex align-items-center justify-content-center hero-image">
                <div class="text-center text-muted">
                    <i class="bi bi-image display-1"></i>
                    <p class="mt-2">Gambar belum tersedia</p>
                </div>
            </div>
        @endif

        <!-- Hero Overlay -->
        <div class="hero-overlay"></div>

        <!-- Hero Content -->
        <div class="hero-content">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <span class="mb-2 badge bg-primary">{{ $package->type_name }}</span>
                        <h1 class="mb-2 text-white display-4 fw-bold">{{ $package->name }}</h1>
                        <div class="flex-wrap gap-3 text-white d-flex">
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
        <div class="row g-4">
            <!-- Mobile Booking Card -->
            <div class="col-12 d-lg-none">
                @include('landing.partials.package-booking-card')
            </div>

            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Package Description -->
                <div class="mb-4 border-0 shadow-sm card rounded-4">
                    <div class="p-4 card-body">
                        <h2 class="mb-4 h5">Tentang Paket</h2>
                        <div class="prose">
                            {!! $package->description !!}
                        </div>
                    </div>
                </div>

                <!-- Package Itinerary -->
                @if ($package->itinerary)
                    <div class="mb-4 border-0 shadow-sm card rounded-4">
                        <div class="p-4 card-body">
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
                                            <div class="p-4 bg-light rounded-4">
                                                <h5 class="mb-3 fw-bold text-primary">
                                                    Hari {{ is_numeric($day) ? $day + 1 : $day }}
                                                </h5>
                                                <div class="ps-2">
                                                    @if (is_array($activities))
                                                        <div class="activity-item">
                                                            {{ $activities['title'] ?? $activities }}
                                                        </div>
                                                    @else
                                                        <div class="activity-item">
                                                            {{ $activities }}
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

                <!-- Package Details -->
                <div class="border-0 shadow-sm card rounded-4">
                    <div class="p-4 card-body">
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
                                                    <i class="bi bi-check text-success me-2"></i>
                                                    <span>
                                                        @if (is_array($value))
                                                            {{ $value['description'] ?? ($value['item'] ?? '') }}
                                                        @else
                                                            {{ is_string($key) && !is_numeric($key) ? $value : $key }}
                                                        @endif
                                                    </span>
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
                                                    <i class="bi bi-x text-danger me-2"></i>
                                                    <span>
                                                        @if (is_array($value))
                                                            {{ $value['description'] ?? ($value['item'] ?? '') }}
                                                        @else
                                                            {{ is_string($key) && !is_numeric($key) ? $value : $key }}
                                                        @endif
                                                    </span>
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

            <!-- Desktop Booking Card -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="sticky-top" style="top: 2rem;">
                    @include('landing.partials.package-booking-card')
                </div>
            </div>
        </div>
    </div>

    @auth
        @include('landing.partials.modals.booking-modal', ['package' => $package])
    @endauth
@endsection

@push('styles')
    <style>
        /* Hero Section */
        .hero-section {
            margin-top: -2rem;
        }

        .hero-image {
            height: 60vh;
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.7));
        }

        .hero-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 2rem 0;
        }

        /* Timeline */
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
            background: var(--bs-primary);
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px var(--bs-primary);
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: -2.35rem;
            top: 16px;
            height: calc(100% + 1rem);
            width: 2px;
            background: var(--bs-primary);
        }

        /* Card Styles */
        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-image {
                height: 50vh;
            }
        }

        /* Modal z-index fixes */
        .modal-backdrop {
            z-index: 1040 !important;
        }

        .modal {
            z-index: 1045 !important;
        }

        .modal-dialog {
            z-index: 1046 !important;
        }

        .sticky-top {
            z-index: 1020 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle modal focus management
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                modal.addEventListener('shown.bs.modal', function() {
                    // Set focus to first focusable element
                    const firstFocusable = modal.querySelector(
                        'button, [href], input, select, textarea');
                    if (firstFocusable) {
                        firstFocusable.focus();
                    }
                });

                // Prevent focus from leaving modal
                modal.addEventListener('keydown', function(e) {
                    if (e.key === 'Tab') {
                        const focusableElements = modal.querySelectorAll(
                            'button, [href], input, select, textarea');
                        const firstFocusable = focusableElements[0];
                        const lastFocusable = focusableElements[focusableElements.length - 1];

                        if (e.shiftKey) {
                            if (document.activeElement === firstFocusable) {
                                lastFocusable.focus();
                                e.preventDefault();
                            }
                        } else {
                            if (document.activeElement === lastFocusable) {
                                firstFocusable.focus();
                                e.preventDefault();
                            }
                        }
                    }
                });
            });
        });
    </script>
@endpush
