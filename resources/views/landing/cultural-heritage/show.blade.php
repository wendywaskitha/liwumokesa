@extends('layouts.landing')

@section('title', $heritage->name . ' - Warisan Budaya Muna Barat')

@section('content')
    <!-- Hero Section -->
    <section class="position-relative">
        @if ($heritage->featured_image)
            <img src="{{ Storage::url($heritage->featured_image) }}" alt="{{ $heritage->name }}" class="w-100"
                style="height: 60vh; object-fit: cover;">

            <!-- Overlay gradient -->
            <div class="top-0 position-absolute start-0 w-100 h-100"
                style="background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.7));">
            </div>
        @else
            <!-- Fallback image -->
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
                        <span class="mb-2 badge bg-primary">{{ $heritage->type }}</span>
                        <h1 class="mb-2 display-4 fw-bold">{{ $heritage->name }}</h1>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>{{ $heritage->district->name ?? $heritage->location }}</span>
                        </div>
                    </div>
                    <div class="mt-3 col-lg-4 text-lg-end mt-lg-0">
                        @if ($heritage->reviews->count() > 0)
                            <div class="mb-2 d-flex align-items-center justify-content-lg-end">
                                <div class="me-2">
                                    <div class="d-flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= round($heritage->reviews->avg('rating')))
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <span>
                                    {{ number_format($heritage->reviews->avg('rating'), 1) }}
                                    ({{ $heritage->reviews->count() }} ulasan)
                                </span>
                            </div>
                        @endif
                        <button class="btn btn-outline-light" onclick="window.history.back()">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Description -->
                    <div class="mb-4 border-0 shadow-sm card rounded-3">
                        <div class="card-body">
                            <h2 class="mb-4 h5">Tentang Warisan Budaya</h2>
                            <div class="prose">
                                {!! $heritage->description !!}
                            </div>

                            @if ($heritage->historical_significance)
                                <div class="mt-4">
                                    <h3 class="h6">Signifikansi Sejarah</h3>
                                    <p>{!! $heritage->historical_significance !!}</p>
                                </div>
                            @endif

                            @if ($heritage->practices_description)
                                <div class="mt-4">
                                    <h3 class="h6">Praktik dan Tradisi</h3>
                                    <p>{!! $heritage->practices_description !!}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Gallery Section -->
                    @if ($heritage->galleries->isNotEmpty())
                        <div class="mb-4 border-0 shadow-sm card rounded-3">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Galeri Foto</h2>
                                <div class="row g-3">
                                    @foreach ($heritage->galleries as $gallery)
                                        <div class="col-md-4">
                                            <a href="{{ Storage::url($gallery->file_path) }}" data-fslightbox="gallery"
                                                class="d-block gallery-item">
                                                <div class="position-relative">
                                                    <img src="{{ Storage::url($gallery->file_path) }}"
                                                        alt="{{ $gallery->caption ?? $heritage->name }}"
                                                        class="rounded img-fluid w-100"
                                                        style="height: 200px; object-fit: cover;">
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Related Sections -->
                    <div class="row">
                        <!-- Accommodation Column -->
                        <div class="col-md-6">
                            @if (isset($recommendedAccommodations) && $recommendedAccommodations->isNotEmpty())
                                <div class="mb-4 border-0 shadow-sm card rounded-3">
                                    <div class="card-body">
                                        <h2 class="mb-4 h5">Akomodasi Terdekat</h2>
                                        @foreach ($recommendedAccommodations as $accommodation)
                                            <div class="pb-3 mb-3 border-bottom last:border-0">
                                                <div class="row g-2">
                                                    <div class="col-auto">
                                                        @if ($accommodation->featured_image)
                                                            <img src="{{ Storage::url($accommodation->featured_image) }}"
                                                                alt="{{ $accommodation->name }}" class="rounded"
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                        @endif
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="mb-1">{{ $accommodation->name }}</h6>
                                                        <div class="mb-2 small text-muted">
                                                            <i
                                                                class="bi bi-geo-alt me-1"></i>{{ $accommodation->district->name }}
                                                        </div>
                                                        <div class="mb-2 d-flex align-items-center">
                                                            <span
                                                                class="badge bg-primary me-2">{{ $accommodation->type }}</span>
                                                            <span class="small text-muted">
                                                                Mulai Rp
                                                                {{ number_format($accommodation->price_range_start) }}
                                                            </span>
                                                        </div>
                                                        @if ($accommodation->contact_person || $accommodation->phone_number || $accommodation->email)
                                                            <div class="small">
                                                                @if ($accommodation->contact_person)
                                                                    <div class="mb-1">
                                                                        <i
                                                                            class="bi bi-person me-1"></i>{{ $accommodation->contact_person }}
                                                                    </div>
                                                                @endif
                                                                @if ($accommodation->phone_number)
                                                                    <div class="mb-1">
                                                                        <i class="bi bi-telephone me-1"></i>
                                                                        <a href="tel:{{ $accommodation->phone_number }}"
                                                                            class="text-decoration-none">
                                                                            {{ $accommodation->phone_number }}
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                                @if ($accommodation->email)
                                                                    <div>
                                                                        <i class="bi bi-envelope me-1"></i>
                                                                        <a href="mailto:{{ $accommodation->email }}"
                                                                            class="text-decoration-none">
                                                                            {{ $accommodation->email }}
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Culinary Column -->
                        <div class="col-md-6">
                            @if (isset($recommendedCulinaries) && $recommendedCulinaries->isNotEmpty())
                                <div class="mb-4 border-0 shadow-sm card rounded-3">
                                    <div class="card-body">
                                        <h2 class="mb-4 h5">Kuliner Terdekat</h2>
                                        @foreach ($recommendedCulinaries as $culinary)
                                            <div class="pb-3 mb-3 border-bottom last:border-0">
                                                <div class="row g-2">
                                                    <div class="col-auto">
                                                        @if ($culinary->featured_image)
                                                            <img src="{{ Storage::url($culinary->featured_image) }}"
                                                                alt="{{ $culinary->name }}" class="rounded"
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                        @endif
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="mb-1">{{ $culinary->name }}</h6>
                                                        <div class="mb-2 small text-muted">
                                                            <i
                                                                class="bi bi-geo-alt me-1"></i>{{ $culinary->district->name }}
                                                        </div>
                                                        <div class="flex-wrap gap-2 mb-2 d-flex align-items-center">
                                                            <span class="badge bg-primary">{{ $culinary->type }}</span>
                                                            @if ($culinary->halal_certified)
                                                                <span class="badge bg-success">Halal</span>
                                                            @endif
                                                            @if ($culinary->has_vegetarian_option)
                                                                <span class="badge bg-info">Vegetarian</span>
                                                            @endif
                                                        </div>
                                                        @if ($culinary->contact_person || $culinary->phone_number)
                                                            <div class="small">
                                                                @if ($culinary->contact_person)
                                                                    <div class="mb-1">
                                                                        <i
                                                                            class="bi bi-person me-1"></i>{{ $culinary->contact_person }}
                                                                    </div>
                                                                @endif
                                                                @if ($culinary->phone_number)
                                                                    <div>
                                                                        <i class="bi bi-telephone me-1"></i>
                                                                        <a href="tel:{{ $culinary->phone_number }}"
                                                                            class="text-decoration-none">
                                                                            {{ $culinary->phone_number }}
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Related Sections New line -->
                    @if (isset($availableTransportations) && $availableTransportations->isNotEmpty())
                        <div class="mb-4 border-0 shadow-sm card rounded-3">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Transportasi Tersedia</h2>
                                @foreach ($availableTransportations as $transport)
                                    <div class="pb-3 mb-3 border-bottom last:border-0">
                                        <div class="row g-2">
                                            <div class="col-auto">
                                                @if ($transport->featured_image)
                                                    <img src="{{ Storage::url($transport->featured_image) }}"
                                                        alt="{{ $transport->name }}" class="rounded"
                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                @endif
                                            </div>
                                            <div class="col">
                                                <h6 class="mb-1">{{ $transport->name }}</h6>
                                                <div class="mb-2 small text-muted">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $transport->district->name }}
                                                </div>
                                                <div class="flex-wrap gap-2 mb-2 d-flex align-items-center">
                                                    <span class="badge bg-primary">{{ $transport->type }}</span>
                                                    <span class="badge bg-info">{{ $transport->subtype }}</span>
                                                    <span class="small text-muted">
                                                        Rp {{ number_format($transport->base_price) }}
                                                    </span>
                                                </div>
                                                @if ($transport->contact_person || $transport->phone_number)
                                                    <div class="small">
                                                        @if ($transport->contact_person)
                                                            <div class="mb-1">
                                                                <i
                                                                    class="bi bi-person me-1"></i>{{ $transport->contact_person }}
                                                            </div>
                                                        @endif
                                                        @if ($transport->phone_number)
                                                            <div>
                                                                <i class="bi bi-telephone me-1"></i>
                                                                <a href="tel:{{ $transport->phone_number }}"
                                                                    class="text-decoration-none">
                                                                    {{ $transport->phone_number }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                @if ($transport->pivot && $transport->pivot->route_notes)
                                                    <div class="mt-2 small text-muted">
                                                        <i
                                                            class="bi bi-info-circle me-1"></i>{{ $transport->pivot->route_notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Related Events Section -->
                    @if (isset($relatedEvents) && $relatedEvents->isNotEmpty())
                        <div class="mb-4 border-0 shadow-sm card rounded-3">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Acara Terkait</h2>
                                @foreach ($relatedEvents as $event)
                                    <div class="pb-3 mb-3 border-bottom last:border-0">
                                        <div class="row g-2">
                                            <div class="col-auto">
                                                @if ($event->featured_image)
                                                    <img src="{{ Storage::url($event->featured_image) }}"
                                                        alt="{{ $event->name }}" class="rounded"
                                                        style="width: 80px; height: 80px; object-fit: cover;">
                                                @endif
                                            </div>
                                            <div class="col">
                                                <h6 class="mb-1">{{ $event->name }}</h6>
                                                <div class="mb-2 small">
                                                    <div class="mb-1 text-muted">
                                                        <i class="bi bi-calendar-event me-1"></i>
                                                        {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                                        @if ($event->end_date && $event->end_date != $event->start_date)
                                                            -
                                                            {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
                                                        @endif
                                                    </div>
                                                    <div class="text-muted">
                                                        <i class="bi bi-geo-alt me-1"></i>
                                                        {{ $event->location }}
                                                    </div>
                                                </div>
                                                <div class="flex-wrap gap-2 d-flex align-items-center">
                                                    <span class="badge bg-primary">{{ $event->type }}</span>
                                                    @if ($event->is_free)
                                                        <span class="badge bg-success">Gratis</span>
                                                    @else
                                                        <span class="small text-muted">
                                                            Mulai Rp {{ number_format($event->price) }}
                                                        </span>
                                                    @endif
                                                    @if ($event->status === 'upcoming')
                                                        <span class="badge bg-warning">Akan Datang</span>
                                                    @elseif($event->status === 'ongoing')
                                                        <span class="badge bg-success">Sedang Berlangsung</span>
                                                    @endif
                                                </div>
                                                @if ($event->contact_person || $event->phone_number)
                                                    <div class="mt-2 small">
                                                        @if ($event->contact_person)
                                                            <div class="mb-1">
                                                                <i
                                                                    class="bi bi-person me-1"></i>{{ $event->contact_person }}
                                                            </div>
                                                        @endif
                                                        @if ($event->phone_number)
                                                            <div>
                                                                <i class="bi bi-telephone me-1"></i>
                                                                <a href="tel:{{ $event->phone_number }}"
                                                                    class="text-decoration-none">
                                                                    {{ $event->phone_number }}
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif



                    <!-- Reviews Section -->
                    <div class="border-0 shadow-sm card rounded-3">
                        <div class="card-body">
                            <div class="mb-4 d-flex justify-content-between align-items-center">
                                <h2 class="mb-0 h5">Ulasan Pengunjung</h2>
                                @auth
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#reviewModal">
                                        Tulis Ulasan
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login untuk Menulis
                                        Ulasan</a>
                                @endauth
                            </div>

                            @if ($heritage->reviews->isNotEmpty())
                                @foreach ($heritage->reviews as $review)
                                    <div class="pb-4 mb-4 border-bottom">
                                        <!-- Review content -->
                                    </div>
                                @endforeach
                            @else
                                <div class="py-4 text-center">
                                    <i class="bi bi-chat-square-text display-4 text-muted"></i>
                                    <p class="mt-2 text-muted">Belum ada ulasan untuk warisan budaya ini</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Information Card -->
                    <div class="mb-4 border-0 shadow-sm card rounded-3">
                        <div class="card-body">
                            <h2 class="mb-4 h5">Informasi</h2>

                            @if ($heritage->recognition_status)
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-award text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Status Pengakuan</small>
                                        <span>{{ $heritage->recognition_status }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($heritage->custodian)
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-person text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Pemangku/Penjaga</small>
                                        <span>{{ $heritage->custodian }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($heritage->visitor_info)
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-info-circle text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Informasi Pengunjung</small>
                                        <span>{{ $heritage->visitor_info }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Location Section -->
                    @if ($heritage->latitude && $heritage->longitude)
                        <div class="mb-4 border-0 shadow-sm card rounded-3">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Lokasi</h2>
                                <div id="mapHeritage" class="mb-3 rounded" style="height: 400px;"></div>
                                <div class="mt-3">
                                    <div class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-geo-alt text-primary me-2"></i>
                                        <span>{{ $heritage->location }}</span>
                                    </div>
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $heritage->latitude }},{{ $heritage->longitude }}"
                                        target="_blank" class="btn btn-primary btn-sm">
                                        <i class="bi bi-map me-2"></i>Petunjuk Arah
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Amenities Section -->
                    @if (isset($nearbyAmenities) && $nearbyAmenities->isNotEmpty())
                        <div class="mb-4 border-0 shadow-sm card rounded-3">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Fasilitas Umum</h2>
                                @foreach ($nearbyAmenities as $amenity)
                                    <div class="pb-3 mb-3 border-bottom last:border-0">
                                        <div class="row g-2">
                                            <div class="col-auto">
                                                @if ($amenity->featured_image)
                                                    <img src="{{ Storage::url($amenity->featured_image) }}"
                                                        alt="{{ $amenity->name }}" class="rounded"
                                                        style="width: 60px; height: 60px; object-fit: cover;">
                                                @endif
                                            </div>
                                            <div class="col">
                                                <h6 class="mb-1">{{ $amenity->name }}</h6>
                                                <div class="mb-2 small text-muted">
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $amenity->district->name }}
                                                </div>
                                                <div class="flex-wrap gap-2 d-flex align-items-center">
                                                    <span class="badge bg-primary">{{ $amenity->type }}</span>
                                                    @if ($amenity->is_free)
                                                        <span class="badge bg-success">Gratis</span>
                                                    @endif
                                                    @if ($amenity->is_accessible)
                                                        <span class="badge bg-info">Aksesibel</span>
                                                    @endif
                                                </div>
                                                @if ($amenity->opening_hours || $amenity->closing_hours)
                                                    <div class="mt-2 small">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $amenity->opening_hours }} - {{ $amenity->closing_hours }}
                                                    </div>
                                                @endif
                                                @if ($amenity->operational_notes)
                                                    <div class="mt-2 small text-muted">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        {{ $amenity->operational_notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

    <!-- Review Modal -->
    @auth
        <!-- Review modal content -->
    @endauth
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .heritage-map-marker {
            background: none;
            border: none;
        }

        .heritage-map-marker i {
            font-size: 24px;
            -webkit-text-stroke: 2px rgb(255, 255, 255);
        }

        .heritage-map-marker.toilet i {
            color: #dc3545;
            /* Merah */
        }

        .heritage-map-marker.parking i {
            color: #0d6efd;
            /* Biru */
        }

        .heritage-map-marker.worship i {
            color: #198754;
            /* Hijau */
        }

        .heritage-map-marker.rest_area i {
            color: #ffc107;
            /* Kuning */
        }

        .heritage-map-marker.atm i {
            color: #6610f2;
            /* Ungu */
        }

        .heritage-map-marker.hospital i {
            color: #d63384;
            /* Pink */
        }

        .heritage-map-marker.police i {
            color: #084298;
            /* Biru Tua */
        }

        .heritage-map-marker.restaurant i {
            color: #fd7e14;
            /* Oranye */
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('mapHeritage').setView([{{ $heritage->latitude }}, {{ $heritage->longitude }}], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Custom icon
            const customIcon = L.divIcon({
                html: '<i class="bi bi-geo-alt-fill"></i>',
                className: 'heritage-map-marker',
                iconSize: [24, 24],
                iconAnchor: [12, 24],
                popupAnchor: [0, -24]
            });

            // Add marker
            const marker = L.marker([{{ $heritage->latitude }}, {{ $heritage->longitude }}], {
                icon: customIcon
            }).addTo(map);

            // Add popup
            marker.bindPopup(`
                <div class="text-center">
                    <strong>{{ $heritage->name }}</strong><br>
                    <small>{{ $heritage->location }}</small>
                </div>
            `).openPopup();

            // Add nearby markers if available
            @if (isset($nearbyAmenities) && $nearbyAmenities->isNotEmpty())
                @foreach ($nearbyAmenities as $amenity)
                    @if ($amenity->latitude && $amenity->longitude)
                        L.marker([{{ $amenity->latitude }}, {{ $amenity->longitude }}], {
                                icon: L.divIcon({
                                    html: `<i class="bi bi-{{ $amenity->icon ?? 'building' }}"></i>`,
                                    className: 'heritage-map-marker {{ $amenity->type }}',
                                    iconSize: [24, 24],
                                    iconAnchor: [12, 24],
                                    popupAnchor: [0, -24]
                                })
                            }).addTo(map)
                            .bindPopup(`
                                <div class="text-center">
                                    <strong>{{ $amenity->name }}</strong><br>
                                    <small class="text-muted">{{ $amenity->type }}</small>
                                    @if ($amenity->opening_hours)
                                        <br><small><i class="bi bi-clock"></i> {{ $amenity->opening_hours }}</small>
                                    @endif
                                </div>
                            `);
                    @endif
                @endforeach
            @endif
        });
    </script>
@endpush
