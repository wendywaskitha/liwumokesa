@extends('layouts.landing')

@section('title', $event->name . ' - Event & Festival Muna Barat')

@section('content')
    <!-- Hero Section -->
    <section class="position-relative">
        @if ($event->featured_image)
            <img src="{{ asset('storage/' . $event->featured_image) }}" alt="{{ $event->name }}" class="w-100"
                style="height: 60vh; object-fit: cover;">
        @else
            <div class="bg-light w-100 d-flex align-items-center justify-content-center" style="height: 60vh;">
                <div class="text-center text-muted">
                    <i class="bi bi-calendar-event display-1"></i>
                    <p class="mt-2">Gambar belum tersedia</p>
                </div>
            </div>
        @endif

        <!-- Overlay gradient -->
        <div class="top-0 position-absolute start-0 w-100 h-100"
            style="background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.7));">
        </div>

        <!-- Content overlay -->
        <div class="bottom-0 p-4 text-white position-absolute start-0 w-100">
            <div class="container">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <h1 class="mb-2 display-4 fw-bold">{{ $event->name }}</h1>
                        <div class="flex-wrap gap-3 d-flex align-items-center">
                            <div>
                                <i class="bi bi-calendar me-2"></i>
                                {{ $event->start_date->format('d M Y H:i') }}
                                @if ($event->end_date)
                                    - {{ $event->end_date->format('d M Y H:i') }}
                                @endif
                            </div>
                            <div>
                                <i class="bi bi-geo-alt me-2"></i>
                                {{ $event->location }}
                                @if ($event->district)
                                    <span class="ms-1">({{ $event->district->name }})</span>
                                @endif
                            </div>
                            @if ($event->organizer)
                                <div>
                                    <i class="bi bi-people me-2"></i>
                                    {{ $event->organizer }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 col-lg-4 text-lg-end mt-lg-0">
                        <button class="btn btn-outline-light" onclick="window.history.back()">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Description -->
                    <div class="mb-4 border-0 shadow-sm card rounded-3">
                        <div class="card-body">
                            <h2 class="mb-4 h5">Tentang Event</h2>
                            <div class="prose">
                                {!! $event->description !!}
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Info -->
                    @if ($event->schedule_info)
                        <div class="mb-4 border-0 shadow-sm card rounded-3">
                            <div class="card-body">
                                <h2 class="mb-4 h5">
                                    <i class="bi bi-calendar-week text-primary me-2"></i>
                                    Jadwal Acara
                                </h2>

                                <div class="schedule-timeline">
                                    @php
                                        // Decode schedule info
                                        $scheduleInfo = is_string($event->schedule_info)
                                            ? json_decode($event->schedule_info, true)
                                            : $event->schedule_info;

                                        // Jika schedule_info bukan array atau kosong, buat array default
                                        if (!is_array($scheduleInfo) || empty($scheduleInfo)) {
                                            $scheduleInfo = [
                                                [
                                                    'time' => $event->start_date->format('H:i'),
                                                    'title' => 'Pembukaan Acara',
                                                    'description' =>
                                                        $event->schedule_info ?? 'Pembukaan dan registrasi peserta',
                                                ],
                                            ];
                                        }
                                    @endphp

                                    <div class="schedule-list">
                                        @foreach ($scheduleInfo as $schedule)
                                            <div class="mb-4 schedule-item position-relative">
                                                <!-- Timeline dot -->
                                                <div class="timeline-dot"></div>

                                                <!-- Schedule content -->
                                                <div class="p-3 bg-white shadow-sm schedule-content rounded-3 ms-4">
                                                    <div class="mb-2 d-flex align-items-center">
                                                        <span class="badge bg-primary me-3">
                                                            {{ isset($schedule['time']) ? $schedule['time'] : $event->start_date->format('H:i') }}
                                                        </span>
                                                        <h4 class="mb-0 h6">
                                                            {{ isset($schedule['title']) ? $schedule['title'] : 'Agenda' }}
                                                        </h4>
                                                    </div>

                                                    @if (!empty($schedule['description']))
                                                        <p class="mb-2 text-muted small">
                                                            {{ $schedule['description'] }}
                                                        </p>
                                                    @endif

                                                    @if (!empty($schedule['items']))
                                                        <ul class="mb-0 list-unstyled">
                                                            @foreach ($schedule['items'] as $item)
                                                                <li class="mb-1 d-flex align-items-center text-muted small">
                                                                    <i class="bi bi-check2 text-success me-2"></i>
                                                                    {{ $item }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Gallery -->
                    @if ($event->galleries->isNotEmpty())
                        <div class="mb-4 border-0 shadow-sm card rounded-3">
                            <div class="card-body">
                                <h2 class="mb-4 h5">Galeri</h2>
                                <div class="row g-3">
                                    @foreach ($event->galleries as $gallery)
                                        <div class="col-6 col-md-4">
                                            <a href="{{ asset('storage/' . $gallery->file_path) }}"
                                                data-fslightbox="gallery">
                                                <img src="{{ asset('storage/' . $gallery->file_path) }}"
                                                    alt="{{ $gallery->caption ?? $event->name }}"
                                                    class="rounded img-fluid w-100"
                                                    style="height: 160px; object-fit: cover;">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Event Info Card -->
                    <div class="mb-4 border-0 shadow-sm card rounded-3 sticky-top" style="top: 2rem;">
                        <div class="card-body">
                            <h5 class="mb-4">Informasi Event</h5>

                            <!-- Price -->
                            <div class="mb-4">
                                @if ($event->is_free)
                                    <h3 class="mb-0 text-success">Gratis</h3>
                                @else
                                    <h3 class="mb-0 text-primary">
                                        Rp {{ number_format($event->ticket_price, 0, ',', '.') }}
                                    </h3>
                                @endif
                                <small class="text-muted">per tiket</small>
                            </div>

                            <!-- Features -->
                            <div class="mb-4">
                                @if ($event->capacity)
                                    <div class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-people text-primary me-2"></i>
                                        <span>Kapasitas: {{ number_format($event->capacity) }} orang</span>
                                    </div>
                                @endif
                                @if ($event->facilities)
                                    <div class="mb-2 d-flex align-items-center">
                                        <i class="bi bi-check-circle text-primary me-2"></i>
                                        <span>{{ $event->facilities }}</span>
                                    </div>
                                @endif
                                @if ($event->contact_person)
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person text-primary me-2"></i>
                                        <span>{{ $event->contact_person }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Registration Status -->
                            @if ($event->end_date->isPast())
                                <div class="mb-4 alert alert-secondary">
                                    Event ini telah berakhir
                                </div>
                            @else
                                @if ($userRegistered)
                                    <div class="mb-4 alert alert-success">
                                        Anda telah terdaftar pada event ini
                                    </div>
                                @else
                                    <!-- Registration Form -->
                                    <form action="{{ url('/events/' . $event->id . '/register') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="text" name="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                placeholder="Nama Lengkap"
                                                value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Email" value="{{ old('email', auth()->user()->email ?? '') }}"
                                                required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <input type="tel" name="phone"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                placeholder="No. Telepon"
                                                value="{{ old('phone', auth()->user()->phone ?? '') }}" required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-calendar-check me-2"></i>
                                                Daftar Sekarang
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            @endif

                            <!-- Contact Button -->
                            @if ($event->contact_phone)
                                <div class="mt-3 d-grid">
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $event->contact_phone) }}?text=Halo, saya tertarik dengan event {{ $event->name }}"
                                        class="btn btn-success" target="_blank">
                                        <i class="bi bi-whatsapp me-2"></i>
                                        Hubungi via WhatsApp
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Events -->
            @if ($relatedEvents->isNotEmpty())
                <div class="mt-5">
                    <h2 class="mb-4 h4">Event Lainnya</h2>
                    <div class="row g-4">
                        @foreach ($relatedEvents as $relatedEvent)
                            <div class="col-md-4">
                                <div class="border-0 shadow-sm card h-100">
                                    @if ($relatedEvent->featured_image)
                                        <img src="{{ asset('storage/' . $relatedEvent->featured_image) }}"
                                            class="card-img-top" alt="{{ $relatedEvent->name }}"
                                            style="height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $relatedEvent->name }}</h5>
                                        <p class="card-text text-muted">
                                            <i class="bi bi-calendar me-2"></i>
                                            {{ $relatedEvent->start_date->format('d M Y') }}
                                        </p>
                                        <a href="{{ url('/events/' . $relatedEvent->slug) }}"
                                            class="btn btn-outline-primary">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fslightbox@3.3.1/index.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <style>
        .schedule-timeline {
            position: relative;
        }

        .day-events {
            position: relative;
        }

        .day-events::before {
            content: '';
            position: absolute;
            left: 0.5rem;
            top: 0;
            height: 100%;
            width: 2px;
            background: var(--bs-primary);
            opacity: 0.2;
        }

        .event-item {
            position: relative;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 1rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: #fff;
            border: 2px solid var(--bs-primary);
            z-index: 1;
        }

        .event-content {
            position: relative;
            transition: all 0.3s ease;
        }

        .event-item:hover .event-content {
            transform: translateX(5px);
        }

        .event-item:hover .timeline-dot {
            background: var(--bs-primary);
            transform: scale(1.2);
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .day-events {
                margin-left: 1rem !important;
            }

            .event-content {
                margin-left: 1rem !important;
            }

            .timeline-dot {
                width: 0.75rem;
                height: 0.75rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fslightbox@3.3.1/index.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
@endpush
