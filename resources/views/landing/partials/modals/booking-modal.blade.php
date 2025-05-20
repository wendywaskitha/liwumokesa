<div class="modal fade" id="bookingModal-{{ $package->id }}" tabindex="-1"
    aria-labelledby="bookingModalLabel-{{ $package->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingModalLabel-{{ $package->id }}">Pesan Paket Wisata</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('tourist.bookings.store') }}" method="POST">
                @csrf
                <input type="hidden" name="travel_package_id" value="{{ $package->id }}">

                <div class="modal-body">
                    <!-- Booking Date -->
                    <div class="mb-3">
                        <label class="form-label">Tanggal Kunjungan</label>
                        <input type="date" class="form-control @error('booking_date') is-invalid @enderror"
                            name="booking_date" min="{{ date('Y-m-d') }}" required>
                        @error('booking_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Number of People -->
                    <div class="mb-3">
                        <label class="form-label">Jumlah Peserta</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                            name="quantity" min="{{ $package->min_participants ?? 1 }}"
                            max="{{ $package->max_participants ?? 10 }}" value="{{ $package->min_participants ?? 1 }}"
                            required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea class="form-control" name="notes" rows="3"
                            placeholder="Tambahkan catatan khusus untuk pemesanan Anda"></textarea>
                    </div>

                    <!-- Package Details -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="mb-3 card-title">Detail Paket:</h6>
                            <p class="mb-1">{{ $package->name }}</p>
                            <p class="mb-1">Harga: Rp {{ number_format($package->price, 0, ',', '.') }}/orang</p>
                            <small class="text-muted">{{ $package->duration_text }}</small>
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
