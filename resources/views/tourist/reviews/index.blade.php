@extends('layouts.tourist-dashboard')

@section('title', 'Ulasan Saya')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Ulasan Saya</h4>
    </div>

    <!-- Reviews List -->
    <div class="row g-4">
        @forelse($reviews as $review)
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="mb-3 d-flex align-items-center">
                        <img src="{{ asset('storage/' . $review->reviewable->image) }}"
                             class="rounded me-3"
                             style="width: 64px; height: 64px; object-fit: cover;"
                             alt="{{ $review->reviewable->name }}">
                        <div>
                            <h5 class="mb-1 card-title">{{ $review->reviewable->name }}</h5>
                            <div class="mb-1 text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                @endfor
                            </div>
                            <small class="text-muted">
                                {{ $review->created_at->format('d M Y') }}
                            </small>
                        </div>
                    </div>

                    <p class="card-text">{{ $review->comment }}</p>

                    @if($review->images->count() > 0)
                        <div class="mb-3 review-images">
                            <div class="row g-2">
                                @foreach($review->images as $image)
                                <div class="col-4">
                                    <img src="{{ asset('storage/' . $image->path) }}"
                                         class="rounded img-fluid"
                                         alt="Review image">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end">
                        <button type="button"
                                class="btn btn-sm btn-primary me-2"
                                data-bs-toggle="modal"
                                data-bs-target="#editReviewModal{{ $review->id }}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-danger"
                                onclick="confirmDelete('{{ $review->id }}')">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal for each review -->
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
        @empty
        <div class="col-12">
            <div class="py-5 text-center">
                <img src="{{ asset('images/empty-reviews.svg') }}"
                     alt="No Reviews"
                     class="mb-3"
                     style="max-width: 200px">
                <h5>Belum ada ulasan</h5>
                <p class="text-muted">
                    Bagikan pengalaman perjalanan Anda dengan menulis ulasan!
                </p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(reviewId) {
    if (confirm('Apakah Anda yakin ingin menghapus ulasan ini?')) {
        document.getElementById('delete-form-' + reviewId).submit();
    }
}
</script>
@endpush
@endsection
