<form action="{{ route('tourist.bookings.store') }}" method="POST">
    @csrf
    <input type="hidden" name="travel_package_id" value="{{ $package->id }}">

    <!-- Booking Date -->
    <div class="mb-3">
        <label class="form-label">Tanggal Kunjungan</label>
        <input type="date"
               class="form-control @error('booking_date') is-invalid @enderror"
               name="booking_date"
               min="{{ date('Y-m-d') }}"
               value="{{ old('booking_date') }}"
               required>
        @error('booking_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Number of People -->
    <div class="mb-3">
        <label class="form-label">Jumlah Peserta</label>
        <input type="number"
               class="form-control @error('quantity') is-invalid @enderror"
               name="quantity"
               min="{{ $package->min_participants ?? 1 }}"
               max="{{ $package->max_participants ?? 10 }}"
               value="{{ old('quantity', 1) }}"
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
        <label class="form-label">Catatan (opsional)</label>
        <textarea class="form-control @error('notes') is-invalid @enderror"
                  name="notes"
                  rows="3">{{ old('notes') }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary">
            Lanjutkan ke Pembayaran
        </button>
    </div>
</form>
