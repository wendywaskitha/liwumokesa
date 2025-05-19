@extends('layouts.landing')

@section('title', 'Tentang Kami - Pariwisata Muna Barat')

@section('content')
    <!-- Hero Section -->
    <section class="text-white hero-section position-relative bg-primary">
        <div class="container py-5">
            <div class="row align-items-center min-vh-50">
                <div class="col-lg-6">
                    <h1 class="mb-4 display-4 fw-bold">Tentang Kami</h1>
                    <p class="mb-0 lead">Jelajahi keindahan dan kekayaan budaya Kabupaten Muna Barat melalui destinasi wisata yang menakjubkan.</p>
                </div>
            </div>
        </div>
        <!-- Overlay dengan pattern -->
        <div class="top-0 position-absolute start-0 w-100 h-100" style="background-image: url('data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="text-center border-0 shadow-sm card h-100">
                        <div class="card-body">
                            <i class="mb-3 bi bi-geo-alt text-primary display-4"></i>
                            <h3 class="mb-2 h2 fw-bold">{{ number_format($stats['districts_count']) }}</h3>
                            <p class="mb-0 text-muted">Kecamatan</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-center border-0 shadow-sm card h-100">
                        <div class="card-body">
                            <i class="mb-3 bi bi-compass text-primary display-4"></i>
                            <h3 class="mb-2 h2 fw-bold">{{ number_format($stats['destinations_count']) }}</h3>
                            <p class="mb-0 text-muted">Destinasi Wisata</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-center border-0 shadow-sm card h-100">
                        <div class="card-body">
                            <i class="mb-3 bi bi-bank text-primary display-4"></i>
                            <h3 class="mb-2 h2 fw-bold">{{ number_format($stats['cultural_heritages_count']) }}</h3>
                            <p class="mb-0 text-muted">Warisan Budaya</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="text-center border-0 shadow-sm card h-100">
                        <div class="card-body">
                            <i class="mb-3 bi bi-building text-primary display-4"></i>
                            <h3 class="mb-2 h2 fw-bold">{{ number_format($stats['accommodations_count']) }}</h3>
                            <p class="mb-0 text-muted">Akomodasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="mb-4 col-lg-6 mb-lg-0">
                    <img src="{{ asset('images/about-image.jpg') }}"
                         alt="Muna Barat"
                         class="shadow img-fluid rounded-3">
                </div>
                <div class="col-lg-6">
                    <div class="prose">
                        {!! $aboutContent ?? '
                            <h2 class="mb-4 h3">Selamat Datang di Muna Barat</h2>
                            <p class="mb-4 lead">Kabupaten Muna Barat adalah destinasi wisata yang menawarkan keindahan alam, kekayaan budaya, dan pengalaman yang tak terlupakan.</p>
                            <p>Kami berkomitmen untuk memperkenalkan keindahan dan keunikan Muna Barat kepada dunia. Dengan berbagai destinasi wisata yang menakjubkan, warisan budaya yang kaya, dan kuliner yang lezat, Muna Barat siap menyambut Anda.</p>
                            <p>Jelajahi pantai-pantai eksotis, nikmati sunset yang memukau, rasakan kehangatan budaya lokal, dan temukan pengalaman baru yang mengesankan di Muna Barat.</p>
                        ' !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Mission -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="border-0 shadow-sm card h-100">
                        <div class="card-body">
                            <div class="mb-4 d-flex align-items-center">
                                <i class="bi bi-eye text-primary display-5 me-3"></i>
                                <h3 class="mb-0 h4">Visi</h3>
                            </div>
                            <p class="mb-0">Menjadikan Muna Barat sebagai destinasi wisata unggulan yang berkelanjutan, berdaya saing, dan memberikan manfaat bagi masyarakat lokal.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border-0 shadow-sm card h-100">
                        <div class="card-body">
                            <div class="mb-4 d-flex align-items-center">
                                <i class="bi bi-bullseye text-primary display-5 me-3"></i>
                                <h3 class="mb-0 h4">Misi</h3>
                            </div>
                            <ul class="mb-0 list-unstyled">
                                <li class="mb-3 d-flex">
                                    <i class="mt-1 bi bi-check2-circle text-primary me-2"></i>
                                    <span>Mengembangkan destinasi wisata yang berkualitas dan berkelanjutan</span>
                                </li>
                                <li class="mb-3 d-flex">
                                    <i class="mt-1 bi bi-check2-circle text-primary me-2"></i>
                                    <span>Melestarikan dan mempromosikan kekayaan budaya lokal</span>
                                </li>
                                <li class="d-flex">
                                    <i class="mt-1 bi bi-check2-circle text-primary me-2"></i>
                                    <span>Meningkatkan kesejahteraan masyarakat melalui sektor pariwisata</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="text-center col-lg-8">
                    <h2 class="mb-4 h3">Mulai Petualangan Anda di Muna Barat</h2>
                    <p class="mb-4 lead">Temukan keindahan alam, budaya, dan kuliner yang menakjubkan.</p>
                    <div class="gap-3 d-flex justify-content-center">
                        <a href="{{ url('/destinations') }}" class="btn btn-primary">
                            <i class="bi bi-compass me-2"></i>Jelajahi Destinasi
                        </a>
                        <a href="{{ url('/contact') }}" class="btn btn-outline-primary">
                            <i class="bi bi-chat-dots me-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .min-vh-50 {
        min-height: 50vh;
    }

    .prose {
        color: #4a5568;
    }

    .prose h2 {
        color: #2d3748;
        margin-bottom: 1rem;
    }

    .prose p {
        margin-bottom: 1.5rem;
        line-height: 1.7;
    }

    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
