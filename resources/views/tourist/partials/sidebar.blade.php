{{-- resources/views/tourist/partials/sidebar.blade.php --}}

<div class="list-group">
    <a href="{{ route('tourist.dashboard') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('tourist.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>

    <a href="{{ route('tourist.profile') }}" {{-- Perhatikan perubahan ini --}}
        class="list-group-item list-group-item-action {{ request()->routeIs('tourist.profile') ? 'active' : '' }}">
        <i class="bi bi-person me-2"></i> Profil
    </a>

    <a href="{{ route('tourist.bookings.index') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('tourist.bookings.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check me-2"></i> Pemesanan
    </a>

    <a href="{{ route('tourist.reviews.index') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('tourist.reviews.*') ? 'active' : '' }}">
        <i class="bi bi-star me-2"></i> Ulasan
    </a>

    <a href="{{ route('tourist.wishlist.index') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('tourist.wishlist.*') ? 'active' : '' }}">
        <i class="bi bi-heart me-2"></i> Wishlist
    </a>

    <a href="{{ route('tourist.itinerary.index') }}"
        class="list-group-item list-group-item-action {{ request()->routeIs('tourist.itinerary.*') ? 'active' : '' }}">
        <i class="bi bi-map me-2"></i> Rencana Perjalanan
    </a>
</div>
