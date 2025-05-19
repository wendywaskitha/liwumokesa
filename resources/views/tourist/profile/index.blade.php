{{-- resources/views/tourist/profile/index.blade.php --}}
@extends('layouts.tourist-dashboard')

@section('title', 'Profil Saya')

@section('content')
    <div class="container-fluid">
        <!-- Profile Header -->
        <div class="mb-4 profile-header">
            <div class="text-white card bg-primary">
                <div class="py-4 card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="position-relative">
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"
                                    class="rounded-circle profile-photo"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                                <button class="bottom-0 btn btn-sm btn-light position-absolute end-0" data-bs-toggle="modal"
                                    data-bs-target="#updatePhotoModal">
                                    <i class="bi bi-camera"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-2">{{ auth()->user()->name }}</h4>
                            <p class="mb-1">
                                <i class="bi bi-geo-alt me-2"></i>
                                {{ auth()->user()->address ?? 'Belum ada lokasi' }}
                            </p>
                            <p class="mb-0">
                                <i class="bi bi-calendar3 me-2"></i>
                                Bergabung sejak {{ auth()->user()->created_at->format('M Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Basic Info Card -->
                <div class="mb-4 card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 card-title">Informasi Dasar</h5>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small text-muted">Nama Lengkap</label>
                                <p class="mb-0">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Email</label>
                                <p class="mb-0">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">No. Telepon</label>
                                <p class="mb-0">{{ auth()->user()->phone ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Tanggal Lahir</label>
                                <p class="mb-0">{{ auth()->user()->birthdate?->format('d M Y') ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Travel Preferences Card -->
                <div class="mb-4 card">
                    <div class="card-header">
                        <h5 class="mb-0 card-title">Preferensi Wisata</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small text-muted">Jenis Wisata Favorit</label>
                                <div class="flex-wrap gap-2 d-flex">
                                    <span class="badge bg-primary">Pantai</span>
                                    <span class="badge bg-primary">Budaya</span>
                                    <span class="badge bg-primary">Kuliner</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted">Budget Perjalanan</label>
                                <p class="mb-0">Rp 1.000.000 - Rp 5.000.000</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Travel History Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 card-title">Riwayat Perjalanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @forelse($travelHistory ?? [] as $history)
                                <div class="timeline-item">
                                    <div class="timeline-point"></div>
                                    <div class="timeline-content">
                                        <h6>{{ $history->destination_name }}</h6>
                                        <p class="mb-0 small text-muted">
                                            {{ $history->visit_date->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-4 text-center">
                                    <img src="{{ asset('images/empty-history.svg') }}" alt="No Travel History"
                                        class="mb-3" style="max-width: 150px">
                                    <p class="text-muted">
                                        Belum ada riwayat perjalanan.
                                        <br>
                                        <a href="{{ route('packages.index') }}" class="text-primary">
                                            Mulai jelajahi paket wisata
                                        </a>
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Stats Card -->
                <div class="mb-4 card">
                    <div class="card-body">
                        <div class="text-center row g-3">
                            <div class="col-4">
                                <h3 class="mb-1">{{ $stats['total_visits'] ?? 0 }}</h3>
                                <small class="text-muted">Kunjungan</small>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-1">{{ $stats['total_reviews'] ?? 0 }}</h3>
                                <small class="text-muted">Ulasan</small>
                            </div>
                            <div class="col-4">
                                <h3 class="mb-1">{{ $stats['total_photos'] ?? 0 }}</h3>
                                <small class="text-muted">Foto</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Achievements Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 card-title">Pencapaian</h5>
                    </div>
                    <div class="card-body">
                        <div class="achievement-list">
                            <div class="achievement-item">
                                <div class="achievement-icon">
                                    <i class="bi bi-trophy-fill text-warning"></i>
                                </div>
                                <div class="achievement-info">
                                    <h6 class="mb-1">Petualang Pemula</h6>
                                    <small class="text-muted">Kunjungi 5 destinasi</small>
                                    <div class="mt-2 progress" style="height: 5px;">
                                        <div class="progress-bar" style="width: 60%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Profile Styles */
            .profile-header {
                position: relative;
                margin-bottom: 2rem;
            }

            .profile-photo {
                border: 4px solid white;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            /* Card Styles */
            .card {
                border: none;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s;
            }

            .card:hover {
                transform: translateY(-2px);
            }

            /* Timeline Styles */
            .timeline {
                position: relative;
                padding: 1rem 0;
            }

            .timeline::before {
                content: '';
                position: absolute;
                left: 1rem;
                top: 0;
                bottom: 0;
                width: 2px;
                background: #e2e8f0;
            }

            .timeline-item {
                position: relative;
                padding-left: 3rem;
                margin-bottom: 1.5rem;
            }

            .timeline-point {
                position: absolute;
                left: 0.5rem;
                width: 1rem;
                height: 1rem;
                border-radius: 50%;
                background: var(--primary-color);
                border: 2px solid white;
            }

            /* Achievement Styles */
            .achievement-list {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .achievement-item {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .achievement-icon {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                background: #fff8e1;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.25rem;
            }

            .achievement-info {
                flex: 1;
            }
        </style>
    @endpush
@endsection
