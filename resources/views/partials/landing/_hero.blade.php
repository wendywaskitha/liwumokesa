<section class="hero-section position-relative">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @php
                $banner = \App\Models\Setting::get('website.home_banner');
                $fallbackImages = [
                    'https://images.pexels.com/photos/1371360/pexels-photo-1371360.jpeg?auto=compress&cs=tinysrgb&h=1080',
                    'https://images.pexels.com/photos/3225531/pexels-photo-3225531.jpeg?auto=compress&cs=tinysrgb&h=1080',
                    'https://images.pexels.com/photos/3225517/pexels-photo-3225517.jpeg?auto=compress&cs=tinysrgb&h=1080',
                    'https://images.pexels.com/photos/2166553/pexels-photo-2166553.jpeg?auto=compress&cs=tinysrgb&h=1080',
                    'https://images.pexels.com/photos/2474689/pexels-photo-2474689.jpeg?auto=compress&cs=tinysrgb&h=1080'
                ];
                $randomFallbackImage = $fallbackImages[array_rand($fallbackImages)];
            @endphp

            <div class="carousel-item active">
                @if($banner && Storage::disk('public')->exists($banner))
                    <img src="{{ asset('storage/' . $banner) }}"
                         class="hero-image"
                         alt="Muna Barat">
                @else
                    <img src="{{ $randomFallbackImage }}"
                         class="hero-image"
                         alt="Muna Barat Tourism"
                         onerror="this.onerror=null; this.src='{{ asset('images/default-banner.jpg') }}'">
                @endif

                <!-- Overlay gradient -->
                <div class="hero-overlay"></div>

                <!-- Hero Content -->
                <div class="hero-content">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="text-center col-lg-8">
                                <h1 class="hero-title animate__animated animate__fadeInDown">
                                    {{ \App\Models\Setting::get('website.home_banner_title', 'Selamat Datang di Muna Barat') }}
                                </h1>
                                <p class="hero-subtitle animate__animated animate__fadeInUp">
                                    {{ \App\Models\Setting::get('website.home_banner_subtitle', 'Temukan pesona wisata alam dan budaya Muna Barat') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .hero-section {
        position: relative;
        height: 75vh; /* Dikurangi untuk memberikan ruang search box */
        overflow: hidden;
        margin-bottom: -80px; /* Nilai negatif untuk membuat search box menggantung */
    }

    .hero-image {
        width: 100%;
        height: 85vh;
        object-fit: cover;
        transition: transform 3s ease;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.7));
    }

    .hero-content {
        position: absolute;
        top: 40%;
        left: 0;
        right: 0;
        transform: translateY(-50%);
        color: white;
        z-index: 2;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }

    @media (max-width: 768px) {
        .hero-section {
            height: 75vh;
            margin-bottom: -60px;
        }

        .hero-image {
            height: 75vh;
        }

        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }
    }
</style>
@endpush
