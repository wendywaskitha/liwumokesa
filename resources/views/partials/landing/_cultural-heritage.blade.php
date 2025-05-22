{{-- resources/views/partials/landing/_cultural-heritage.blade.php --}}
<section class="py-5 cultural-heritage-section bg-light">
    <div class="container">
        <div class="mb-4 row">
            <div class="col-lg-6">
                <h2 class="section-title">Warisan Budaya</h2>
                <p class="section-description">Jelajahi kekayaan warisan budaya Muna Barat yang menakjubkan</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <a href="{{ route('landing.cultural-heritage.index') }}" class="btn btn-primary">
                    Lihat Semua <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
            
        </div>

        <div class="row g-4">
            @foreach ($culturalHeritages as $heritage)
                <div class="col-md-6 col-lg-4">
                    <div class="card heritage-card h-100">
                        @if ($heritage->featured_image)
                            <img src="{{ Storage::url($heritage->featured_image) }}" class="card-img-top"
                                alt="{{ $heritage->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <img src="{{ asset('images/placeholder/no-image.jpg') }}" class="card-img-top"
                                alt="No Image" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-start">
                                <h5 class="card-title">{{ $heritage->name }}</h5>
                                <span class="badge bg-primary">{{ $heritage->type }}</span>
                            </div>
                            <p class="mb-3 card-text text-muted">{{ Str::limit($heritage->description, 100) }}</p>
                            <div class="heritage-info">
                                <div class="mb-2 d-flex align-items-center">
                                    <i class="bi bi-geo-alt me-2"></i>
                                    <span>{{ $heritage->district->name }}</span>
                                </div>
                                @if ($heritage->opening_hours)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock me-2"></i>
                                        <span>{{ $heritage->opening_hours }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="bg-white card-footer border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('landing.cultural-heritage.show', $heritage->slug) }}"
                                    class="btn btn-sm btn-outline-primary">Lihat Detail</a>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-star-fill text-warning me-1"></i>
                                    <span>{{ number_format($heritage->average_rating, 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
