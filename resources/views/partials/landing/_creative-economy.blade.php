<section class="py-5 bg-light">
    <div class="container">
        <div class="mb-4 row">
            <div class="col-lg-6">
                <h2 class="mb-0">Ekonomi Kreatif</h2>
                <p class="text-muted">Temukan produk kreatif khas Muna Barat</p>
            </div>
            <div class="col-lg-6 text-lg-end">
                <a href="{{ route('economy-creative.index') }}" class="btn btn-primary">
                    Lihat Semua <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($creativeProducts as $product)
                <div class="col-6 col-md-3">
                    <div class="border-0 shadow-sm card h-100 creative-card">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $product['featured_image']) }}"
                                 class="card-img-top"
                                 alt="{{ $product['name'] }}"
                                 style="height: 200px; object-fit: cover;">
                            @if($product['is_verified'])
                                <div class="top-0 m-2 position-absolute start-0">
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Terverifikasi
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h6 class="mb-1 card-title text-truncate">{{ $product['name'] }}</h6>
                            <p class="mb-2 text-muted small">{{ $product['category'] }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="small text-muted">
                                    {{ $product['price_range'] }}
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    {{ number_format($product['rating'], 1) }}
                                </small>
                            </div>
                        </div>
                        <div class="bg-white border-0 card-footer">
                            <a href="{{ route('economy-creative.show', $product['slug']) }}"
                               class="btn btn-outline-primary btn-sm w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="py-5 text-center">
                        <img src="{{ asset('images/empty-product.svg') }}"
                             alt="Tidak ada produk"
                             class="mb-3"
                             style="max-width: 200px">
                        <h5>Belum ada produk</h5>
                        <p class="text-muted">
                            Produk ekonomi kreatif akan segera ditambahkan
                        </p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>
