{{-- resources/views/landing/index.blade.php --}}
@extends('layouts.landing')

@section('title', \App\Models\Setting::get('general.site_name', 'Pariwisata Muna Barat'))

@section('meta_description', \App\Models\Setting::get('seo.meta_description', 'Jelajahi keindahan alam dan warisan budaya Kabupaten Muna Barat. Temukan destinasi wisata menakjubkan, paket perjalanan, dan informasi lengkap untuk liburan Anda di Muna Barat.'))

@section('styles')
<style>
    .hero-section {
        margin-top: 56px;
        height: 70vh;
        min-height: 500px;
    }

    .hero-section img {
        height: 70vh;
        min-height: 500px;
        object-fit: cover;
    }

    .search-box-wrapper {
        margin-top: -100px;
        position: relative;
        z-index: 10;
        margin-bottom: 30px;
    }

    .destination-card, .package-card, .event-card, .creative-card {
        transition: all 0.3s ease;
    }

    .destination-card:hover, .package-card:hover, .event-card:hover, .creative-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    @include('partials.landing._hero')

    <!-- Search Box -->
    @include('partials.landing._search-box')

    <!-- Featured Destinations -->
    @include('partials.landing._featured-destinations')

    <!-- Travel Packages -->
    @include('partials.landing._travel-packages')

    <!-- Creative Economy Section -->
    @include('partials.landing._economy-creative')

    <!-- Upcoming Events -->
    @include('partials.landing._upcoming-events')

    <!-- About Section -->
    @include('partials.landing._about-section')

    <!-- CTA Section -->
    @include('partials.landing._cta')
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize any plugins or interactions here

        // Filter for creative economy products
        const filterButtons = document.querySelectorAll('[data-filter]');
        if (filterButtons) {
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.dataset.filter;

                    // Update active state
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Filter items
                    document.querySelectorAll('.creative-item').forEach(item => {
                        if (filter === 'all' || item.dataset.category === filter) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        }
    });
</script>
@endsection
