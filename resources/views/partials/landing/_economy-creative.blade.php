{{-- resources/views/partials/landing/_economy-creative.blade.php --}}
<section class="py-5 creative-economy-section">
    <div class="container">
        <!-- Section Header -->
        <div class="mb-5 text-center">
            <span class="text-primary fw-semibold">Ekonomi Kreatif</span>
            <h2 class="mb-3 display-5 fw-bold">Produk Kreatif Muna Barat</h2>
            <p class="text-muted">Temukan keunikan dan kreativitas produk lokal dari tangan-tangan terampil masyarakat Muna Barat</p>
        </div>

        <!-- Categories -->
        <div class="mb-5 row g-4">
            <div class="col-6 col-md-3">
                <a href="{{ route('creative-economy.category', 'kerajinan') }}" class="text-decoration-none">
                    <div class="border-0 shadow-sm card category-card h-100">
                        <div class="p-4 text-center card-body">
                            <div class="mb-3 category-icon">
                                <i class="bi bi-palette h1 text-primary"></i>
                            </div>
                            <h5 class="mb-2 card-title">Kerajinan Tangan</h5>
                            <p class="mb-0 text-muted small">Produk Kerajinan Lokal</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('creative-economy.category', 'fashion') }}" class="text-decoration-none">
                    <div class="border-0 shadow-sm card category-card h-100">
                        <div class="p-4 text-center card-body">
                            <div class="mb-3 category-icon">
                                <i class="bi bi-bag h1 text-primary"></i>
                            </div>
                            <h5 class="mb-2 card-title">Fashion</h5>
                            <p class="mb-0 text-muted small">Busana & Aksesoris</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('creative-economy.category', 'kuliner') }}" class="text-decoration-none">
                    <div class="border-0 shadow-sm card category-card h-100">
                        <div class="p-4 text-center card-body">
                            <div class="mb-3 category-icon">
                                <i class="bi bi-cup-hot h1 text-primary"></i>
                            </div>
                            <h5 class="mb-2 card-title">Kuliner</h5>
                            <p class="mb-0 text-muted small">Makanan & Minuman Khas</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('creative-economy.category', 'seni') }}" class="text-decoration-none">
                    <div class="border-0 shadow-sm card category-card h-100">
                        <div class="p-4 text-center card-body">
                            <div class="mb-3 category-icon">
                                <i class="bi bi-brush h1 text-primary"></i>
                            </div>
                            <h5 class="mb-2 card-title">Seni</h5>
                            <p class="mb-0 text-muted small">Karya Seni Lokal</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Featured Products -->
        <div class="mb-5 featured-products">
            <div class="mb-4 d-flex justify-content-between align-items-center">
                <h3>Produk Unggulan</h3>
                <a href="{{ route('creative-economy.index') }}" class="btn btn-outline-primary">
                    Lihat Semua
                </a>
            </div>

            <div class="row g-4">
                @forelse($featuredProducts ?? [] as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="border-0 shadow-sm card product-card h-100">
                        <div class="position-relative">
                            <img src="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/placeholder.jpg') }}"
                                 class="card-img-top"
                                 alt="{{ $product->name }}"
                                 style="height: 200px; object-fit: cover;">
                            <div class="product-overlay">
                                <a href="{{ route('creative-economy.show', $product->slug) }}"
                                   class="btn btn-sm btn-light">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="mb-2 card-title">{{ $product->name }}</h5>
                            <p class="mb-3 text-muted small">{{ Str::limit($product->short_description, 60) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary">
                                    Rp {{ number_format($product->price_range_start, 0, ',', '.') }}
                                </span>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $product->phone_number) }}"
                                   class="btn btn-sm btn-success"
                                   target="_blank">
                                    <i class="bi bi-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="py-4 text-center">
                        <p class="text-muted">Belum ada produk unggulan</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Category Card */
.category-card {
    transition: transform 0.3s ease;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: rgba(var(--bs-primary-rgb), 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

/* Product Card */
.product-card {
    transition: transform 0.3s ease;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
}
</style>
@endpush
