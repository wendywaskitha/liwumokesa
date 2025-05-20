{{-- resources/views/tourist/bookings/partials/_image-preview-script.blade.php --}}

<script>
// Preview image before upload
document.querySelector('input[name="payment_proof"]')?.addEventListener('change', function(e) {
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

// Add loading state to form submission
const form = document.querySelector('form');
const submitBtn = form?.querySelector('button[type="submit"]');

form?.addEventListener('submit', function() {
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Mengupload...
        `;
    }
});
</script>
