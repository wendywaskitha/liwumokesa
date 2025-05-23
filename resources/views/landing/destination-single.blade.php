@extends('layouts.landing')

@section('title', $destination->name . ' - Destinasi Wisata Muna Barat')

@section('content')
    <!-- Sticky Header Info -->
    <div class="bg-white sticky-top border-bottom" style="top: 56px; z-index: 1020;">
        <div class="container">
            <div class="py-3 row">
                <div class="col-lg-8">
                    <h1 class="mb-1 h4">{{ $destination->name }}</h1>
                    <div class="gap-3 d-flex align-items-center small">
                        <div>
                            <i class="bi bi-geo-alt text-primary"></i>
                            {{ $destination->district->name }}
                        </div>
                        @if ($destination->reviews->count() > 0)
                            <div>
                                <i class="bi bi-star-fill text-warning"></i>
                                {{ number_format($destination->reviews->avg('rating'), 1) }}
                                <span class="text-muted">({{ $destination->reviews->count() }} ulasan)</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="mt-3 col-lg-4 text-lg-end mt-lg-0">
                    <button class="btn btn-outline-primary btn-sm me-2">
                        <i class="bi bi-share me-1"></i>Bagikan
                    </button>

                    @auth
                        <form action="{{ route('tourist.wishlist.toggle') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="wishable_type" value="{{ get_class($destination) }}">
                            <input type="hidden" name="wishable_id" value="{{ $destination->id }}">
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-heart{{ $destination->isWishedBy(auth()->user()) ? '-fill' : '' }} me-1"></i>
                                {{ $destination->isWishedBy(auth()->user()) ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-heart me-1"></i>Simpan ke Wishlist
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="py-4">
        <div class="container">
            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Gallery Grid -->
                    <div class="mb-4 position-relative">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <img src="{{ Storage::url($destination->featured_image) }}" class="rounded img-fluid w-100"
                                    style="height: 400px; object-fit: cover;" alt="{{ $destination->name }}">
                            </div>
                            <div class="col-md-4">
                                <div class="row g-2">
                                    @foreach ($destination->galleries->take(4) as $index => $gallery)
                                        <div class="col-6 col-md-12">
                                            <div class="position-relative">
                                                <img src="{{ Storage::url($gallery->file_path) }}"
                                                    class="rounded img-fluid w-100"
                                                    style="height: 197px; object-fit: cover;"
                                                    alt="{{ $gallery->caption ?? $destination->name }}">
                                                @if ($index == 3 && $destination->galleries->count() > 4)
                                                    <div
                                                        class="top-0 bg-opacity-50 rounded position-absolute start-0 w-100 h-100 bg-dark d-flex align-items-center justify-content-center">
                                                        <span
                                                            class="text-white h5">+{{ $destination->galleries->count() - 4 }}
                                                            foto</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <button class="bottom-0 m-3 btn btn-light position-absolute end-0" data-bs-toggle="modal"
                            data-bs-target="#galleryModal">
                            <i class="bi bi-images me-1"></i>Lihat semua foto
                        </button>
                    </div>

                    <!-- Tabs Navigation -->
                    <ul class="mb-4 nav nav-tabs" id="destinationTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#overview">Overview</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="amenities-tab" data-bs-toggle="tab" href="#amenities">Fasilitas Umum</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="accommodations-tab" data-bs-toggle="tab"
                                href="#accommodations">Akomodasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="culinary-tab" data-bs-toggle="tab" href="#culinary">Kuliner</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="transportation-tab" data-bs-toggle="tab"
                                href="#transportation">Transportasi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="creative-tab" data-bs-toggle="tab" href="#creative">Ekonomi Kreatif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="reviews-tab" data-bs-toggle="tab" href="#reviews">Ulasan</a>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="destinationTabContent">
                        <!-- Overview Tab -->
                        <div class="tab-pane fade show active" id="overview">
                            <div class="border-0 shadow-sm card">
                                <div class="card-body">
                                    <h2 class="mb-4 h5">Tentang {{ $destination->name }}</h2>
                                    <div class="prose">
                                        {!! $destination->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Amenities Tab -->
                        <div class="tab-pane fade" id="amenities">
                            <div class="border-0 shadow-sm card">
                                <div class="card-body">
                                    <h2 class="mb-4 h5">Fasilitas Umum</h2>
                                    @if ($destination->amenities->isNotEmpty())
                                        <div class="row g-4">
                                            @foreach ($destination->amenities->groupBy('type') as $type => $amenities)
                                                <div class="col-md-6">
                                                    <h6 class="mb-3">{{ $type }}</h6>
                                                    <ul class="list-unstyled">
                                                        @foreach ($amenities as $amenity)
                                                            <li class="mb-2">
                                                                <i
                                                                    class="bi bi-{{ $amenity->icon ?? 'check-circle' }} text-primary me-2"></i>
                                                                {{ $amenity->name }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-4 text-center">
                                            <i class="bi bi-info-circle display-4 text-muted"></i>
                                            <p class="mt-2 text-muted">Belum ada data fasilitas umum</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Accommodations Tab -->
                        <div class="tab-pane fade" id="accommodations">
                            <div class="border-0 shadow-sm card">
                                <div class="card-body">
                                    <div class="mb-4 d-flex justify-content-between align-items-center">
                                        <h2 class="mb-0 h5">Akomodasi Terdekat</h2>
                                        @if (optional($destination->accommodations)->isNotEmpty())
                                            <div class="dropdown">
                                                <button class="btn btn-outline-primary btn-sm dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-funnel me-1"></i>Filter
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#"
                                                            data-sort="distance">Jarak Terdekat</a></li>
                                                    <li><a class="dropdown-item" href="#"
                                                            data-sort="recommended">Rekomendasi</a></li>
                                                    <li><a class="dropdown-item" href="#" data-sort="price">Harga
                                                            Terendah</a></li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>

                                    @if (optional($destination->accommodations)->isNotEmpty())
                                        <div class="row g-4" id="accommodationsList">
                                            @foreach ($destination->accommodations->sortBy('pivot.distance') as $accommodation)
                                                <div class="col-md-6"
                                                    data-distance="{{ $accommodation->pivot->distance }}">
                                                    <div class="card h-100 hover-shadow">
                                                        @if ($accommodation->featured_image)
                                                            <img src="{{ Storage::url($accommodation->featured_image) }}"
                                                                class="card-img-top" alt="{{ $accommodation->name }}"
                                                                style="height: 200px; object-fit: cover;">
                                                        @endif
                                                        <div class="card-body">
                                                            <h5 class="card-title h6">{{ $accommodation->name }}</h5>
                                                            <div class="mb-2 small text-muted">
                                                                <i class="bi bi-geo-alt me-1"></i>
                                                                {{ $accommodation->district->name }}
                                                                <span class="ms-2">
                                                                    <i class="bi bi-signpost-2 me-1"></i>
                                                                    {{ number_format($accommodation->pivot->distance, 1) }}
                                                                    km
                                                                </span>
                                                            </div>
                                                            <div class="flex-wrap gap-2 mb-3 d-flex align-items-center">
                                                                <span
                                                                    class="badge bg-primary">{{ $accommodation->type }}</span>
                                                                @if ($accommodation->pivot->is_recommended)
                                                                    <span class="badge bg-success">
                                                                        <i class="bi bi-star-fill me-1"></i>Rekomendasi
                                                                    </span>
                                                                @endif
                                                                <span class="small text-muted">
                                                                    Mulai Rp
                                                                    {{ number_format($accommodation->price_range_start) }}
                                                                </span>
                                                            </div>
                                                            @if ($accommodation->facilities)
                                                                <div class="small text-muted">
                                                                    <i class="bi bi-check-circle me-1"></i>
                                                                    {{ implode(', ', $accommodation->facilities) }}
                                                                </div>
                                                            @endif
                                                            @if ($accommodation->pivot->notes)
                                                                <div class="mt-2 small text-muted">
                                                                    <i class="bi bi-info-circle me-1"></i>
                                                                    {{ $accommodation->pivot->notes }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="bg-white card-footer border-top-0">
                                                            <div class="d-grid">
                                                                <a href="{{ route('accommodations.show', $accommodation->slug) }}"
                                                                    class="btn btn-outline-primary btn-sm">
                                                                    <i class="bi bi-info-circle me-1"></i>Lihat Detail
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-4 text-center">
                                            <i class="bi bi-building display-4 text-muted"></i>
                                            <p class="mt-2 text-muted">Belum ada data akomodasi terdekat</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Culinary Tab -->
                        <div class="tab-pane fade" id="culinary">
                            <div class="border-0 shadow-sm card">
                                <div class="card-body">
                                    <h2 class="mb-4 h5">Kuliner Terdekat</h2>
                                    @if ($destination->culinaries->isNotEmpty())
                                        <div class="row g-4">
                                            @foreach ($destination->culinaries as $culinary)
                                                <div class="col-md-6">
                                                    <div class="card h-100">
                                                        @if ($culinary->featured_image)
                                                            <img src="{{ Storage::url($culinary->featured_image) }}"
                                                                class="card-img-top" alt="{{ $culinary->name }}"
                                                                style="height: 200px; object-fit: cover;">
                                                        @endif
                                                        <div class="card-body">
                                                            <h5 class="card-title h6">{{ $culinary->name }}</h5>
                                                            <div class="mb-2 small text-muted">
                                                                <i
                                                                    class="bi bi-geo-alt me-1"></i>{{ $culinary->district->name }}
                                                            </div>
                                                            <div class="flex-wrap gap-2 mb-3 d-flex align-items-center">
                                                                <span
                                                                    class="badge bg-primary">{{ $culinary->type }}</span>
                                                                @if ($culinary->halal_certified)
                                                                    <span class="badge bg-success">Halal</span>
                                                                @endif
                                                                @if ($culinary->has_vegetarian_option)
                                                                    <span class="badge bg-info">Vegetarian</span>
                                                                @endif
                                                            </div>
                                                            @if ($culinary->specialties)
                                                                <div class="small text-muted">
                                                                    <i class="bi bi-award me-1"></i>
                                                                    Menu unggulan: {{ $culinary->specialties }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-4 text-center">
                                            <i class="bi bi-cup-hot display-4 text-muted"></i>
                                            <p class="mt-2 text-muted">Belum ada data kuliner terdekat</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Transportation Tab -->
                        <div class="tab-pane fade" id="transportation">
                            <div class="border-0 shadow-sm card">
                                <div class="card-body">
                                    <h2 class="mb-4 h5">Transportasi Tersedia</h2>
                                    @if ($destination->transportations->isNotEmpty())
                                        <div class="row g-4">
                                            @foreach ($destination->transportations as $transport)
                                                <div class="col-md-6">
                                                    <div class="card h-100">
                                                        @if ($transport->featured_image)
                                                            <img src="{{ Storage::url($transport->featured_image) }}"
                                                                class="card-img-top" alt="{{ $transport->name }}"
                                                                style="height: 200px; object-fit: cover;">
                                                        @endif
                                                        <div class="card-body">
                                                            <h5 class="card-title h6">{{ $transport->name }}</h5>
                                                            <div class="mb-2 small text-muted">
                                                                <i
                                                                    class="bi bi-geo-alt me-1"></i>{{ $transport->district->name }}
                                                            </div>
                                                            <div class="flex-wrap gap-2 mb-3 d-flex align-items-center">
                                                                <span
                                                                    class="badge bg-primary">{{ $transport->type }}</span>
                                                                <span class="small text-muted">
                                                                    Rp {{ number_format($transport->base_price) }}
                                                                </span>
                                                            </div>
                                                            @if ($transport->pivot && $transport->pivot->route_notes)
                                                                <div class="small text-muted">
                                                                    <i class="bi bi-info-circle me-1"></i>
                                                                    {{ $transport->pivot->route_notes }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-4 text-center">
                                            <i class="bi bi-car-front display-4 text-muted"></i>
                                            <p class="mt-2 text-muted">Belum ada data transportasi tersedia</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Creative Economy Tab -->
                        <div class="tab-pane fade" id="creative">
                            <div class="border-0 shadow-sm card">
                                <div class="card-body">
                                    <h2 class="mb-4 h5">Ekonomi Kreatif</h2>
                                    @if ($destination->creativeEconomies->isNotEmpty())
                                        <div class="row g-4">
                                            @foreach ($destination->creativeEconomies as $creative)
                                                <div class="col-md-6">
                                                    <div class="card h-100">
                                                        @if ($creative->featured_image)
                                                            <img src="{{ Storage::url($creative->featured_image) }}"
                                                                class="card-img-top" alt="{{ $creative->name }}"
                                                                style="height: 200px; object-fit: cover;">
                                                        @endif
                                                        <div class="card-body">
                                                            <h5 class="card-title h6">{{ $creative->name }}</h5>
                                                            <div class="mb-2 small text-muted">
                                                                <i
                                                                    class="bi bi-geo-alt me-1"></i>{{ $creative->district->name }}
                                                            </div>
                                                            <div class="flex-wrap gap-2 mb-3 d-flex align-items-center">
                                                                <span
                                                                    class="badge bg-primary">{{ $creative->category->name }}</span>
                                                                @if ($creative->has_workshop)
                                                                    <span class="badge bg-info">Workshop Tersedia</span>
                                                                @endif
                                                            </div>
                                                            @if ($creative->products_description)
                                                                <div class="small text-muted">
                                                                    <i class="bi bi-bag me-1"></i>
                                                                    {{ $creative->products_description }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="py-4 text-center">
                                            <i class="bi bi-shop display-4 text-muted"></i>
                                            <p class="mt-2 text-muted">Belum ada data ekonomi kreatif</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" id="reviews">
                            <div class="border-0 shadow-sm card">
                                <div class="card-body">
                                    <!-- Alert Messages -->
                                    @if (session('success'))
                                        <div class="mb-4 alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="mb-4 alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        </div>
                                    @endif

                                    <!-- Header -->
                                    <div class="mb-4 d-flex justify-content-between align-items-center">
                                        <h2 class="mb-0 h5">Ulasan Pengunjung</h2>
                                        @auth
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#reviewModal">
                                                <i class="bi bi-pencil me-1"></i>Tulis Ulasan
                                            </button>
                                        @else
                                            <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                                                <i class="bi bi-box-arrow-in-right me-1"></i>Login untuk Menulis Ulasan
                                            </a>
                                        @endauth
                                    </div>

                                    <!-- Reviews Content -->
                                    @if ($destination->reviews->where('status', 'approved')->isNotEmpty())
                                        <!-- Rating Summary -->
                                        <div class="mb-4 row">
                                            <div class="text-center col-md-4">
                                                <div class="display-4 fw-bold text-primary">
                                                    {{ number_format($destination->reviews->where('status', 'approved')->avg('rating'), 1) }}
                                                </div>
                                                <div class="mb-2">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i
                                                            class="bi bi-star{{ $i <= round($destination->reviews->where('status', 'approved')->avg('rating')) ? '-fill' : '' }} text-warning"></i>
                                                    @endfor
                                                </div>
                                                <div class="text-muted">
                                                    {{ $destination->reviews->where('status', 'approved')->count() }}
                                                    ulasan
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                @php
                                                    $approvedReviews = $destination->reviews->where(
                                                        'status',
                                                        'approved',
                                                    );
                                                    $ratings = $approvedReviews->groupBy('rating');
                                                    $totalReviews = $approvedReviews->count();
                                                @endphp
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <div class="mb-2 d-flex align-items-center">
                                                        <div class="text-muted small" style="width: 60px;">
                                                            {{ $i }} bintang
                                                        </div>
                                                        <div class="mx-2 flex-grow-1">
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar bg-warning"
                                                                    style="width: {{ ($ratings->get($i, collect())->count() / ($totalReviews ?: 1)) * 100 }}%">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="text-muted small" style="width: 60px;">
                                                            {{ $ratings->get($i, collect())->count() }}
                                                        </div>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>

                                        <!-- Reviews List -->
                                        @foreach ($destination->reviews->where('status', 'approved')->sortByDesc('created_at') as $review)
                                            <div class="pb-4 mb-4 border-bottom last:border-0 last:pb-0 last:mb-0">
                                                <div class="d-flex">
                                                    <img src="{{ $review->user->profile_photo_url }}"
                                                        alt="{{ $review->user->name }}" class="rounded-circle me-3"
                                                        width="48" height="48">
                                                    <div class="flex-grow-1">
                                                        <div
                                                            class="mb-2 d-flex justify-content-between align-items-center">
                                                            <h6 class="mb-0">{{ $review->user->name }}</h6>
                                                            <small
                                                                class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                                        </div>
                                                        <div class="mb-2">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <i
                                                                    class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                                                            @endfor
                                                        </div>
                                                        <p class="mb-0">{{ $review->comment }}</p>
                                                    </div>
                                                </div>
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
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Map Card -->
                    @if ($destination->latitude && $destination->longitude)
                        <div class="mb-4 border-0 shadow-sm card">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Lokasi</h2>
                                <div id="map" class="mb-3 rounded" style="height: 300px;"></div>
                                <div class="d-grid">
                                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $destination->latitude }},{{ $destination->longitude }}"
                                        class="btn btn-primary" target="_blank">
                                        <i class="bi bi-map me-2"></i>Petunjuk Arah
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Related Cards -->
                    @if (isset($destination->accommodations) && $destination->accommodations->isNotEmpty())
                        <div class="mb-4 border-0 shadow-sm card">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Akomodasi Terdekat</h2>
                                @foreach ($destination->accommodations as $accommodation)
                                    <div class="pb-3 mb-3 d-flex border-bottom last:mb-0 last:pb-0 last:border-0">
                                        <img src="{{ Storage::url($accommodation->featured_image) }}"
                                            alt="{{ $accommodation->name }}" class="rounded me-3"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-1">{{ $accommodation->name }}</h6>
                                            <div class="mb-2 small text-muted">
                                                <i class="bi bi-geo-alt me-1"></i>{{ $accommodation->district->name }}
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-primary me-2">{{ $accommodation->type }}</span>
                                                <span class="small text-muted">
                                                    Mulai Rp {{ number_format($accommodation->price_range_start) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Other related sections (Culinary, Transportation, etc.) follow the same pattern -->
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Galeri Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        @foreach ($destination->galleries as $gallery)
                            <div class="col-md-4">
                                <a href="{{ Storage::url($gallery->file_path) }}" data-fslightbox="gallery-modal">
                                    <img src="{{ Storage::url($gallery->file_path) }}"
                                        alt="{{ $gallery->caption ?? $destination->name }}"
                                        class="rounded img-fluid w-100" style="height: 200px; object-fit: cover;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Review Modal -->
    @auth
        <div class="modal fade" id="reviewModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tulis Ulasan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="reviewable_type" value="App\Models\Destination">
                        <input type="hidden" name="reviewable_id" value="{{ $destination->id }}">

                        <div class="modal-body">
                            <!-- Rating Input -->
                            <div class="mb-3">
                                <label class="form-label">Rating <span class="text-danger">*</span></label>
                                <div class="rating-input">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" value="{{ $i }}"
                                            id="rating{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}
                                            required>
                                        <label for="rating{{ $i }}">
                                            <i class="bi bi-star-fill"></i>
                                        </label>
                                    @endfor
                                </div>
                                @error('rating')
                                    <div class="mt-1 text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Comment Input -->
                            <div class="mb-3">
                                <label class="form-label">Komentar <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('comment') is-invalid @enderror" name="comment" rows="3" required
                                    minlength="10" maxlength="1000">{{ old('comment') }}</textarea>
                                <div class="form-text">Minimal 10 karakter, maksimal 1000 karakter</div>
                                @error('comment')
                                    <div class="mt-1 text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i>Kirim Ulasan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endauth
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fslightbox@3.3.1/index.min.css">
    <style>
        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .rating-input input {
            display: none;
        }

        .rating-input label {
            cursor: pointer;
            padding: 0 0.1em;
            font-size: 1.5rem;
        }

        .rating-input label i {
            color: #ddd;
        }

        .rating-input input:checked~label i {
            color: #ffc107;
        }

        .rating-input label:hover i,
        .rating-input label:hover~label i {
            color: #ffc107;
        }

        .prose {
            line-height: 1.8;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 2px solid transparent;
            padding: 1rem;
        }

        .nav-tabs .nav-link.active {
            color: var(--bs-primary);
            border-bottom-color: var(--bs-primary);
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fslightbox@3.3.1/index.min.js"></script>
    <script>
        // Initialize map
        const map = L.map('map').setView([{{ $destination->latitude }}, {{ $destination->longitude }}], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add marker
        L.marker([{{ $destination->latitude }}, {{ $destination->longitude }}])
            .addTo(map)
            .bindPopup('{{ $destination->name }}');
    </script>
@endpush
