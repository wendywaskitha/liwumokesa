@extends('layouts.tourist-dashboard')

@section('title', 'Wishlist Saya')

@section('content')
<div class="container-fluid">
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Wishlist Saya</h4>
    </div>

    <div class="row g-4">
        @forelse($wishlists as $wishlist)
            <div class="col-md-6 col-lg-4">
                @include('tourist.wishlist.partials.wishlist-card', ['wishlist' => $wishlist])
            </div>
            @include('tourist.wishlist.partials.edit-modal', ['wishlist' => $wishlist])
        @empty
            <div class="col-12">
                <div class="py-5 text-center">
                    <i class="bi bi-heart display-4 text-muted"></i>
                    <h5 class="mt-3">Wishlist Anda masih kosong</h5>
                    <p class="text-muted">
                        Jelajahi destinasi menarik dan tambahkan ke wishlist Anda!
                    </p>
                    <a href="{{ route('destinations.index') }}" class="btn btn-primary">
                        <i class="bi bi-compass me-1"></i>Jelajahi Destinasi
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $wishlists->links() }}
    </div>
</div>
@endsection
