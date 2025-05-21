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

    <!-- Creative Economy Grid -->
    <div class="row g-4">
        @forelse($creativeEconomies as $creativeEconomy)
            <div class="col-6 col-md-3">
                <div class="border-0 shadow-sm card h-100 product-card">
                    <div class="position-relative">
                        @php
                            $pexelsImages = [
                                'handicraft' => 'https://images.pexels.com/photos/4553036/pexels-photo-4553036.jpeg',
                                'fashion' => 'https://images.pexels.com/photos/3735641/pexels-photo-3735641.jpeg',
                                'food' => 'https://images.pexels.com/photos/1640777/pexels-photo-1640777.jpeg',
                                'art' => 'https://images.pexels.com/photos/1509534/pexels-photo-1509534.jpeg'
                            ];
                            $defaultImage = $pexelsImages[$creativeEconomy->category->slug] ?? 'https://images.pexels.com/photos/3735641/pexels-photo-3735641.jpeg';
                        @endphp
                        <img src="{{ $creativeEconomy->featured_image ? asset('storage/' . $creativeEconomy->featured_image) : $defaultImage }}"
                             class="card-img-top"
                             alt="{{ $creativeEconomy->name }}"
                             style="height: 200px; object-fit: cover;">

                        @if($creativeEconomy->is_featured)
                            <div class="top-0 m-2 position-absolute start-0">
                                <span class="badge bg-danger">Unggulan</span>
                            </div>
                        @endif
                        @if($creativeEconomy->is_verified)
                            <div class="top-0 m-2 position-absolute end-0">
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Terverifikasi
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h6 class="mb-1 card-title text-truncate">{{ $creativeEconomy->name }}</h6>
                        <p class="mb-2 text-muted small">{{ $creativeEconomy->category->name }}</p>
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <span class="text-muted small">
                                <i class="bi bi-tag me-1"></i>
                                {{ $creativeEconomy->price_range_text }}
                            </span>
                            <small class="text-muted">
                                <i class="bi bi-star-fill text-warning"></i>
                                {{ number_format($creativeEconomy->average_rating, 1) }}
                            </small>
                        </div>
                        @if($creativeEconomy->has_workshop)
                            <div class="mb-2">
                                <span class="badge bg-info">
                                    <i class="bi bi-tools me-1"></i>
                                    Workshop Tersedia
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="bg-white border-0 card-footer">
                        <div class="d-grid">
                            <a href="{{ route('economy-creative.show', $creativeEconomy->slug) }}"
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
                    <img src="https://images.pexels.com/photos/3943746/pexels-photo-3943746.jpeg"
                         alt="Tidak ada UMKM"
                         class="mb-3 rounded"
                         style="max-width: 300px">
                    <h5>Belum ada UMKM</h5>
                    <p class="text-muted">
                        Data UMKM ekonomi kreatif akan segera ditambahkan
                    </p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $creativeEconomies->links() }}
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
    .card-img-top {
        transition: transform 0.3s;
    }
    .product-card:hover .card-img-top {
        transform: scale(1.05);
    }
</style>
@endpush
@endsection
