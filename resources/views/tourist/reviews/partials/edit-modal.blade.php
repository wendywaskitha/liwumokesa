<!-- Edit Review Modal -->
<div class="modal fade" id="editReviewModal{{ $review->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Ulasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tourist.reviews.update', $review) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Rating Input -->
                    <div class="mb-3">
                        <label class="form-label">Rating <span class="text-danger">*</span></label>
                        <div class="star-rating">
                            <div class="star-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio"
                                           name="rating"
                                           value="{{ $i }}"
                                           id="rate-{{ $review->id }}-{{ $i }}"
                                           {{ $review->rating == $i ? 'checked' : '' }}
                                           required>
                                    <label for="rate-{{ $review->id }}-{{ $i }}">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                @endfor
                            </div>
                        </div>
                        @error('rating')
                            <div class="mt-1 text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Comment Input -->
                    <div class="mb-3">
                        <label class="form-label">Komentar <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('comment') is-invalid @enderror"
                                name="comment"
                                rows="3"
                                required
                                minlength="10"
                                maxlength="1000">{{ old('comment', $review->comment) }}</textarea>
                        <div class="form-text">Minimal 10 karakter, maksimal 1000 karakter</div>
                        @error('comment')
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


@push('styles')
<style>
    .star-rating .star-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-start;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        cursor: pointer;
        padding: 0 0.2em;
        font-size: 2rem;
        transition: color 0.2s;
    }

    .star-rating label i {
        color: #ddd;
    }

    .star-rating input:checked ~ label i {
        color: #ffc107;
    }

    .star-rating label:hover i,
    .star-rating label:hover ~ label i {
        color: #ffc107;
    }

    .star-rating[data-rating="1"] label:nth-child(n+10) i,
    .star-rating[data-rating="2"] label:nth-child(n+8) i,
    .star-rating[data-rating="3"] label:nth-child(n+6) i,
    .star-rating[data-rating="4"] label:nth-child(n+4) i,
    .star-rating[data-rating="5"] label:nth-child(n+2) i {
        color: #ffc107;
    }
</style>
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle star rating in modal
    const starRatings = document.querySelectorAll('.star-rating');
    starRatings.forEach(container => {
        const inputs = container.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('change', function() {
                container.dataset.rating = this.value;
            });
        });
    });
});
</script>
@endpush
