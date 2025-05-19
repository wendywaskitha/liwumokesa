<section class="featured-destinations py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Destinasi Unggulan</h2>
            <a href="{{ route('destinations.index') }}" class="btn btn-link text-primary">Lihat Semua</a>
        </div>

        <div class="row g-4">
            @foreach(\App\Models\Destination::where('is_featured', true)->limit(6)->get() as $destination)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 destination-card">
                        <div class="position-relative">
                            @if($destination->featured_image)
                                <img src="{{ asset('storage/' . $destination->featured_image) }}" class="card-img-top" alt="{{ $destination->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light" style="height: 200px;"></div>
                            @endif
                            <div class="position-absolute bottom-0 start-0 w-100 p-3 text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                                <span class="badge bg-primary">{{ $destination->category->name ?? 'Wisata' }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $destination->name }}</h5>
                            <div class="d-flex align-items-center mb-2">
                                <div class="text-warning me-2">
                                    @php
                                        $rating = $destination->reviews->avg('rating') ?? 0;
                                        $rating = round($rating, 1);
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating)
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i - 0.5 <= $rating)
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-muted small">{{ $rating }} ({{ $destination->reviews->count() }})</span>
                            </div>
                            <p class="card-text text-muted small mb-1">
                                <i class="bi bi-geo-alt"></i> {{ $destination->district->name ?? $destination->address }}
                            </p>
                            <a href="{{ route('destinations.show', $destination->slug) }}" class="btn btn-sm btn-outline-primary mt-2">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
