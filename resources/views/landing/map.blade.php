@extends('layouts.landing')

@section('title', 'Sebaran Wisata - Muna Barat')

@section('content')
    <!-- Hero Section -->
    <div class="hero-inner" style="background-image: url('{{ asset('images/hero/map.jpg') }}');">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1>Sebaran Wisata</h1>
                        <p>Jelajahi persebaran destinasi wisata di Kabupaten Muna Barat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="py-5">
        <div class="container">
            <!-- Filter Controls -->
            <div class="mb-4 row g-3">
                <div class="col-md-3">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Semua Kategori</option>
                        <option value="destination">Destinasi Wisata</option>
                        <option value="cultural">Warisan Budaya</option>
                        <option value="culinary">Kuliner</option>
                        <option value="creative">Ekonomi Kreatif</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="districtFilter">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari lokasi...">
                        <button class="btn btn-primary" type="button" id="searchButton">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="border-0 shadow-sm card">
                <div class="p-0 card-body">
                    <div id="map" style="height: 600px;"></div>
                </div>
            </div>

            <!-- Legend -->
            <div class="mt-4 border-0 shadow-sm card">
                <div class="card-body">
                    <h5 class="mb-3">Keterangan</h5>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                                <span>Destinasi Wisata</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-bank2 text-success me-2"></i>
                                <span>Warisan Budaya</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-cup-hot-fill text-danger me-2"></i>
                                <span>Kuliner</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-shop text-warning me-2"></i>
                                <span>Ekonomi Kreatif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <style>
        .marker-cluster {
            background-clip: padding-box;
            border-radius: 20px;
        }

        .marker-cluster div {
            width: 30px;
            height: 30px;
            margin-left: 5px;
            margin-top: 5px;
            text-align: center;
            border-radius: 15px;
            font-size: 12px;
            color: white;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .leaflet-popup-content {
            margin: 8px;
            min-width: 200px;
        }

        .leaflet-popup-content img {
            display: block;
            width: 100%;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        .leaflet-popup-content .btn-primary {
            color: white !important;
        }

        .leaflet-popup {
            max-width: 300px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize map
            const map = L.map('map').setView([-4.8335, 122.6675], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Initialize marker clusters
            const markers = L.markerClusterGroup();

            // Add markers from data
            @foreach ($destinations as $destination)
                addMarker(
                    {{ $destination->latitude }},
                    {{ $destination->longitude }},
                    '{{ $destination->name }}',
                    '{{ $destination->category ? $destination->category->name : 'Umum' }}',
                    'destination',
                    '{{ route('destinations.show', $destination->slug) }}',
                    '{{ $destination->featured_image }}'
                );
            @endforeach

            // Cultural Heritage
            @foreach ($culturalHeritages as $heritage)
                addMarker(
                    {{ $heritage->latitude }},
                    {{ $heritage->longitude }},
                    '{{ $heritage->name }}',
                    '{{ $heritage->type }}',
                    'cultural',
                    '{{ route('landing.cultural-heritage.show', $heritage->slug) }}',
                    '{{ $heritage->featured_image }}'
                );
            @endforeach

            // Culinary
            @foreach ($culinaries as $culinary)
                addMarker(
                    {{ $culinary->latitude }},
                    {{ $culinary->longitude }},
                    '{{ $culinary->name }}',
                    '{{ $culinary->type }}',
                    'culinary',
                    '{{ route('culinaries.show', $culinary->slug) }}',
                    '{{ $culinary->featured_image ? Storage::url($culinary->featured_image) : null }}'
                );
            @endforeach

            // Economy Creative
            @foreach ($creatives as $creative)
                addMarker(
                    {{ $creative->latitude }},
                    {{ $creative->longitude }},
                    '{{ $creative->name }}',
                    '{{ $creative->category ? $creative->category->name : 'Umum' }}',
                    'creative',
                    '{{ route('economy-creative.show', $creative->slug) }}',
                    '{{ $creative->featured_image }}'
                );
            @endforeach

            map.addLayer(markers);

            function addMarker(lat, lng, name, type, category, url, image) {
                const icon = L.divIcon({
                    html: `<i class="bi ${getIcon(category)}"></i>`,
                    className: `marker-${category}`,
                    iconSize: [24, 24],
                    iconAnchor: [12, 24],
                    popupAnchor: [0, -24]
                });

                const popupContent = `
                    <div class="text-center">
                        ${image ? `
                                <div class="mb-2">
                                    <img src="/storage/${image}"
                                        alt="${name}"
                                        class="rounded"
                                        style="width: 200px; height: 150px; object-fit: cover;">
                                </div>
                            ` : ''}
                        <strong>${name}</strong><br>
                        <small class="text-muted">${type}</small><br>
                        <a href="${url}" class="mt-2 text-white btn btn-sm btn-primary">Lihat Detail</a>
                    </div>
                `;

                const marker = L.marker([lat, lng], {
                        icon
                    })
                    .bindPopup(popupContent);

                markers.addLayer(marker);
            }

            function getIcon(category) {
                switch (category) {
                    case 'destination':
                        return 'bi-geo-alt-fill text-primary';
                    case 'cultural':
                        return 'bi-bank2 text-success';
                    case 'culinary':
                        return 'bi-cup-hot-fill text-danger';
                    case 'creative':
                        return 'bi-shop text-warning';
                    default:
                        return 'bi-geo-alt-fill text-primary';
                }
            }

            // Filter functionality
            const categoryFilter = document.getElementById('categoryFilter');
            const districtFilter = document.getElementById('districtFilter');
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');

            function filterMarkers() {
                // Implement filter logic here
            }

            categoryFilter.addEventListener('change', filterMarkers);
            districtFilter.addEventListener('change', filterMarkers);
            searchButton.addEventListener('click', filterMarkers);
        });
    </script>
@endpush
