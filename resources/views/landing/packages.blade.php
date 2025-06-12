@extends('layouts.landing')

@section('title', 'Paket Wisata - Visit Liwu Mokesa')

@section('content')
    <!-- Hero Section -->
    <section class="py-5 text-white bg-primary">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold">Paket Wisata</h1>
                    <p class="lead">Temukan paket wisata terbaik untuk menjelajahi keindahan Kabupaten Muna Barat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <form action="{{ route('packages.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text"
                            name="q"
                            class="form-control"
                            placeholder="Cari paket wisata..."
                            value="{{ request('q') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="duration" class="form-select">
                            <option value="">Durasi</option>
                            <option value="1" {{ request('duration') == '1' ? 'selected' : '' }}>1 Hari</option>
                            <option value="2" {{ request('duration') == '2' ? 'selected' : '' }}>2 Hari</option>
                            <option value="3" {{ request('duration') == '3' ? 'selected' : '' }}>3 Hari</option>
                            <option value="4+" {{ request('duration') == '4+' ? 'selected' : '' }}>4+ Hari</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select">
                            <option value="">Tipe Paket</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="sort" class="form-select">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            <option value="duration_asc" {{ request('sort') == 'duration_asc' ? 'selected' : '' }}>Durasi Terpendek</option>
                            <option value="duration_desc" {{ request('sort') == 'duration_desc' ? 'selected' : '' }}>Durasi Terpanjang</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Packages List -->
    <section class="py-5">
        <div class="container">
            @if($packages->isEmpty())
                <div class="py-5 text-center">
                    <i class="bi bi-calendar2-x display-1 text-muted"></i>
                    <h3 class="mt-3">Tidak Ada Paket Wisata</h3>
                    <p class="text-muted">Tidak ada paket wisata yang sesuai dengan filter yang dipilih.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($packages as $package)
                        <div class="col-md-6 col-lg-4">
                            <div class="border-0 shadow-sm card h-100">
                                <!-- Package Image -->
                                <div class="position-relative">
                                    @if($package->featured_image)
                                        <img src="{{ asset('storage/' . $package->featured_image) }}"
                                            class="card-img-top"
                                            alt="{{ $package->name }}"
                                            style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="bg-light" style="height: 200px;"></div>
                                    @endif

                                    <!-- Duration Badge -->
                                    <div class="top-0 p-3 position-absolute start-0">
                                        <span class="badge bg-light text-primary">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $package->duration }} Hari
                                        </span>
                                    </div>

                                    <!-- Availability Badge -->
                                    <div class="top-0 p-3 position-absolute end-0">
                                        @if($package->availability === 'available')
                                            <span class="badge bg-success">Tersedia</span>
                                        @elseif($package->availability === 'limited')
                                            <span class="badge bg-warning">Terbatas</span>
                                        @else
                                            <span class="badge bg-danger">Penuh</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body">
                                    <!-- Package Title -->
                                    <h5 class="card-title">
                                        <a href="{{ route('packages.show', $package->slug) }}"
                                           class="text-decoration-none text-dark">
                                            {{ $package->name }}
                                        </a>
                                    </h5>

                                    <!-- Package Type -->
                                    <p class="mb-3 text-muted small">
                                        <i class="bi bi-tag me-1"></i>
                                        {{ ucfirst($package->type) }}
                                    </p>

                                    <!-- Destinations -->
                                    <div class="mb-3">
                                        @foreach($package->destinations()->take(3)->get() as $destination)
                                            <span class="badge bg-light text-dark me-1">
                                                {{ $destination->name }}
                                            </span>
                                        @endforeach
                                        @if($package->destinations()->count() > 3)
                                            <span class="badge bg-light text-dark">
                                                +{{ $package->destinations()->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Features -->
                                    <div class="mb-3">
                                        <div class="row g-2 text-muted small">
                                            @if($package->max_persons)
                                                <div class="col-auto">
                                                    <i class="bi bi-people me-1"></i>
                                                    {{ $package->max_persons }} Orang
                                                </div>
                                            @endif
                                            @if($package->transportation_included)
                                                <div class="col-auto">
                                                    <i class="bi bi-car-front me-1"></i>
                                                    Termasuk Transport
                                                </div>
                                            @endif
                                            @if($package->accommodation_included)
                                                <div class="col-auto">
                                                    <i class="bi bi-house me-1"></i>
                                                    Termasuk Penginapan
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Price and Action -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">Mulai dari</small>
                                            <div class="text-primary fw-bold">
                                                Rp {{ number_format($package->price, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        <a href="{{ route('packages.show', $package->slug) }}"
                                           class="btn btn-outline-primary">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center row justify-content-center">
                <div class="col-lg-8">
                    <h2>Butuh Paket Wisata Kustom?</h2>
                    <p class="mb-4 lead">
                        Kami dapat membantu Anda merancang paket wisata sesuai kebutuhan dan preferensi Anda.
                    </p>
                    <a href="{{ route('contact') }}?subject=Custom_Package"
                       class="btn btn-primary btn-lg">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
