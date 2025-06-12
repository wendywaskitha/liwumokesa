<!-- resources/views/landing/destinations.blade.php -->
@extends('layouts.landing')

@section('title', 'Destinasi Wisata - Visit Liwu Mokesa')

@section('content')
    <!-- Page Header -->
    <section class="py-5 text-white bg-primary">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold">Destinasi Wisata</h1>
                    <p class="lead">Temukan keindahan alam dan kekayaan budaya di berbagai destinasi wisata Kabupaten Muna Barat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Search & Filters -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="row g-3">
                <div class="col-md-6">
                    <form action="{{ route('destinations.index') }}" method="GET" class="d-flex">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control me-2" placeholder="Cari destinasi...">
                        <button type="submit" class="btn btn-primary">Cari</button>
                    </form>
                </div>
                <div class="col-md-6 d-flex justify-content-md-end">
                    <div class="dropdown me-2">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Kecamatan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('destinations.index') }}">Semua</a></li>
                            @foreach($districts as $district)
                                <li><a class="dropdown-item" href="{{ route('destinations.index', ['district_id' => $district->id]) }}">{{ $district->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="dropdown me-2">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Kategori
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('destinations.index') }}">Semua</a></li>
                            @foreach($categories as $category)
                                <li><a class="dropdown-item" href="{{ route('destinations.index', ['category' => $category->slug]) }}">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Urutkan
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('destinations.index', array_merge(request()->query(), ['sort' => 'newest'])) }}">Terbaru</a></li>
                            <li><a class="dropdown-item" href="{{ route('destinations.index', array_merge(request()->query(), ['sort' => 'rating'])) }}">Rating Tertinggi</a></li>
                            <li><a class="dropdown-item" href="{{ route('destinations.index', array_merge(request()->query(), ['sort' => 'popular'])) }}">Terpopuler</a></li>
                            <li><a class="dropdown-item" href="{{ route('destinations.index', array_merge(request()->query(), ['sort' => 'name_asc'])) }}">Nama (A-Z)</a></li>
                            <li><a class="dropdown-item" href="{{ route('destinations.index', array_merge(request()->query(), ['sort' => 'name_desc'])) }}">Nama (Z-A)</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinations Listing -->
    <section class="py-5">
        <div class="container">
            @if(request('q'))
                <div class="mb-4 alert alert-info">
                    Hasil pencarian untuk: <strong>{{ request('q') }}</strong>
                </div>
            @endif

            @if($destinations->isEmpty())
                <div class="py-5 text-center">
                    <img src="{{ asset('images/no-results.svg') }}" alt="Tidak ada hasil" class="mb-3 img-fluid" style="max-height: 200px;">
                    <h3 class="h4">Destinasi tidak ditemukan</h3>
                    <p class="text-muted">Coba gunakan kata kunci pencarian lain atau hapus filter yang digunakan.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($destinations as $destination)
                        <div class="col">
                            <div class="border-0 shadow-sm card h-100 destination-card">
                                <div class="position-relative">
                                    @if($destination->featured_image)
                                        <img src="{{  asset('storage/destinations/' . basename($destination->featured_image)) }}" class="card-img-top" alt="{{ $destination->name }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary" style="height: 200px;"></div>
                                    @endif
                                    <div class="bottom-0 p-3 position-absolute start-0">
                                        <span class="badge bg-primary">{{ $destination->category->name ?? 'Umum' }}</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $destination->name }}</h5>

                                    <div class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-star-fill text-warning me-1"></i>
                                        <span>{{ $destination->reviews->avg('rating') ? number_format($destination->reviews->avg('rating'), 1) : 'Belum ada ulasan' }}</span>

                                        @if($destination->reviews->count() > 0)
                                            <span class="text-muted ms-1">({{ $destination->reviews->count() }})</span>
                                        @endif
                                    </div>

                                    <div class="mb-3 d-flex align-items-center">
                                        <i class="bi bi-geo-alt text-muted me-1"></i>
                                        <span class="text-muted">{{ $destination->district->name ?? $destination->address }}</span>
                                    </div>

                                    <p class="mb-3 card-text text-muted small">{{ Str::limit($destination->description, 100) }}</p>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('destinations.show', $destination->slug) }}" class="btn btn-outline-primary">Detail</a>

                                        <div>
                                            @if($destination->amenities->isNotEmpty())
                                                <span class="d-inline-flex align-items-center text-muted small">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    {{ $destination->amenities->count() }} fasilitas
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $destinations->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- Promotion Banner -->
    <section class="py-5 text-center bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <h2>Ingin mengunjungi beberapa destinasi sekaligus?</h2>
                    <p class="mb-4 lead">Nikmati kemudahan berwisata dengan paket wisata yang sudah termasuk transportasi, penginapan, dan pemandu wisata.</p>
                    <a href="{{ route('packages.index') }}" class="btn btn-primary">Lihat Paket Wisata</a>
                </div>
            </div>
        </div>
    </section>
@endsection
