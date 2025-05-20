{{-- resources/views/tourist/bookings/partials/_payment-methods.blade.php --}}

<div class="payment-methods">
    <!-- Bank Transfer Section -->
    <div class="mb-4">
        <h6 class="mb-3">Transfer Bank</h6>

        <!-- Bank List -->
        <div class="mb-3 border-0 shadow-sm card">
            <div class="card-body">
                <!-- Bank BRI -->
                <div class="pb-3 mb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="p-2 rounded bg-light">
                                <i class="bi bi-bank text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1 fw-bold">Bank BRI</p>
                            <div class="d-flex align-items-center">
                                <span class="me-2 font-monospace">123456789012</span>
                                <button class="btn btn-sm btn-light"
                                        onclick="copyToClipboard('123456789012')"
                                        data-bs-toggle="tooltip"
                                        title="Salin nomor rekening">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                            <small class="text-muted">a.n. DINAS PARIWISATA MUNA BARAT</small>
                        </div>
                    </div>
                </div>

                <!-- Bank BNI -->
                <div class="mb-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="p-2 rounded bg-light">
                                <i class="bi bi-bank text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-1 fw-bold">Bank BNI</p>
                            <div class="d-flex align-items-center">
                                <span class="me-2 font-monospace">987654321098</span>
                                <button class="btn btn-sm btn-light"
                                        onclick="copyToClipboard('987654321098')"
                                        data-bs-toggle="tooltip"
                                        title="Salin nomor rekening">
                                    <i class="bi bi-copy"></i>
                                </button>
                            </div>
                            <small class="text-muted">a.n. DINAS PARIWISATA MUNA BARAT</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Instructions -->
    <div class="border-0 shadow-sm alert alert-info">
        <h6 class="mb-2 alert-heading">
            <i class="bi bi-info-circle me-2"></i>
            Petunjuk Pembayaran:
        </h6>
        <ol class="mb-0 ps-3">
            <li class="mb-2">Transfer sesuai nominal ke salah satu rekening di atas</li>
            <li class="mb-2">Simpan bukti transfer</li>
            <li class="mb-2">Upload bukti transfer pada form di bawah</li>
            <li>Tunggu konfirmasi dari admin (1x24 jam)</li>
        </ol>
    </div>

    <!-- Payment Deadline -->
    <div class="mb-4 border-0 shadow-sm alert alert-warning">
        <div class="d-flex align-items-center">
            <i class="bi bi-clock-history fs-4 me-2"></i>
            <div>
                <h6 class="mb-1 alert-heading">Batas Waktu Pembayaran</h6>
                <p class="mb-0">{{ now()->addHours(24)->format('d M Y H:i') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Upload Payment Form -->
    <form action="{{ route('tourist.bookings.upload-payment', $booking) }}"
          method="POST"
          enctype="multipart/form-data"
          class="mt-4">
        @csrf
        <div class="mb-3">
            <label class="form-label">Upload Bukti Pembayaran</label>
            <input type="file"
                   class="form-control @error('payment_proof') is-invalid @enderror"
                   name="payment_proof"
                   accept="image/*"
                   required>
            @error('payment_proof')
                <div class="invalid-feedback">
                    {{ $message ?? 'Bukti pembayaran wajib diunggah dan harus berupa gambar.' }}
                </div>
            @enderror
            <div class="form-text">
                Format: JPG, PNG (Maks. 2MB)
            </div>
        </div>

        <!-- Preview Image -->
        <div class="mb-3 d-none" id="imagePreview">
            <img src="#" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-upload me-2"></i>
                Upload Bukti Pembayaran
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Show success message
        alert('Nomor rekening berhasil disalin!');
    });
}

// Preview image before upload
document.querySelector('input[name="payment_proof"]').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    const file = e.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('d-none');
        }

        reader.readAsDataURL(file);
    } else {
        preview.classList.add('d-none');
    }
});
</script>
@endpush
