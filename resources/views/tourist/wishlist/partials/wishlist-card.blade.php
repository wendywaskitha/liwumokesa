<div class="card h-100">
    @if($wishlist->wishable->featured_image)
        <img src="{{ Storage::url($wishlist->wishable->featured_image) }}"
             class="card-img-top"
             style="height: 200px; object-fit: cover;"
             alt="{{ $wishlist->wishable->name }}">
    @endif
    <div class="card-body">
        <div class="mb-2 d-flex justify-content-between align-items-start">
            <h5 class="mb-0 card-title">{{ $wishlist->wishable->name }}</h5>
            @php
                $priorities = [
                    1 => 'Prioritas Rendah',
                    2 => 'Prioritas Cukup Rendah',
                    3 => 'Prioritas Sedang',
                    4 => 'Prioritas Cukup Tinggi',
                    5 => 'Prioritas Tinggi'
                ];

                $priorityColors = [
                    1 => 'bg-secondary',
                    2 => 'bg-info',
                    3 => 'bg-primary',
                    4 => 'bg-warning',
                    5 => 'bg-danger'
                ];

                $priorityIcons = [
                    1 => 'bi-star',
                    2 => 'bi-star-half',
                    3 => 'bi-star-fill',
                    4 => 'bi-stars',
                    5 => 'bi-star-fill'
                ];
            @endphp

            <span class="badge {{ $priorityColors[$wishlist->priority ?? 1] }}">
                <i class="bi {{ $priorityIcons[$wishlist->priority ?? 1] }} me-1"></i>
                {{ $priorities[$wishlist->priority ?? 1] }}
            </span>
        </div>

        <p class="mb-3 card-text small text-muted">
            <i class="bi bi-geo-alt me-1"></i>
            {{ $wishlist->wishable->district->name }}
        </p>

        @if($wishlist->notes)
            <p class="mb-3 card-text small">
                <i class="bi bi-pencil me-1"></i>
                {{ $wishlist->notes }}
            </p>
        @endif

        <div class="gap-2 d-flex">
            <button type="button"
                    class="btn btn-sm btn-outline-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#editWishlistModal{{ $wishlist->id }}">
                <i class="bi bi-pencil me-1"></i>Edit
            </button>

            <form action="{{ route('tourist.wishlist.toggle') }}" method="POST">
                @csrf
                <input type="hidden" name="wishable_type" value="{{ get_class($wishlist->wishable) }}">
                <input type="hidden" name="wishable_id" value="{{ $wishlist->wishable->id }}">
                <button type="submit" class="btn btn-sm btn-danger">
                    <i class="bi bi-heart-fill me-1"></i>Hapus
                </button>
            </form>
        </div>
    </div>
</div>
