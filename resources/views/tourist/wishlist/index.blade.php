@extends('layouts.tourist-dashboard')

@section('title', 'Wishlist')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Wishlist Saya</h4>
        <div class="btn-group">
            <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                Urutkan
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Terbaru</a></li>
                <li><a class="dropdown-item" href="#">Harga Tertinggi</a></li>
                <li><a class="dropdown-item" href="#">Harga Terendah</a></li>
            </ul>
        </div>
    </div>

    <!-- Wishlist Grid -->
    <div class="row g-4">
        @forelse($wishlistItems as $item)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <img src="{{ asset('storage/' . $item->travelPackage->image) }}"
                     class="card-img-top"
                     style="height: 200px; object-fit: cover;"
                     alt="{{ $item->travelPackage->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $item->travelPackage->name }}</h5>
                    <p class="card-text text-muted">
                        {{ Str::limit($item->travelPackage->description, 100) }}
                    </p>

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span class="mb-0 h5">
                            Rp {{ number_format($item->travelPackage->price, 0, ',', '.') }}
                        </span>
                        <span class="badge bg-info">
                            {{ $item->travelPackage->duration }}
                        </span>
                    </div>

                    <div class="gap-2 d-grid">
                        <a href="{{ route('packages.show', $item->travelPackage->slug) }}"
                           class="btn btn-primary">
                            <i class="bi bi-eye me-2"></i> Lihat Detail
                        </a>
                        <form action="{{ route('tourist.wishlist.destroy', $item) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-outline-danger w-100"
                                    onclick="return confirm('Hapus dari wishlist?')">
                                <i class="bi bi-trash me-2"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="py-5 text-center">
                <img src="{{ asset('images/empty-wishlist.svg') }}"
                     alt="Empty Wishlist"
                     class="mb-3"
                     style="max-width: 200px">
                <h5>Wishlist Kosong</h5>
                <p class="text-muted">
                    Jelajahi paket wisata dan tambahkan ke wishlist Anda!
                </p>
                <a href="{{ route('packages.index') }}" class="btn btn-primary">
                    Jelajahi Paket Wisata
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $wishlistItems->links() }}
    </div>
</div>
@endsection
