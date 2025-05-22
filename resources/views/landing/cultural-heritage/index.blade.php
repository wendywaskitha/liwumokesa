@extends('layouts.landing')

@section('title', 'Warisan Budaya - Muna Barat')

@section('content')
    <!-- Page Header -->
    <section class="py-5 text-white bg-primary">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold">Warisan Budaya</h1>
                    <p class="lead">Jelajahi kekayaan warisan budaya Muna Barat yang menakjubkan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="py-5">
        <div class="container">
            <!-- Search & Filter -->
            <div class="mb-4 row g-3">
                <div class="col-md-4">
                    <select class="form-select" id="categoryFilter">
                        <option value="">Semua Kategori</option>
                        <option value="tangible">Warisan Budaya Benda</option>
                        <option value="intangible">Warisan Budaya Tak Benda</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="districtFilter">
                        <option value="">Semua Kecamatan</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput" placeholder="Cari warisan budaya...">
                        <button class="btn btn-primary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cultural Heritage List -->
            <div class="row g-4" id="culturalHeritageList">
                @forelse($culturalHeritages as $heritage)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 hover-shadow">
                            <div class="position-relative">
                                @if ($heritage->featured_image)
                                    <img src="{{ Storage::url($heritage->featured_image) }}" class="card-img-top"
                                        alt="{{ $heritage->name }}" style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="top-0 p-3 position-absolute start-0">
                                    <span class="badge bg-primary">{{ $heritage->type }}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="mb-1 card-title">{{ $heritage->name }}</h5>
                                <div class="mb-2 small text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $heritage->district->name }}
                                </div>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($heritage->description, 100) }}
                                </p>
                            </div>
                            <div class="bg-white card-footer border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('landing.cultural-heritage.show', $heritage->slug) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        Lihat Detail
                                    </a>
                                    @if ($heritage->reviews_count > 0)
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-star-fill text-warning me-1"></i>
                                            <span class="small">
                                                {{ number_format($heritage->average_rating, 1) }}
                                                ({{ $heritage->reviews_count }})
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center alert alert-info">
                            Belum ada data warisan budaya yang tersedia.
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                @if ($culturalHeritages->hasPages())
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            {{-- Previous Page Link --}}
                            @if ($culturalHeritages->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="bi bi-chevron-left"></i>
                                    </span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $culturalHeritages->previousPageUrl() }}" rel="prev">
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($culturalHeritages->getUrlRange(1, $culturalHeritages->lastPage()) as $page => $url)
                                @if ($page == $culturalHeritages->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($culturalHeritages->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $culturalHeritages->nextPageUrl() }}" rel="next">
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pagination {
            --bs-pagination-color: var(--bs-primary);
            --bs-pagination-hover-color: var(--bs-primary);
            --bs-pagination-focus-color: var(--bs-primary);
            --bs-pagination-active-bg: var(--bs-primary);
            --bs-pagination-active-border-color: var(--bs-primary);
        }

        .page-link {
            border-radius: 0.375rem;
            margin: 0 0.25rem;
        }

        .page-item.active .page-link {
            font-weight: 600;
        }

        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilter = document.getElementById('categoryFilter');
            const districtFilter = document.getElementById('districtFilter');
            const searchInput = document.getElementById('searchInput');

            function applyFilters() {
                const params = new URLSearchParams(window.location.search);

                if (categoryFilter.value) params.set('category', categoryFilter.value);
                if (districtFilter.value) params.set('district', districtFilter.value);
                if (searchInput.value) params.set('search', searchInput.value);

                window.location.search = params.toString();
            }

            categoryFilter.addEventListener('change', applyFilters);
            districtFilter.addEventListener('change', applyFilters);

            // Apply search on enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') applyFilters();
            });
        });
    </script>
@endpush
