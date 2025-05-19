{{-- resources/views/tourist/reviews/partials/edit-modal.blade.php --}}
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
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="rating">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio"
                                       name="rating"
                                       value="{{ $i }}"
                                       id="rating{{ $review->id }}-{{ $i }}"
                                       {{ $review->rating == $i ? 'checked' : '' }}>
                                <label for="rating{{ $review->id }}-{{ $i }}">☆</label>
                            @endfor
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ulasan</label>
                        <textarea class="form-control"
                                  name="comment"
                                  rows="3"
                                  required>{{ $review->comment }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
