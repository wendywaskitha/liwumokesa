@extends('layouts.landing')

@section('title', 'Event & Festival - Pariwisata Muna Barat')

@section('content')
    <!-- Hero Section -->
    <section class="py-5 text-white bg-primary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold">Event & Festival</h1>
                    <p class="mb-0 lead">Temukan berbagai event dan festival menarik di Kabupaten Muna Barat</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="py-4 bg-light">
        <div class="container">
            <form action="{{ route('landing.events') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="q" class="form-control" placeholder="Cari event..."
                            value="{{ request('q') }}">
                    </div>
                    <div class="col-md-5">
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-2"></i>
                            Filter
                        </button>
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="mt-3 row">
                    <div class="col-12">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="sort" id="sort_upcoming" value="upcoming"
                                {{ request('sort', 'upcoming') == 'upcoming' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="sort_upcoming">Akan Datang</label>

                            <input type="radio" class="btn-check" name="sort" id="sort_name_asc" value="name_asc"
                                {{ request('sort') == 'name_asc' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="sort_name_asc">A-Z</label>

                            <input type="radio" class="btn-check" name="sort" id="sort_popular" value="popular"
                                {{ request('sort') == 'popular' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="sort_popular">Terpopuler</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Events List -->
    <section class="py-5">
        <div class="container">
            @if ($events->isEmpty())
                <div class="py-5 text-center">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                    <h3 class="mt-3">Tidak Ada Event</h3>
                    <p class="text-muted">Tidak ada event yang sesuai dengan filter yang dipilih.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($events as $event)
                        <div class="col-md-6 col-lg-4">
                            <div class="border-0 shadow-sm card h-100">
                                <!-- Event Image -->
                                <div class="position-relative">
                                    @if ($event->featured_image)
                                        <img src="{{ asset('storage/' . $event->featured_image) }}" class="card-img-top"
                                            alt="{{ $event->name }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        <div class="bg-light" style="height: 200px;"></div>
                                    @endif

                                    <!-- Event Status Badge -->
                                    <div class="top-0 p-3 position-absolute start-0">
                                        @if ($event->start_date->isFuture())
                                            <span class="badge bg-primary">Akan Datang</span>
                                        @elseif($event->end_date->isPast())
                                            <span class="badge bg-secondary">Selesai</span>
                                        @else
                                            <span class="badge bg-success">Sedang Berlangsung</span>
                                        @endif
                                    </div>

                                    <!-- Free/Paid Badge -->
                                    <div class="top-0 p-3 position-absolute end-0">
                                        @if ($event->is_free)
                                            <span class="badge bg-success">Gratis</span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Rp {{ number_format($event->ticket_price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-body">
                                    <!-- Event Title -->
                                    <h5 class="mb-3 card-title">
                                        <a href="{{ route('landing.events.show', $event->slug) }}"
                                            class="text-decoration-none text-dark">
                                            {{ $event->name }}
                                        </a>
                                    </h5>

                                    <!-- Event Info -->
                                    <div class="mb-3">
                                        <div class="mb-2 d-flex align-items-center text-muted">
                                            <i class="bi bi-calendar me-2"></i>
                                            {{ $event->start_date->format('d M Y H:i') }}
                                        </div>
                                        <div class="mb-2 d-flex align-items-center text-muted">
                                            <i class="bi bi-geo-alt me-2"></i>
                                            {{ $event->location }}
                                        </div>
                                        @if ($event->organizer)
                                            <div class="d-flex align-items-center text-muted">
                                                <i class="bi bi-people me-2"></i>
                                                {{ $event->organizer }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Action Button -->
                                    <div class="d-grid">
                                        <a href="{{ route('landing.events.show', $event->slug) }}"
                                            class="btn btn-outline-primary">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    @if ($events->hasPages())
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                {{-- Previous Page Link --}}
                                @if ($events->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="bi bi-chevron-left"></i>
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $events->previousPageUrl() }}" rel="prev">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                                    @if ($page == $events->currentPage())
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
                                @if ($events->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $events->nextPageUrl() }}" rel="next">
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
            @endif
        </div>
    </section>

    <!-- Upcoming Events Calendar -->
    @if ($upcomingDates->isNotEmpty())
        <section class="py-5 bg-light">
            <div class="container">
                <h2 class="mb-4 h4">Event Mendatang</h2>
                <div class="row g-3">
                    @foreach ($upcomingDates as $date)
                        <div class="col-md-4 col-lg-2">
                            <a href="{{ route('landing.events', ['date' => $date['date']]) }}"
                                class="text-decoration-none">
                                <div class="text-center border-0 shadow-sm card h-100">
                                    <div class="card-body">
                                        <h5 class="mb-0">{{ $date['label'] }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@push('styles')
    <style>
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: var(--bs-primary);
            border: 1px solid var(--bs-primary);
            margin: 0 3px;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: var(--bs-primary);
            color: white;
            border-color: var(--bs-primary);
        }

        .page-item.active .page-link {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .page-item.disabled .page-link {
            color: #6c757d;
            border-color: #dee2e6;
        }

        @media (max-width: 768px) {
            .page-link {
                padding: 6px 12px;
                font-size: 14px;
            }
        }
    </style>
@endpush
