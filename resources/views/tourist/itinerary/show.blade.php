@extends('layouts.tourist-dashboard')

@section('title', $itinerary->name)

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">{{ $itinerary->name }}</h4>
                <div class="text-muted">
                    <i class="bi bi-calendar-event me-1"></i>
                    {{ $itinerary->start_date->format('d M Y') }} - {{ $itinerary->end_date->format('d M Y') }}
                    <span class="ms-2">
                        <i class="bi bi-clock me-1"></i>
                        {{ $itinerary->start_date->diffInDays($itinerary->end_date) + 1 }} hari
                    </span>
                </div>
            </div>
            <div class="gap-2 d-flex">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Item
                </button>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#editItineraryModal{{ $itinerary->id }}">
                    <i class="bi bi-pencil me-1"></i>Edit
                </button>
            </div>
        </div>

        <!-- Notes -->
        @if ($itinerary->notes)
            <div class="mb-4 alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                {{ $itinerary->notes }}
            </div>
        @endif

        <!-- Estimated Budget -->
        @if ($itinerary->estimated_budget)
            <div class="mb-4 alert alert-warning">
                <i class="bi bi-wallet2 me-2"></i>
                Estimasi Budget: Rp {{ number_format($itinerary->estimated_budget, 0, ',', '.') }}
            </div>
        @endif

        <!-- Itinerary Items -->
        @php
            $days = $itinerary->itineraryItems->groupBy('day');
        @endphp

        <div class="row">
            <div class="col-lg-8">
                @forelse($days as $day => $items)
                    <div class="mb-4 card">
                        <div class="text-white card-header bg-primary">
                            <h5 class="mb-0">Hari {{ $day }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @foreach ($items as $item)
                                    <div class="pb-4 timeline-item">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="text-muted small">
                                                    {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} -
                                                    {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                                                </div>
                                                <h5 class="mb-1">{{ $item->itemable->name }}</h5>
                                                @if ($item->notes)
                                                    <p class="mb-2 small">{{ $item->notes }}</p>
                                                @endif
                                                @if ($item->estimated_cost)
                                                    <div class="small text-muted">
                                                        <i class="bi bi-wallet2 me-1"></i>
                                                        Rp {{ number_format($item->estimated_cost, 0, ',', '.') }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="dropdown">
                                                <button class="p-0 btn btn-link text-dark" data-bs-toggle="dropdown">
                                                    <i class="bi bi-three-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#editItemModal{{ $item->id }}">
                                                            <i class="bi bi-pencil me-2"></i>Edit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('tourist.itinerary.items.destroy', ['itinerary' => $itinerary->id, 'item' => $item->id]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus item ini?')">
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
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-5 text-center">
                        <i class="bi bi-calendar-x display-4 text-muted"></i>
                        <h5 class="mt-3">Belum ada item dalam itinerary</h5>
                        <p class="text-muted">
                            Mulai tambahkan destinasi, akomodasi, atau aktivitas ke dalam rencana perjalanan Anda!
                        </p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addItemModal">
                            <i class="bi bi-plus-lg me-1"></i>Tambah Item
                        </button>
                    </div>
                @endforelse
            </div>

            <!-- Map Preview -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 80px">
                    <div class="card-body">
                        <!-- Add map implementation here -->
                        <div id="map" style="height: 400px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Modals -->
    @include('tourist.itinerary.partials.edit-modal', ['itinerary' => $itinerary])
    @include('tourist.itinerary.partials.add-item-modal', ['itinerary' => $itinerary])
    @foreach ($itinerary->itineraryItems as $item)
        @include('tourist.itinerary.partials.edit-item-modal', ['item' => $item])
    @endforeach
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -34px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #0d6efd;
            border: 2px solid #fff;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Initialize map
        const map = L.map('map').setView([-4.8337, 122.6082], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

        // Add markers for each item
        const items = {!! json_encode(
            $itinerary->itineraryItems->map(function ($item) {
                return [
                    'name' => $item->itemable->name,
                    'lat' => $item->itemable->latitude,
                    'lng' => $item->itemable->longitude,
                    'day' => $item->day,
                ];
            }),
        ) !!};

        // Add markers to map
        if (items && items.length > 0) {
            items.forEach(item => {
                if (item.lat && item.lng) {
                    L.marker([item.lat, item.lng])
                        .bindPopup(`<b>Hari ${item.day}</b><br>${item.name}`)
                        .addTo(map);
                }
            });

            // Fit bounds if there are markers
            const bounds = items
                .filter(item => item.lat && item.lng)
                .map(item => [item.lat, item.lng]);

            if (bounds.length > 0) {
                map.fitBounds(bounds);
            }
        }
    </script>
@endpush
