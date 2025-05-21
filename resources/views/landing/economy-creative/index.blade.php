@extends('layouts.landing')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="mb-4">
        <h2 class="mb-3">Ekonomi Kreatif Muna Barat</h2>
        <div class="row g-3">
            <!-- Category Pills -->
            <div class="col-12">
                <div class="flex-wrap gap-2 d-flex">
                    <a href="{{ route('economy-creative.index') }}"
                       class="btn btn-{{ request()->category ? 'outline-' : '' }}primary btn-sm">
                        Semua
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('economy-creative.index', ['category' => $category->slug]) }}"
                           class="btn btn-{{ request()->category === $category->slug ? 'primary' : 'outline-primary' }} btn-sm">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-6 col-md-3">
                <div class="border-0 shadow-sm card h-100 product-card">
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $product->featured_image) }}"
                             class="card-img-top"
                             alt="{{ $product->name }}"
                             style="height: 200px; object-fit: cover;">
                        @if($product->is_featured)
                            <div class="top-0 m-2 position-absolute start-0">
                                <span class="badge bg-danger">Unggulan</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="mb-1 card-title text-truncate">{{ $product->name }}</h6>
                        <p class="mb-2 text-muted small">{{ $product->category->name }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-primary">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <small class="text-muted">
                                <i class="bi bi-star-fill text-warning"></i>
                                {{ number_format($product->average_rating, 1) }}
                            </small>
                        </div>
                    </div>
                    <div class="bg-white border-0 card-footer">
                        <div class="d-grid">
                            <a href="{{ route('economy-creative.show', $product) }}"
                               class="btn btn-outline-primary btn-sm">
                                Lihat Detail
                            </a>
                        </div>
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

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>

@push('styles')
<style>
    .product-card {
        transition: transform 0.2s;
    }
    .product-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
@endsection
