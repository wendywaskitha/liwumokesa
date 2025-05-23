@extends('layouts.tourist-dashboard')

@section('title', 'Rencana Perjalanan')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Rencana Perjalanan</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createItineraryModal">
                <i class="bi bi-plus-lg me-1"></i>Buat Rencana
            </button>
        </div>

        <!-- Itinerary List -->
        <div class="row g-4">
            @forelse($itineraries as $itinerary)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <!-- Itinerary Header -->
                            <div class="mb-3 d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1 card-title">{{ $itinerary->name }}</h5>
                                    <div class="text-muted small">
                                        <i class="bi bi-calendar-event me-1"></i>
                                        {{ $itinerary->start_date->format('d M Y') }} -
                                        {{ $itinerary->end_date->format('d M Y') }}
                                        <span class="ms-2">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $itinerary->start_date->diffInDays($itinerary->end_date) + 1 }} hari
                                        </span>
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <button class="p-0 btn btn-link text-dark" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="{{ route('tourist.itinerary.show', $itinerary) }}"
                                                class="dropdown-item">
                                                <i class="bi bi-eye me-2"></i>Detail
                                            </a>
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editItineraryModal{{ $itinerary->id }}">
                                                <i class="bi bi-pencil me-2"></i>Edit
                                            </button>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <form action="{{ route('tourist.itinerary.destroy', $itinerary) }}"
                                                method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus rencana ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i>Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Itinerary Content -->
                            @if ($itinerary->notes)
                                <div class="mb-4 alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <span id="itineraryNotes">{{ $itinerary->notes }}</span>
                                </div>
                            @endif

                            <!-- Itinerary Actions -->
                            <div class="gap-2 d-flex">
                                <a href="{{ route('tourist.itinerary.show', $itinerary) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Lihat Detail
                                </a>
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editItineraryModal{{ $itinerary->id }}">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Modal -->
                @include('tourist.itinerary.partials.edit-modal', ['itinerary' => $itinerary])
            @empty
                <div class="col-12">
                    <div class="py-5 text-center">
                        <img src="{{ asset('images/illustrations/empty-itinerary.svg') }}" alt="No Itineraries"
                            class="mb-3" style="max-width: 200px">
                        <h5>Belum ada rencana perjalanan</h5>
                        <p class="text-muted">
                            Mulai rencanakan perjalanan Anda ke Muna Barat!
                        </p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#createItineraryModal">
                            <i class="bi bi-plus-lg me-1"></i>Buat Rencana
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

    <!-- Create Modal -->
    @include('tourist.itinerary.partials.create-modal')
@endsection
