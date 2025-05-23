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
                        <!-- Review Header with Status -->
                        <div class="mb-3 d-flex align-items-center">
                            <img src="{{ Storage::url($review->reviewable->featured_image) }}"
                                 class="rounded me-3"
                                 style="width: 64px; height: 64px; object-fit: cover;"
                                 alt="{{ $review->reviewable->name }}">
                            <div>
                                <h5 class="mb-1 card-title">{{ $review->reviewable->name }}</h5>
                                <div class="flex-wrap gap-2 d-flex align-items-center">
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="badge {{ $review->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                        {{ $review->status === 'approved' ? 'Disetujui' : 'Menunggu Persetujuan' }}
                                    </span>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>{{ $review->created_at->format('d M Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Review Content -->
                        <p class="card-text">{{ $review->comment }}</p>

                        <!-- Review Images -->
                        @if($review->images->isNotEmpty())
                            <div class="mb-3 review-images">
                                <div class="row g-2">
                                    @foreach($review->images as $image)
                                        <div class="col-4">
                                            <a href="{{ Storage::url($image->path) }}" data-fslightbox="review-{{ $review->id }}">
                                                <img src="{{ Storage::url($image->path) }}"
                                                     class="rounded img-fluid"
                                                     alt="Review image">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Review Actions -->
                        @if($review->status !== 'approved')
                            <div class="gap-2 d-flex justify-content-end">
                                <button type="button"
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editReviewModal{{ $review->id }}">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </button>
                                <form action="{{ route('tourist.reviews.destroy', $review) }}"
                                      method="POST"
                                      id="delete-form-{{ $review->id }}"
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $review->id }}')">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            @if($review->status !== 'approved')
                @include('tourist.reviews.partials.edit-modal', ['review' => $review])
            @endif
        @empty
            <div class="col-12">
                <div class="py-5 text-center">
                    <img src="{{ asset('images/illustrations/empty-reviews.svg') }}"
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
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fslightbox@3.3.1/index.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fslightbox@3.3.1/index.min.js"></script>
<script>
function confirmDelete(reviewId) {
    if (confirm('Apakah Anda yakin ingin menghapus ulasan ini?')) {
        document.getElementById('delete-form-' + reviewId).submit();
    }
}
</script>
@endpush
