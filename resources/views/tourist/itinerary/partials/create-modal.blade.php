<div class="modal fade" id="createItineraryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Rencana Perjalanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tourist.itinerary.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Name Input -->
                    <div class="mb-3">
                        <label class="form-label">Nama Rencana <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Date Range -->
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   name="start_date"
                                   value="{{ old('start_date') }}"
                                   required>
                            @error('start_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   name="end_date"
                                   value="{{ old('end_date') }}"
                                   required>
                            @error('end_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Travel Packages -->
                    <div class="mb-3">
                        <label class="form-label">Pilih Paket Wisata <span class="text-danger">*</span></label>
                        <select class="form-select @error('travel_package_ids') is-invalid @enderror"
                                name="travel_package_ids[]"
                                multiple
                                required>
                            @foreach($travelPackages as $package)
                                <option value="{{ $package->id }}">
                                    {{ $package->name }} - {{ $package->duration_text }}
                                    ({{ $package->price_formatted }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            Tahan tombol Ctrl (Windows) atau Command (Mac) untuk memilih beberapa paket
                        </div>
                        @error('travel_package_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                name="notes"
                                rows="3"
                                placeholder="Tambahkan catatan untuk rencana perjalanan ini...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
