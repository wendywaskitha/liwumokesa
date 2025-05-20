{{-- resources/views/landing/partials/modals/booking-modal.blade.php --}}
<div class="modal fade"
     id="bookingModal{{ $package->id }}"
     tabindex="-1"
     role="dialog"
     aria-labelledby="bookingModalLabel{{ $package->id }}">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel{{ $package->id }}">
                    Pesan Paket Wisata
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>
            <form action="{{ route('tourist.bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="travel_package_id" value="{{ $package->id }}">

                <div class="modal-body">
                    <!-- Booking Date -->
                    <div class="mb-3">
                        <label class="form-label">Tanggal Kunjungan</label>
                        <input type="date"
                               class="form-control @error('booking_date') is-invalid @enderror"
                               name="booking_date"
                               min="{{ date('Y-m-d') }}"
                               required>
                        @error('booking_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Pilih tanggal kunjungan yang diinginkan
                        </div>
                    </div>

                    <!-- Number of People -->
                    <div class="mb-3">
                        <label class="form-label">Jumlah Peserta</label>
                        <input type="number"
                               class="form-control @error('quantity') is-invalid @enderror"
                               name="quantity"
                               min="{{ $package->min_participants ?? 1 }}"
                               max="{{ $package->max_participants ?? 10 }}"
                               value="{{ $package->min_participants ?? 1 }}"
                               required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Minimal {{ $package->min_participants ?? 1 }} orang,
                            maksimal {{ $package->max_participants ?? 10 }} orang
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="form-label">Catatan Tambahan (Opsional)</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  name="notes"
                                  rows="3"
                                  placeholder="Tambahkan permintaan khusus atau catatan untuk pemesanan Anda"></textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Package Summary -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="mb-3 card-title">Ringkasan Paket:</h6>
                            <div class="mb-2">
                                <strong>{{ $package->name }}</strong>
                            </div>
                            <div class="mb-2 d-flex justify-content-between">
                                <span>Harga per orang:</span>
                                <span>Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between">
                                <span>Durasi:</span>
                                <span>{{ $package->duration_text }}</span>
                            </div>
                            @if($package->inclusions)
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Termasuk: {{ implode(', ', array_slice($package->inclusions, 0, 3)) }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-2"></i>
                        Batal
                    </button>
                    <button type="submit"
                            class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>
                        Lanjutkan Pemesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
