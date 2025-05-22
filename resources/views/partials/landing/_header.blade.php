<header>
    <nav class="bg-white shadow-sm navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand" href="{{ url('/') }}">
                @if ($logo = \App\Models\Setting::get('general.site_logo'))
                    <img src="{{ asset('storage/' . $logo) }}" alt="Muna Barat Tourism" height="40">
                @else
                    <span
                        class="fw-bold text-primary">{{ \App\Models\Setting::get('general.site_name', 'Muna Barat Tourism') }}</span>
                @endif
            </a>

            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="mb-2 navbar-nav ms-auto mb-lg-0">
                    <!-- Beranda -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            Beranda
                        </a>
                    </li>

                    <!-- Wisata Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('destinations*') || request()->is('cultural-heritage*') || request()->is('events*') || request()->is('economy-creative*') ? 'active' : '' }}"
                            href="#" role="button" data-bs-toggle="dropdown">
                            Wisata
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item {{ request()->is('destinations*') ? 'active' : '' }}"
                                    href="{{ route('destinations.index') }}">
                                    <i class="bi bi-map me-2"></i>Destinasi
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->is('cultural-heritage*') ? 'active' : '' }}"
                                    href="{{ route('landing.cultural-heritage.index') }}">
                                    <i class="bi bi-bank me-2"></i>Warisan Budaya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->is('events*') ? 'active' : '' }}"
                                    href="{{ route('landing.events') }}">
                                    <i class="bi bi-calendar-event me-2"></i>Event
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ request()->is('economy-creative*') ? 'active' : '' }}"
                                    href="{{ route('economy-creative.index') }}">
                                    <i class="bi bi-shop me-2"></i>Ekonomi Kreatif
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Sebaran Wisata -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('landing.map') ? 'active' : '' }}"
                            href="{{ route('landing.map') }}">
                            <i class="bi bi-map"></i> Sebaran Wisata
                        </a>
                    </li>


                    <!-- Paket Wisata -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('travel-packages*') ? 'active' : '' }}"
                            href="{{ route('packages.index') }}">
                            Paket Wisata
                        </a>
                    </li>

                    <!-- Tentang -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            Tentang
                        </a>
                    </li>

                    <!-- Kontak -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}"
                            href="{{ route('contact') }}">
                            Kontak
                        </a>
                    </li>
                </ul>

                <!-- Auth Buttons/Dropdown -->
                <div class="mt-3 ms-lg-3 mt-lg-0">
                    @auth
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ url('tourist/profile') }}">
                                        <i class="bi bi-person me-2"></i>Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ url('tourist/dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i>Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
