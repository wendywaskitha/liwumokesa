{{-- resources/views/landing/partials/package-booking-card.blade.php --}}
<div class="mb-4 border-0 shadow-sm card rounded-3">
    <div class="card-body">
        <h5 class="mb-4">Informasi Pemesanan</h5>

        <!-- Price -->
        <div class="mb-4">
            @if ($package->discount_price)
                <small class="text-decoration-line-through text-muted">
                    {{ $package->price_formatted }}
                </small>
                <div class="d-flex align-items-center">
                    <h3 class="mb-0 text-primary">
                        {{ $package->discount_price_formatted }}
                    </h3>
                    <span class="badge bg-danger ms-2">
                        -{{ $package->discount_percentage }}%
                    </span>
                </div>
            @else
                <h3 class="mb-0 text-primary">
                    {{ $package->price_formatted }}
                </h3>
            @endif
            <small class="text-muted">per orang</small>
        </div>

        <!-- Features -->
        <div class="mb-4">
            <div class="mb-2 d-flex align-items-center">
                <i class="bi bi-clock text-primary me-2"></i>
                <span>{{ $package->duration_text }}</span>
            </div>
            <div class="mb-2 d-flex align-items-center">
                <i class="bi bi-people text-primary me-2"></i>
                <span>{{ $package->min_participants }}-{{ $package->max_participants }} Orang</span>
            </div>
            @if ($package->meeting_point)
                <div class="d-flex align-items-center">
                    <i class="bi bi-geo-alt text-primary me-2"></i>
                    <span>{{ $package->meeting_point }}</span>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="gap-2 mb-4 d-grid">
            @auth
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#bookingModal{{ $package->id }}">
                    <i class="bi bi-calendar-check me-2"></i>
                    Pesan Sekarang
                </button>

                <!-- Booking Modal -->
                <div class="modal fade" id="bookingModal{{ $package->id }}" tabindex="-1"
                    aria-labelledby="bookingModalLabel{{ $package->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bookingModalLabel{{ $package->id }}">Pesan Paket Wisata</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('tourist.bookings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="travel_package_id" value="{{ $package->id }}">

                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Kunjungan</label>
                                        <input type="date"
                                            class="form-control @error('booking_date') is-invalid @enderror"
                                            name="booking_date" min="{{ date('Y-m-d') }}" required>
                                        @error('booking_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Peserta</label>
                                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                            name="quantity" min="1" value="1" required>
                                        @error('quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Catatan (opsional)</label>
                                        <textarea class="form-control" name="notes" rows="3"></textarea>
                                    </div>

                                    <!-- Package Details -->
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Detail Paket:</h6>
                                            <p class="mb-1">{{ $package->name }}</p>
                                            <p class="mb-1">Harga: Rp
                                                {{ number_format($package->price, 0, ',', '.') }}/orang</p>
                                            <small class="text-muted">{{ $package->duration }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Lanjutkan Pemesanan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    <i class="bi bi-person me-2"></i>
                    Login untuk Memesan
                </a>
            @endauth

            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $package->contact_phone ?? '') }}?text=Halo, saya tertarik dengan paket wisata {{ $package->name }}"
                class="btn btn-success" target="_blank">
                <i class="bi bi-whatsapp me-2"></i>
                Hubungi via WhatsApp
            </a>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize all modals
                    var modals = document.querySelectorAll('.modal');
                    modals.forEach(function(modal) {
                        new bootstrap.Modal(modal);
                    });
                });
            </script>
        @endpush



        <!-- Tour Guide Info -->
        @if ($package->tourGuide)
            <div class="pt-4 mb-4 border-top">
                <h6 class="mb-3">Informasi Pemandu</h6>
                <div class="d-flex align-items-center">
                    @if ($package->tourGuide->photo)
                        <img src="{{ asset('storage/' . $package->tourGuide->photo) }}"
                            alt="{{ $package->tourGuide->name }}" class="rounded-circle" width="50" height="50"
                            style="object-fit: cover;">
                    @else
                        <div class="text-white rounded-circle bg-primary d-flex align-items-center justify-content-center"
                            style="width: 50px; height: 50px;">
                            <i class="bi bi-person"></i>
                        </div>
                    @endif
                    <div class="ms-3">
                        <h6 class="mb-1">{{ $package->tourGuide->name }}</h6>
                        @if ($package->tourGuide->languages)
                            @php
                                // Konversi string JSON atau array ke format yang sesuai
                                $languages = is_string($package->tourGuide->languages)
                                    ? json_decode($package->tourGuide->languages, true)
                                    : $package->tourGuide->languages;

                                // Jika hasil adalah array, gabungkan dengan koma
                                $languageText = is_array($languages) ? implode(', ', $languages) : $languages;
                            @endphp
                            <small class="text-muted">
                                <i class="bi bi-translate me-1"></i>
                                {{ $languageText }}
                            </small>
                        @endif
                    </div>
                </div>
                @if ($package->tourGuide->description)
                    <p class="mt-2 mb-0 text-muted small">
                        {{ $package->tourGuide->description }}
                    </p>
                @endif
            </div>
        @endif


        <!-- Gallery Preview -->
        @if ($package->galleries->isNotEmpty())
            <div class="pt-4 border-top">
                <h6 class="mb-3">Galeri Foto</h6>
                <div class="row g-2">
                    @foreach ($package->galleries->take(6) as $gallery)
                        <div class="col-4">
                            <a href="{{ asset('storage/' . $gallery->file_path) }}" data-fslightbox="gallery"
                                class="d-block">
                                <img src="{{ asset('storage/' . $gallery->file_path) }}"
                                    alt="{{ $gallery->caption ?? $package->name }}" class="rounded img-fluid w-100"
                                    style="height: 60px; object-fit: cover;">
                            </a>
                        </div>
                    @endforeach
                </div>
                @if ($package->galleries->count() > 6)
                    <button class="mt-2 btn btn-link btn-sm text-decoration-none w-100" data-bs-toggle="modal"
                        data-bs-target="#galleryModal">
                        Lihat {{ $package->galleries->count() - 6 }} foto lainnya
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <!-- FsLightbox -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/index.min.js"></script>
@endpush
