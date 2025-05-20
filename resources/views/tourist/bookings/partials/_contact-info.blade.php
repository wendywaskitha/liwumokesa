{{-- resources/views/tourist/bookings/partials/_contact-info.blade.php --}}

<!-- Contact Details -->
<div class="mb-4">
    <!-- District Info -->
    @if($booking->travelPackage->district)
        <div class="mb-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-light rounded-circle">
                        <i class="bi bi-geo-alt text-primary"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <small class="text-muted d-block">Lokasi</small>
                    <span>{{ $booking->travelPackage->district->name }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Duration -->
    <div class="mb-3">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <div class="p-2 bg-light rounded-circle">
                    <i class="bi bi-clock text-primary"></i>
                </div>
            </div>
            <div class="flex-grow-1 ms-3">
                <small class="text-muted d-block">Durasi</small>
                <span>{{ $booking->travelPackage->duration }}</span>
            </div>
        </div>
    </div>

    <!-- Meeting Point -->
    @if($booking->travelPackage->meeting_point)
        <div class="mb-3">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <div class="p-2 bg-light rounded-circle">
                        <i class="bi bi-pin-map text-primary"></i>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <small class="text-muted d-block">Titik Kumpul</small>
                    <span>{{ $booking->travelPackage->meeting_point }}</span>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Contact Buttons -->
<div class="gap-2 d-grid">
    <a href="https://wa.me/6282346338821"
       class="btn btn-success"
       target="_blank">
        <i class="bi bi-whatsapp me-2"></i>
        Hubungi Customer Service
    </a>

    <a href="{{ route('contact') }}"
       class="btn btn-outline-primary">
        <i class="bi bi-question-circle me-2"></i>
        Pusat Bantuan
    </a>
</div>

<!-- Additional Info -->
<div class="mt-4">
    <small class="text-muted">
        <i class="bi bi-info-circle me-1"></i>
        Butuh bantuan? Customer service kami siap membantu 24/7
    </small>
</div>
