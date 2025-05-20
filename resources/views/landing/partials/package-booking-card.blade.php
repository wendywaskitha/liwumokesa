{{-- resources/views/landing/partials/package-booking-card.blade.php --}}
<div class="border-0 shadow-sm card">
    <div class="card-body">
        <!-- Package Price -->
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Rp {{ number_format($package->price, 0, ',', '.') }}</h5>
                <small class="text-muted">/orang</small>
            </div>
            <span class="badge bg-primary">{{ $package->duration_text }}</span>
        </div>

        <!-- Quick Info -->
        <div class="mb-4">
            <div class="mb-2 d-flex align-items-center">
                <i class="bi bi-people me-2 text-primary"></i>
                <span>{{ $package->min_participants ?? 1 }}-{{ $package->max_participants ?? 10 }} orang</span>
            </div>
            <div class="mb-2 d-flex align-items-center">
                <i class="bi bi-calendar-check me-2 text-primary"></i>
                <span>Tersedia setiap hari</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="bi bi-clock me-2 text-primary"></i>
                <span>{{ $package->duration_text }}</span>
            </div>
        </div>

        <!-- Booking Buttons -->
        <div class="gap-2 mb-4 d-grid">
            @auth
                <button type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#bookingModal{{ $package->id }}"
                        aria-controls="bookingModal{{ $package->id }}">
                    <i class="bi bi-calendar-check me-2"></i>
                    Pesan Sekarang
                </button>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="bi bi-person me-2"></i>
                    Login untuk Memesan
                </a>
            @endauth

            <!-- WhatsApp button -->
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $package->contact_phone ?? '') }}"
                class="btn btn-success" target="_blank">
                <i class="bi bi-whatsapp me-2"></i>
                Hubungi via WhatsApp
            </a>
        </div>

        <!-- Facilities -->
        @if ($package->facilities)
            <div class="facilities">
                <h6 class="mb-3">Fasilitas:</h6>
                <div class="row g-2">
                    @foreach ($package->facilities as $facility)
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span>{{ $facility }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@once
    @include('landing.partials.modals.booking-modal')
@endonce
