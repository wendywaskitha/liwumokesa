@extends('layouts.landing')

@section('title', 'Kontak Kami - Visit Liwu Mokesa')

@section('content')
    <!-- Hero Section -->
    <section class="py-5 text-white bg-primary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="mb-3 display-4 fw-bold">Kontak Kami</h1>
                    <p class="mb-0 lead">Hubungi kami untuk informasi lebih lanjut tentang Visit Liwu Mokesa</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Contact Info -->
                <div class="col-lg-4">
                    <div class="border-0 shadow-sm card h-100">
                        <div class="card-body">
                            <h4 class="mb-4 card-title">Informasi Kontak</h4>

                            <!-- Address -->
                            <div class="mb-4 d-flex">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                        <i class="bi bi-geo-alt text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1 fs-6">Alamat</h5>
                                    <p class="mb-0 text-muted">{{ $contactAddress }}</p>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-4 d-flex">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                        <i class="bi bi-envelope text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1 fs-6">Email</h5>
                                    <p class="mb-0 text-muted">
                                        <a href="mailto:{{ $contactEmail }}" class="text-decoration-none text-muted">
                                            {{ $contactEmail }}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="mb-4 d-flex">
                                <div class="flex-shrink-0">
                                    <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                        <i class="bi bi-telephone text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5 class="mb-1 fs-6">Telepon</h5>
                                    <p class="mb-0 text-muted">
                                        <a href="tel:{{ $contactPhone }}" class="text-decoration-none text-muted">
                                            {{ $contactPhone }}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <!-- WhatsApp -->
                            @if ($contactWhatsapp)
                                <div class="mb-4 d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                            <i class="bi bi-whatsapp text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1 fs-6">WhatsApp</h5>
                                        <p class="mb-0 text-muted">
                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactWhatsapp) }}"
                                                class="text-decoration-none text-muted" target="_blank">
                                                {{ $contactWhatsapp }}
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Office Hours -->
                            @if ($officeHours)
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="p-3 bg-primary bg-opacity-10 rounded-3">
                                            <i class="bi bi-clock text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-1 fs-6">Jam Operasional</h5>
                                        <p class="mb-0 text-muted">{{ $officeHours }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="border-0 shadow-sm card">
                        <div class="card-body">
                            <h4 class="mb-4 card-title">Kirim Pesan</h4>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ url('/contact-message') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Subjek</label>
                                            <input type="text" name="subject"
                                                class="form-control @error('subject') is-invalid @enderror"
                                                value="{{ old('subject') }}" required>
                                            @error('subject')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Pesan</label>
                                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5" required>{{ old('message') }}</textarea>
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-send me-2"></i>
                                            Kirim Pesan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="border-0 shadow-sm card">
                <div class="p-0 card-body">
                    <div id="map" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Koordinat Dinas Pariwisata dan Ekonomi Kreatif Kab. Muna Barat
            const lat = -4.810034322874862;
            const lng = 122.40549441851633; // Sesuaikan dengan koordinat yang benar

            // Inisialisasi peta
            const map = L.map('map').setView([lat, lng], 15);

            // Tambahkan tile layer (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Tambahkan marker dengan popup
            const marker = L.marker([lat, lng]).addTo(map);

            // Konten popup dengan styling
            const popupContent = `
            <div class="text-center">
                <h6 class="mb-2">Dinas Pariwisata dan Ekonomi Kreatif</h6>
                <p class="mb-2">Kabupaten Muna Barat</p>
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    Senin - Jumat: 08.00 - 16.00 WITA
                </small>
                <hr class="my-2">
                <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}"
                   class="btn btn-sm btn-primary w-100"
                   target="_blank">
                    <i class="bi bi-sign-turn-right me-1"></i>
                    Petunjuk Arah
                </a>
            </div>
        `;

            marker.bindPopup(popupContent).openPopup();
        });
    </script>
@endpush

@push('styles')
    <style>
        #map {
            width: 100%;
            border-radius: 0.5rem;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 0.5rem;
        }

        .leaflet-popup-content {
            margin: 12px;
            min-width: 200px;
        }
    </style>
@endpush
