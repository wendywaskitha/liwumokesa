{{-- resources/views/tourist/bookings/partials/_booking-details.blade.php --}}

<div class="mb-4 row">
    <div class="col-md-3">
        <img src="{{ asset('storage/' . $booking->travelPackage->featured_image) }}"
             class="rounded shadow-sm img-fluid"
             alt="{{ $booking->travelPackage->name }}"
             style="object-fit: cover; height: 120px; width: 100%;">
    </div>
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h5>{{ $booking->travelPackage->name }}</h5>
                <p class="mb-1 text-muted">
                    <i class="bi bi-ticket-perforated me-2"></i>
                    Kode Booking: {{ $booking->booking_code }}
                </p>
                <p class="mb-1">
                    <i class="bi bi-calendar me-2"></i>
                    {{ $booking->booking_date->format('d M Y') }}
                </p>
                <p class="mb-0">
                    <i class="bi bi-people me-2"></i>
                    {{ $booking->quantity }} orang
                </p>
            </div>
            <span class="badge bg-{{ $booking->booking_status === 'confirmed' ? 'success' : ($booking->booking_status === 'cancelled' ? 'danger' : 'warning') }}">
                {{ ucfirst($booking->booking_status) }}
            </span>
        </div>
    </div>
</div>
