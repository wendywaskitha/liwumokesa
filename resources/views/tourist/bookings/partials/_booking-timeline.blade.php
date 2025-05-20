{{-- resources/views/tourist/bookings/partials/_booking-timeline.blade.php --}}

<div class="mb-4 booking-timeline">
    <div class="d-flex justify-content-between">
        <div class="timeline-item {{ $booking->booking_status != 'cancelled' ? 'active' : '' }}">
            <div class="timeline-point"></div>
            <div class="timeline-content">
                <h6>Pemesanan</h6>
                <small>{{ $booking->created_at->format('d M Y H:i') }}</small>
            </div>
        </div>
        <div class="timeline-item {{ $booking->payment_status == 'paid' ? 'active' : '' }}">
            <div class="timeline-point"></div>
            <div class="timeline-content">
                <h6>Pembayaran</h6>
                <small>{{ $booking->payment_status == 'paid' ? $booking->updated_at->format('d M Y H:i') : '-' }}</small>
            </div>
        </div>
        <div class="timeline-item {{ $booking->booking_status == 'confirmed' ? 'active' : '' }}">
            <div class="timeline-point"></div>
            <div class="timeline-content">
                <h6>Konfirmasi</h6>
                <small>{{ $booking->booking_status == 'confirmed' ? $booking->updated_at->format('d M Y H:i') : '-' }}</small>
            </div>
        </div>
    </div>
</div>
