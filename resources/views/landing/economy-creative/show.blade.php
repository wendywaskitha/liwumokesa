@extends('layouts.landing')

@section('content')
    <div class="container py-5">
        <div class="row">
            <!-- Product Images -->
            <div class="mb-4 col-md-6">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <img src="{{ asset('storage/' . $product->featured_image) }}" class="rounded img-fluid"
                            alt="{{ $product->name }}">

                        @if ($product->galleries->count() > 0)
                            <div class="mt-2 row g-2">
                                @foreach ($product->galleries as $image)
                                    <div class="col-3">
                                        <img src="{{ asset('storage/' . $image->featured_image) }}"
                                            class="rounded cursor-pointer img-fluid"
                                            onclick="showImage('{{ asset('storage/' . $image->featured_image) }}')"
                                            alt="Gallery image">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <h3 class="mb-2">{{ $product->name }}</h3>
                        <p class="mb-3 text-muted">{{ $product->category->name }}</p>

                        <div class="mb-3 d-flex align-items-center">
                            <h4 class="mb-0 text-primary">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </h4>
                            <div class="ms-3">
                                <i class="bi bi-star-fill text-warning"></i>
                                {{ number_format($product->average_rating, 1) }}
                                ({{ $product->reviews_count }} ulasan)
                            </div>
                        </div>

                        <hr>

                        <div class="mb-4">
                            <h6>Deskripsi</h6>
                            <p>{{ $product->description }}</p>
                        </div>

                        <div class="mb-4">
                            <h6>Informasi Penjual</h6>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="p-2 bg-light rounded-circle">
                                        <i class="bi bi-shop text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ $product->seller_name }}</h6>
                                    <p class="mb-0 small">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        {{ $product->location }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="gap-2 d-grid">
                            <a href="https://wa.me/{{ $product->contact }}" class="btn btn-primary" target="_blank">
                                <i class="bi bi-whatsapp me-2"></i>
                                Hubungi Penjual
                            </a>
                            @auth
                                <button class="btn btn-outline-primary" onclick="toggleWishlist({{ $product->id }})">
                                    <i class="bi bi-heart{{ $product->is_wishlisted ? '-fill' : '' }} me-2"></i>
                                    {{ $product->is_wishlisted ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Reviews -->
                @if ($product->reviews->count() > 0)
                    <div class="mt-4 border-0 shadow-sm card">
                        <div class="card-body">
                            <h5 class="mb-3">Ulasan Pembeli</h5>
                            @foreach ($product->reviews as $review)
                                <div class="mb-3">
                                    <div class="mb-2 d-flex align-items-center">
                                        <img src="{{ $review->user->profile_photo_url }}" class="rounded-circle"
                                            width="32" height="32" alt="{{ $review->user->name }}">
                                        <div class="ms-2">
                                            <h6 class="mb-0">{{ $review->user->name }}</h6>
                                            <small class="text-muted">
                                                {{ $review->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i
                                                class="bi bi-star{{ $i < $review->rating ? '-fill' : '' }} text-warning"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-0">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showImage(url) {
                // Implement image viewer
            }

            function toggleWishlist(productId) {
                // Implement wishlist toggle
            }
        </script>
    @endpush
@endsection
