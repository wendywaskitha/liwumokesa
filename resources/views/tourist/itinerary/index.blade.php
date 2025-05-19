@extends('layouts.tourist-dashboard')

@section('title', 'Rencana Perjalanan')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Rencana Perjalanan</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createItineraryModal">
            <i class="bi bi-plus-lg me-2"></i> Buat Rencana
        </button>
    </div>

    <!-- Itinerary List -->
    <div class="row g-4">
        @forelse($itineraries as $itinerary)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1 card-title">{{ $itinerary->name }}</h5>
                            <p class="mb-0 text-muted">
                                <i class="bi bi-calendar me-2"></i>
                                {{ $itinerary->start_date->format('d M Y') }}
                            </p>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <button class="dropdown-item"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editItineraryModal{{ $itinerary->id }}">
                                        <i class="bi bi-pencil me-2"></i> Edit
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item"
                                            onclick="confirmDelete('{{ $itinerary->id }}')">
                                        <i class="bi bi-trash me-2"></i> Hapus
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Travel Packages -->
                    <div class="mb-3">
                        @foreach($itinerary->travelPackages as $package)
                        <div class="mb-2 d-flex align-items-center">
                            <img src="{{ asset('storage/' . $package->image) }}"
                                 class="rounded me-2"
                                 style="width: 48px; height: 48px; object-fit: cover;"
                                 alt="{{ $package->name }}">
                            <div>
                                <h6 class="mb-0">{{ $package->name }}</h6>
                                <small class="text-muted">{{ $package->duration }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Notes -->
                    @if($itinerary->notes)
                    <div class="mb-3 alert alert-light">
                        <i class="bi bi-sticky me-2"></i>
                        {{ $itinerary->notes }}
                    </div>
                    @endif

                    <div class="d-grid">
                        <a href="{{ route('tourist.itinerary.show', $itinerary) }}"
                           class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="py-5 text-center">
                <img src="{{ asset('images/empty-itinerary.svg') }}"
                     alt="No Itineraries"
                     class="mb-3"
                     style="max-width: 200px">
                <h5>Belum ada rencana perjalanan</h5>
                <p class="text-muted">
                    Mulai rencanakan perjalanan Anda sekarang!
                </p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createItineraryModal">
                    Buat Rencana Perjalanan
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $itineraries->links() }}
    </div>
</div>

@include('tourist.itinerary.partials.create-modal')
@include('tourist.itinerary.partials.edit-modal')
@endsection
