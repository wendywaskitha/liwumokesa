{{-- resources/views/landing/creative-economy.blade.php --}}
@extends('layouts.landing')

@section('title', 'Ekonomi Kreatif Muna Barat')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative">
    <div class="hero-image" style="background-image: url('{{ asset('images/creative-economy/hero-bg.jpg') }}');">
        <div class="overlay"></div>
        <div class="container">
            <div class="row min-vh-75 align-items-center">
                <div class="text-white col-lg-8">
                    <h1 class="mb-4 display-4 fw-bold">Ekonomi Kreatif Muna Barat</h1>
                    <p class="mb-4 lead">Temukan keunikan produk lokal dan kreativitas masyarakat Muna Barat yang mendunia.</p>
                    <a href="#products" class="btn btn-primary btn-lg">Jelajahi Produk</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5 categories-section">
    <div class="container">
        <div class="mb-5 text-center">
            <h2 class="section-title">Kategori Produk</h2>
            <p class="text-muted">Berbagai produk kreatif dari tangan-tangan terampil Muna Barat</p>
        </div>

        <div class="row g-4">
            @foreach($categories as $category)
            <div class="col-6 col-md-3">
                <a href="#" class="category-card text-decoration-none">
                    <div class="border-0 shadow-sm card h-100">
                        <div class="p-4 text-center card-body">
                            <i class="{{ $category->icon }} h1 mb-3 text-primary"></i>
                            <h5 class="mb-2 card-title">{{ $category->name }}</h5>
                            <p class="mb-0 text-muted small">{{ $category->product_count }} Produk</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="products" class="py-5 featured-products-section bg-light">
    <div class="container">
        <div class="mb-5 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">Produk Unggulan</h2>
                <p class="mb-0 text-muted">Produk terbaik dari pengrajin lokal</p>
            </div>
            <div class="d-none d-md-block">
                <div class="btn-group">
                    <button class="btn btn-outline-primary active" data-filter="all">Semua</button>
                    <button class="btn btn-outline-primary" data-filter="handicraft">Kerajinan</button>
                    <button class="btn btn-outline-primary" data-filter="fashion">Fashion</button>
                    <button class="btn btn-outline-primary" data-filter="food">Kuliner</button>
                </div>
            </div>
        </div>

        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-md-6 col-lg-4">
                <div class="border-0 shadow-sm card product-card h-100">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $product->image) }}"
                             class="card-img-top"
                             alt="{{ $product->name }}"
                             style="height: 250px; object-fit: cover;">
                        <div class="product-overlay">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-light btn-sm">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2 d-flex justify-content-between align-items-start">
                            <h5 class="mb-0 card-title">{{ $product->name }}</h5>
                            <span class="badge bg-primary">{{ $product->category->name }}</span>
                        </div>
                        <p class="mb-3 text-muted small">{{ Str::limit($product->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="mb-0 h5">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $product->contact_phone) }}"
                               class="btn btn-success btn-sm"
                               target="_blank">
                                <i class="bi bi-whatsapp me-1"></i> Pesan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Craftsmen Section -->
<section class="py-5 craftsmen-section">
    <div class="container">
        <div class="mb-5 text-center">
            <h2 class="section-title">Pengrajin Kami</h2>
            <p class="text-muted">Bertemu dengan para pengrajin berbakat Muna Barat</p>
        </div>

        <div class="row g-4">
            @foreach($craftsmen as $craftsman)
            <div class="col-md-6 col-lg-3">
                <div class="border-0 shadow-sm card craftsman-card h-100">
                    <img src="{{ asset('storage/' . $craftsman->photo) }}"
                         class="card-img-top"
                         alt="{{ $craftsman->name }}"
                         style="height: 250px; object-fit: cover;">
                    <div class="text-center card-body">
                        <h5 class="card-title">{{ $craftsman->name }}</h5>
                        <p class="mb-3 text-muted">{{ $craftsman->expertise }}</p>
                        <div class="social-links">
                            <a href="{{ $craftsman->instagram }}" class="btn btn-light btn-sm me-2">
                                <i class="bi bi-instagram"></i>
                            </a>
                            <a href="{{ $craftsman->facebook }}" class="btn btn-light btn-sm me-2">
                                <i class="bi bi-facebook"></i>
                            </a>
                            <a href="https://wa.me/{{ $craftsman->phone }}" class="btn btn-light btn-sm">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Workshop Section -->
<section class="py-5 workshop-section bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="mb-4 col-lg-6 mb-lg-0">
                <img src="{{ asset('images/creative-economy/workshop.jpg') }}"
                     class="shadow img-fluid rounded-3"
                     alt="Workshop">
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4 section-title">Workshop & Pelatihan</h2>
                <p class="mb-4 text-muted">Ikuti berbagai workshop menarik dan pelajari langsung dari para pengrajin ahli Muna Barat.</p>

                <div class="mb-4 workshop-features">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="feature-icon me-3">
                            <i class="mb-0 bi bi-people-fill text-primary h4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Pengajar Berpengalaman</h5>
                            <p class="mb-0 text-muted small">Dibimbing langsung oleh pengrajin ahli</p>
                        </div>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <div class="feature-icon me-3">
                            <i class="mb-0 bi bi-calendar2-check text-primary h4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Jadwal Fleksibel</h5>
                            <p class="mb-0 text-muted small">Pilih waktu yang sesuai dengan Anda</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="feature-icon me-3">
                            <i class="mb-0 bi bi-award-fill text-primary h4"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Sertifikat</h5>
                            <p class="mb-0 text-muted small">Dapatkan sertifikat setelah menyelesaikan workshop</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('workshops.index') }}" class="btn btn-primary">
                    Lihat Jadwal Workshop
                </a>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
/* Hero Section */
.hero-section {
    position: relative;
    margin-top: -2rem;
}

.hero-image {
    position: relative;
    background-size: cover;
    background-position: center;
    min-height: 75vh;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
}

/* Category Card */
.category-card:hover .card {
    transform: translateY(-5px);
}

.category-card .card {
    transition: transform 0.3s ease;
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

/* Craftsman Card */
.craftsman-card {
    transition: transform 0.3s ease;
}

.craftsman-card:hover {
    transform: translateY(-5px);
}

/* Workshop Features */
.feature-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(var(--bs-primary-rgb), 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Section Titles */
.section-title {
    position: relative;
    padding-bottom: 1rem;
    margin-bottom: 1rem;
}

.section-title::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 3px;
    background: var(--bs-primary);
}

.text-center .section-title::after {
    left: 50%;
    transform: translateX(-50%);
}
</style>
@endpush

@push('scripts')
<script>
// Filter products
document.querySelectorAll('[data-filter]').forEach(button => {
    button.addEventListener('click', function() {
        const filter = this.dataset.filter;

        // Update active state
        document.querySelectorAll('[data-filter]').forEach(btn => {
            btn.classList.remove('active');
        });
        this.classList.add('active');

        // Filter products
        document.querySelectorAll('.product-card').forEach(card => {
            if (filter === 'all' || card.dataset.category === filter) {
                card.closest('.col-md-6').style.display = 'block';
            } else {
                card.closest('.col-md-6').style.display = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection
