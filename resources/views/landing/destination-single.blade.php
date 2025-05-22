@extends('layouts.landing')

@section('title', $destination->name . ' - Pariwisata Muna Barat')

@section('content')
    <!-- Hero Section -->
    <section class="position-relative">
        @if ($destination->featured_image)
            <img src="{{ asset('storage/destinations/' . basename($destination->featured_image)) }}"
                alt="{{ $destination->name }}" class="w-100" style="height: 60vh; object-fit: cover;">

            <!-- Optional: Add overlay gradient -->
            <div class="top-0 position-absolute start-0 w-100 h-100"
                style="background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.7));">
            </div>
        @else
            <!-- Fallback image or placeholder -->
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
                        @if ($destination->category)
                            <span class="mb-2 badge bg-primary">{{ $destination->category->name }}</span>
                        @endif
                        <h1 class="mb-2 display-4 fw-bold">{{ $destination->name }}</h1>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>{{ $destination->district->name ?? $destination->address }}</span>
                        </div>
                    </div>
                    <div class="mt-3 col-lg-4 text-lg-end mt-lg-0">
                        @if ($destination->reviews->count() > 0)
                            <div class="mb-2 d-flex align-items-center justify-content-lg-end">
                                <div class="me-2">
                                    <div class="d-flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= round($destination->reviews->avg('rating')))
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <span>
                                    {{ number_format($destination->reviews->avg('rating'), 1) }}
                                    ({{ $destination->reviews->count() }} ulasan)
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
                            <h2 class="mb-4 h5">Tentang Destinasi</h2>
                            <div class="prose">
                                {!! $destination->description !!}
                            </div>
                        </div>
                    </div>

                    <!-- Gallery Section -->
                    @if ($destination->galleries->isNotEmpty())
                        <div class="mb-4 border-0 shadow-sm card rounded-3">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Galeri Foto</h2>
                                <div class="row g-3">
                                    @foreach ($destination->galleries->sortBy('order') as $gallery)
                                        <div class="col-md-4">
                                            <a href="{{ asset('storage/' . $gallery->file_path) }}"
                                                data-fslightbox="gallery" class="d-block gallery-item">
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/' . $gallery->file_path) }}"
                                                        alt="{{ $gallery->caption ?? $destination->name }}"
                                                        class="rounded img-fluid w-100"
                                                        style="height: 200px; object-fit: cover;">

                                                    @if ($gallery->is_featured)
                                                        <div class="top-0 m-2 position-absolute end-0">
                                                            <span class="badge bg-warning">
                                                                <i class="bi bi-star-fill"></i>
                                                                Unggulan
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if ($gallery->caption)
                                                        <div
                                                            class="bottom-0 p-2 position-absolute start-0 w-100 bg-gradient-dark">
                                                            <small class="text-white">{{ $gallery->caption }}</small>
                                                        </div>
                                                    @endif
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Facilities Section -->
                    <div class="mb-4 border-0 shadow-sm card rounded-3">
                        <div class="card-body">
                            <h2 class="mb-4 h5">Fasilitas di Lakasi</h2>

                            @php
                                // Decode facilities dari JSON
                                $facilitiesData = json_decode($destination->facilities, true) ?? [];
                            @endphp

                            @if (!empty($facilitiesData))
                                <div class="row g-3">
                                    @foreach ($facilitiesData as $facility)
                                        <div class="col-md-6">
                                            <div class="p-3 rounded d-flex align-items-center bg-light">
                                                <div class="flex-shrink-0">
                                                    @php
                                                        $icons = [
                                                            'parkir' => 'bi bi-p-square',
                                                            'toilet' => 'bi bi-badge-wc',
                                                            'wifi' => 'bi bi-wifi',
                                                            'restoran' => 'bi bi-cup-hot',
                                                            'musholla' => 'bi bi-building',
                                                            'atm' => 'bi bi-credit-card',
                                                            'souvenir' => 'bi bi-bag',
                                                            'taman' => 'bi bi-tree',
                                                            'camping' => 'bi bi-house',
                                                            'gazebo' => 'bi bi-umbrella',
                                                            'default' => 'bi bi-check-circle',
                                                        ];

                                                        $facilityName = strtolower($facility['name'] ?? '');
                                                        $icon = $icons[$facilityName] ?? $icons['default'];
                                                    @endphp
                                                    <i class="{{ $icon }} text-primary fs-4"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <h6 class="mb-1">{{ $facility['name'] ?? '' }}</h6>
                                                    @if (!empty($facility['description']))
                                                        <small class="text-muted">{{ $facility['description'] }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="py-4 text-center">
                                    <div class="text-muted">
                                        <i class="bi bi-info-circle display-4"></i>
                                        <p class="mt-2">Informasi fasilitas belum tersedia</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Additional Info -->
                            @if ($destination->visiting_hours || $destination->entrance_fee)
                                <div class="pt-4 mt-4 border-top">
                                    <div class="row g-3">
                                        @if ($destination->visiting_hours)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-clock text-primary fs-4"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-1">Jam Kunjungan</h6>
                                                        <p class="mb-0">{{ $destination->visiting_hours }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($destination->entrance_fee)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-ticket-perforated text-primary fs-4"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-1">Tiket Masuk</h6>
                                                        <p class="mb-0">Rp
                                                            {{ number_format($destination->entrance_fee, 0, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>


                    <!-- Reviews -->
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

                            @if ($destination->reviews->isNotEmpty())
                                @foreach ($destination->reviews as $review)
                                    <div class="pb-4 mb-4 border-bottom">
                                        <div class="mb-2 d-flex justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $review->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) }}"
                                                    alt="{{ $review->user->name }}" class="rounded-circle"
                                                    width="40">
                                                <div class="ms-3">
                                                    <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                    <small
                                                        class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            <div class="d-flex">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $review->rating)
                                                        <i class="bi bi-star-fill text-warning"></i>
                                                    @else
                                                        <i class="bi bi-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="mb-0">{{ $review->content }}</p>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-4 text-center">
                                    <i class="bi bi-chat-square-text display-4 text-muted"></i>
                                    <p class="mt-2 text-muted">Belum ada ulasan untuk destinasi ini</p>
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

                            @if ($destination->opening_hours)
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-clock text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Jam Buka</small>
                                        <span>{{ $destination->opening_hours }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($destination->ticket_price)
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-ticket-perforated text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Harga Tiket</small>
                                        <span>Rp {{ number_format($destination->ticket_price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($destination->contact_phone)
                                <div class="mb-3 d-flex align-items-center">
                                    <i class="bi bi-telephone text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Telepon</small>
                                        <span>{{ $destination->contact_phone }}</span>
                                    </div>
                                </div>
                            @endif

                            @if ($destination->website)
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-globe text-primary me-3"></i>
                                    <div>
                                        <small class="text-muted d-block">Website</small>
                                        <a href="{{ $destination->website }}" target="_blank"
                                            class="text-decoration-none">
                                            {{ $destination->website }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Amenities -->
                    @if ($destination->amenities && $destination->amenities->isNotEmpty())
                        <div class="mb-4 border-0 shadow-sm card rounded-3">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Fasilitas</h2>
                                <div class="row g-2">
                                    @foreach ($destination->amenities as $amenity)
                                        <div class="col-6">
                                            <div class="d-flex align-items-center">
                                                <i
                                                    class="{{ $amenity->icon ?? 'bi bi-check-circle' }} text-primary me-2"></i>
                                                <span>{{ $amenity->name }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Location Section -->
                    <div class="mb-4 border-0 shadow-sm card rounded-3">
                        <div class="card-body">
                            <h2 class="mb-4 h5">Lokasi</h2>

                            @if ($destination->latitude && $destination->longitude)
                                <!-- Map Container -->
                                <div id="mapDestination" class="mb-3 rounded" style="height: 400px;"></div>

                                <!-- Location Details -->
                                <div class="mt-3">
                                    <p class="mb-2">
                                        <i class="bi bi-geo-alt text-primary me-2"></i>
                                        {{ $destination->address }}
                                    </p>
                                    @if ($destination->district)
                                        <p class="mb-2">
                                            <i class="bi bi-map text-primary me-2"></i>
                                            Kecamatan {{ $destination->district->name }}
                                        </p>
                                    @endif
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Koordinat: {{ number_format($destination->latitude, 6) }},
                                        {{ number_format($destination->longitude, 6) }}
                                    </p>
                                </div>

                                <!-- Direction Button -->
                                <div class="mt-3">
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $destination->latitude }},{{ $destination->longitude }}"
                                        class="btn btn-outline-primary btn-sm" target="_blank">
                                        <i class="bi bi-sign-turn-right me-2"></i>
                                        Petunjuk Arah
                                    </a>
                                </div>
                            @else
                                <div class="py-5 text-center">
                                    <div class="text-muted">
                                        <i class="bi bi-map display-4"></i>
                                        <p class="mt-2">Koordinat lokasi belum tersedia</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Related Tours -->
                    @if (isset($relatedPackages) && $relatedPackages->isNotEmpty())
                        <div class="border-0 shadow-sm card rounded-3">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Paket Wisata</h2>

                                @foreach ($relatedPackages as $package)
                                    <div class="d-flex mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                        <div class="flex-shrink-0">
                                            @if ($package->featured_image)
                                                <img src="{{ asset('storage/destinations/' . $package->featured_image) }}"
                                                    alt="{{ $package->name }}" class="rounded"
                                                    style="width: 80px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light" style="width: 80px; height: 60px;"></div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1">
                                                <a href="{{ route('packages.show', $package->slug) }}"
                                                    class="text-decoration-none text-dark">
                                                    {{ $package->name }}
                                                </a>
                                            </h6>
                                            <div class="mb-1 small text-muted">
                                                <i class="bi bi-clock me-1"></i>{{ $package->duration }} Hari
                                                @if ($package->destinations_count)
                                                    <span class="mx-2">•</span>
                                                    <i class="bi bi-geo-alt me-1"></i>{{ $package->destinations_count }}
                                                    Destinasi
                                                @endif
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <span class="small text-muted">Mulai dari</span>
                                                    <span class="text-primary fw-bold">Rp
                                                        {{ number_format($package->price, 0, ',', '.') }}</span>
                                                </div>
                                                @if ($package->availability === 'available')
                                                    <span class="badge bg-success">Tersedia</span>
                                                @elseif($package->availability === 'limited')
                                                    <span class="badge bg-warning">Terbatas</span>
                                                @else
                                                    <span class="badge bg-danger">Penuh</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="mt-4 text-center">
                                    <a href="{{ route('packages.index') }}" class="btn btn-outline-primary btn-sm">
                                        Lihat Semua Paket Wisata
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Review Modal -->
    @auth
        <div class="modal fade" id="reviewModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reviewable_type" value="App\Models\Destination">
                        <input type="hidden" name="reviewable_id" value="{{ $destination->id }}">

                        <div class="modal-header">
                            <h5 class="modal-title">Tulis Ulasan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <div class="rating">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}"
                                            id="rating{{ $i }}" required>
                                        <label for="rating{{ $i }}">☆</label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ulasan Anda</label>
                                <textarea name="content" rows="4" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Kirim Ulasan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth
@endsection

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- MarkerCluster CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/index.min.js"></script>

    <!-- Custom Map Styles -->
    <style>
        #mapDestination {
            min-height: 400px;
            width: 100%;
            border-radius: 8px;
        }

        .map-popup-content {
            text-align: center;
            padding: 5px;
        }

        .map-popup-content img {
            max-width: 150px;
            height: 100px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 8px;
        }

        .leaflet-popup-content {
            margin: 13px;
            min-width: 200px;
        }

        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .rating input {
            display: none;
        }

        .rating label {
            cursor: pointer;
            font-size: 30px;
            color: #ddd;
            padding: 0 2px;
        }

        .rating input:checked~label,
        .rating label:hover,
        .rating label:hover~label {
            color: #ffc107;
        }

        .bg-gradient-dark {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.8));
        }

        .gallery-item {
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .gallery-item:hover {
            transform: scale(1.02);
        }

        .gallery-item img {
            transition: all 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .bg-gradient-dark {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));
        }
    </style>
@endpush

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- MarkerCluster JS -->
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapContainer = document.getElementById('mapDestination');

            if (!mapContainer) return;

            try {
                // Validate coordinates
                const lat = {{ $destination->latitude ?? 'null' }};
                const lng = {{ $destination->longitude ?? 'null' }};

                if (!lat || !lng) {
                    console.warn('Invalid coordinates');
                    return;
                }

                // Initialize map
                const map = L.map('mapDestination', {
                    center: [lat, lng],
                    zoom: 15,
                    scrollWheelZoom: false
                });

                // Add tile layer
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);

                // Add main marker
                const mainMarker = L.marker([lat, lng])
                    .bindPopup(`
                <div class="map-popup-content">
                    <h6 class="mb-1">${@json($destination->name)}</h6>
                    <p class="mb-0 text-muted small">${@json($destination->address)}</p>
                </div>
            `)
                    .addTo(map);

                // Add nearby markers if available
                @if ($nearbyDestinations->isNotEmpty())
                    const markers = L.markerClusterGroup();

                    @foreach ($nearbyDestinations as $nearby)
                        @if ($nearby->latitude && $nearby->longitude)
                            L.marker([{{ $nearby->latitude }}, {{ $nearby->longitude }}])
                                .bindPopup(`
                            <div class="map-popup-content">
                                <h6 class="mb-1">${@json($nearby->name)}</h6>
                                <p class="mb-2 text-muted small">${@json($nearby->address)}</p>
                                <a href="{{ route('destinations.show', $nearby->slug) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </div>
                        `)
                                .addTo(markers);
                        @endif
                    @endforeach

                    map.addLayer(markers);
                @endif

                // Ensure map renders correctly
                setTimeout(() => {
                    map.invalidateSize();
                }, 100);

            } catch (error) {
                console.error('Error initializing map:', error);
                mapContainer.innerHTML = `
            <div class="m-3 alert alert-danger">
                Terjadi kesalahan saat memuat peta
            </div>
        `;
            }
        });
    </script>
@endpush
