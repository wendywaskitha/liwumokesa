<section class="py-5 upcoming-events">
    <div class="container">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <h2 class="section-title">Event Mendatang</h2>
            <a href="{{ route('landing.events') }}" class="btn btn-link text-primary">Lihat Semua</a>
        </div>

        <div class="row g-4">
            @foreach(\App\Models\Event::where('start_date', '>=', now())->orderBy('start_date')->limit(4)->get() as $event)
                <div class="col-md-6 col-lg-3">
                    <div class="border-0 shadow-sm card h-100 event-card">
                        <div class="position-relative">
                            @if($event->featured_image)
                                <img src="{{ asset('storage/' . $event->featured_image) }}"
                                    class="card-img-top"
                                    alt="{{ $event->name }}"
                                    style="height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="height: 180px;">
                                    <i class="bi bi-calendar-event text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                            <div class="bottom-0 p-3 text-white position-absolute start-0 w-100"
                                style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                                <span class="badge bg-danger">
                                    {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $event->name }}</h5>
                            <p class="mb-2 card-text text-muted small">
                                <i class="bi bi-geo-alt"></i> {{ $event->location }}
                            </p>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                @if($event->is_free)
                                    <span class="text-success fw-bold">Gratis</span>
                                @else
                                    <span class="text-primary fw-bold">
                                        Rp {{ number_format($event->ticket_price, 0, ',', '.') }}
                                    </span>
                                @endif
                                <a href="{{ route('landing.events.show', $event->slug) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
