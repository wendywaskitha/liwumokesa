{{-- resources/views/landing/cultural-heritage/index.blade.php --}}
@extends('layouts.landing')

@section('content')
<div class="hero-inner" style="background-image: url('{{ asset('images/hero/cultural.jpg') }}');">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1>Warisan Budaya</h1>
                    <p>Jelajahi kekayaan warisan budaya Muna Barat yang menakjubkan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="py-5 cultural-heritage-section">
    <div class="container">
        <!-- Filter dan Pencarian -->
        <div class="mb-4 row">
            <div class="col-md-8">
                <div class="gap-3 search-filter d-flex">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Semua Kategori</option>
                        <option value="tangible">Warisan Budaya Benda</option>
                        <option value="intangible">Warisan Budaya Tak Benda</option>
                    </select>
                    <select class="form-select" id="districtFilter">
                        <option value="">Semua Kecamatan</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="search-box">
                    <input type="text" class="form-control" placeholder="Cari warisan budaya...">
                </div>
            </div>
        </div>

        <!-- Daftar Warisan Budaya -->
        <div class="row g-4">
            @foreach($culturalHeritages as $heritage)
            <div class="col-md-6 col-lg-4">
                <div class="card heritage-card h-100">
                    <img src="{{ $heritage->featured_image }}" class="card-img-top" alt="{{ $heritage->name }}">
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
                            @if($heritage->opening_hours)
                            <div class="mb-2 d-flex align-items-center">
                                <i class="bi bi-clock me-2"></i>
                                <span>{{ $heritage->opening_hours }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-white card-footer border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('landing.cultural-heritage.show', $heritage->slug) }}" class="btn btn-outline-primary">Lihat Detail</a>
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

        <!-- Pagination -->
        <div class="mt-4 row">
            <div class="col-12">
                {{ $culturalHeritages->links() }}
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .heritage-card {
        transition: transform 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .heritage-card:hover {
        transform: translateY(-5px);
    }
    .heritage-card .card-img-top {
        height: 200px;
        object-fit: cover;
    }
    .heritage-info i {
        font-size: 1rem;
        color: #6c757d;
    }
</style>
@endpush

@push('scripts')
<script>
    // Filter functionality
    document.getElementById('categoryFilter').addEventListener('change', function() {
        // Implement filter logic
    });

    document.getElementById('districtFilter').addEventListener('change', function() {
        // Implement filter logic
    });
</script>
@endpush
