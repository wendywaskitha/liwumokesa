<div class="modal fade" id="updatePhotoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tourist.profile.update-photo') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="updatePhotoForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <img src="{{ auth()->user()->profile_photo_url }}"
                             alt="Preview"
                             class="rounded-circle img-thumbnail preview-image"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pilih Foto <span class="text-danger">*</span></label>
                        <input type="file"
                               class="form-control @error('profile_image') is-invalid @enderror"
                               name="profile_image"
                               accept="image/jpeg,image/png,image/jpg"
                               required>
                        @error('profile_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Format yang didukung: JPG, PNG. Maksimal 2MB.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-cloud-upload me-1"></i>Upload Foto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
