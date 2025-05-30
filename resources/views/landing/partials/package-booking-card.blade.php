<!-- Package Price & Info -->
<div class="mb-4 border-0 shadow-sm card">
    <div class="card-body">
        <!-- Price Header -->
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Rp {{ number_format($package->price, 0, ',', '.') }}</h5>
                <small class="text-muted">/orang</small>
            </div>
            <span class="badge bg-primary">{{ $package->duration }}</span>
        </div>

        <!-- Quick Info -->
        <div class="mb-3">
            <div class="mb-2 d-flex align-items-center">
                <i class="bi bi-people me-2 text-primary"></i>
                <span>{{ $package->min_participants ?? 1 }}-{{ $package->max_participants ?? 10 }} Orang</span>
            </div>
            @if($package->district)
                <div class="mb-2 d-flex align-items-center">
                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                    <span>{{ $package->district->name }}</span>
                </div>
            @endif
            <div class="d-flex align-items-center">
                <i class="bi bi-calendar-check me-2 text-primary"></i>
                <span>Tersedia setiap hari</span>
            </div>
        </div>

        <!-- Booking Buttons -->
        <div class="gap-2 d-grid">
            @auth
                <a href="{{ route('tourist.bookings.create', ['package' => $package->id]) }}"
                   class="btn btn-primary">
                    <i class="bi bi-calendar-check me-2"></i>
                    Pesan Sekarang
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="bi bi-person me-2"></i>
                    Login untuk Memesan
                </a>
            @endauth

            <!-- WhatsApp button -->
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $package->contact_phone ?? '') }}"
               class="btn btn-success"
               target="_blank">
                <i class="bi bi-whatsapp me-2"></i>
                Hubungi via WhatsApp
            </a>
        </div>
    </div>
</div>
