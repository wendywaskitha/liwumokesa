<footer class="py-5 text-white bg-dark">
    <div class="container">
        <div class="row">
            <!-- Site Info -->
            <div class="mb-4 col-lg-3 col-md-6 mb-lg-0">
                <h5 class="mb-4 text-uppercase fw-bold">
                    {{ \App\Models\Setting::get('general.site_name', 'Muna Barat Tourism') }}
                </h5>
                <p>{{ \App\Models\Setting::get('general.site_tagline', 'Jelajahi Pesona Muna Barat') }}</p>
                <div class="mt-4">
                    @if($facebookUrl = \App\Models\Setting::get('social.facebook_url'))
                        <a href="{{ $facebookUrl }}" class="text-white me-3" target="_blank">
                            <i class="bi bi-facebook fs-5"></i>
                        </a>
                    @endif

                    @if($instagramUrl = \App\Models\Setting::get('social.instagram_url'))
                        <a href="{{ $instagramUrl }}" class="text-white me-3" target="_blank">
                            <i class="bi bi-instagram fs-5"></i>
                        </a>
                    @endif

                    @if($twitterUrl = \App\Models\Setting::get('social.twitter_url'))
                        <a href="{{ $twitterUrl }}" class="text-white me-3" target="_blank">
                            <i class="bi bi-twitter fs-5"></i>
                        </a>
                    @endif

                    @if($youtubeUrl = \App\Models\Setting::get('social.youtube_url'))
                        <a href="{{ $youtubeUrl }}" class="text-white me-3" target="_blank">
                            <i class="bi bi-youtube fs-5"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div class="mb-4 col-lg-3 col-md-6 mb-lg-0">
                <h5 class="mb-4 text-uppercase fw-bold">Tautan</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ url('/destinations') }}" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right small"></i> Destinasi
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/packages') }}" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right small"></i> Paket Wisata
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/events') }}" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right small"></i> Event
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/about') }}" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right small"></i> Tentang Kami
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/contact') }}" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right small"></i> Kontak
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Categories -->
            <div class="mb-4 col-lg-3 col-md-6 mb-lg-0">
                <h5 class="mb-4 text-uppercase fw-bold">Kategori</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ url('/destinations?category=pantai') }}" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right small"></i> Wisata Pantai
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/destinations?category=sejarah') }}" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right small"></i> Wisata Sejarah
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/destinations?category=alam') }}" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right small"></i> Wisata Alam
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/destinations?category=budaya') }}" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right small"></i> Wisata Budaya
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/destinations?category=kuliner') }}" class="text-white text-decoration-none">
                            <i class="bi bi-chevron-right small"></i> Wisata Kuliner
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="mb-4 col-lg-3 col-md-6 mb-lg-0">
                <h5 class="mb-4 text-uppercase fw-bold">Kontak</h5>
                <p>
                    <i class="bi bi-geo-alt me-2"></i>
                    {{ \App\Models\Setting::get('contact.contact_address', 'Jl. Poros Raha-Laworo, Muna Barat, Sulawesi Tenggara') }}
                </p>
                <p>
                    <i class="bi bi-envelope me-2"></i>
                    {{ \App\Models\Setting::get('contact.contact_email', 'info@munabarat.go.id') }}
                </p>
                <p>
                    <i class="bi bi-telephone me-2"></i>
                    {{ \App\Models\Setting::get('contact.contact_phone', '+62 401 123456') }}
                </p>
            </div>
        </div>

        <hr class="my-4">

        <!-- Copyright -->
        <div class="row align-items-center">
            <div class="text-center col-md-6 text-md-start">
                <p class="mb-3 mb-md-0">
                    &copy; {{ date('Y') }} {{ \App\Models\Setting::get('general.site_name', 'Pariwisata Muna Barat') }}.
                    All Rights Reserved.
                </p>
            </div>
            <div class="text-center col-md-6 text-md-end">
                <a href="{{ url('/privacy-policy') }}" class="text-white text-decoration-none me-3">
                    Kebijakan Privasi
                </a>
                {{-- <a href="{{ url('/terms') }}" class="text-white text-decoration-none">
                    Syarat & Ketentuan
                </a> --}}
            </div>
        </div>
    </div>
</footer>
