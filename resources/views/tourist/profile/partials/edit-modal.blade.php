<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tourist.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Name Input -->
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name"
                               value="{{ old('name', auth()->user()->name) }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone Input -->
                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input type="tel"
                               class="form-control @error('phone') is-invalid @enderror"
                               name="phone"
                               value="{{ old('phone', auth()->user()->phone_number) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Address Input -->
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                name="address"
                                rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
