<header>
    <nav class="bg-white shadow-sm navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                @if($logo = \App\Models\Setting::get('general.site_logo'))
                    <img src="{{ asset('storage/' . $logo) }}" alt="Muna Barat Tourism" height="40">
                @else
                    <span class="fw-bold text-primary">{{ \App\Models\Setting::get('general.site_name', 'Muna Barat Tourism') }}</span>
                @endif
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="mb-2 navbar-nav ms-auto mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                           href="{{ url('/') }}">
                            Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('destinations*') ? 'active' : '' }}"
                           href="{{ route('destinations.index') }}">
                            Destinasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('travel-packages*') ? 'active' : '' }}"
                           href="{{ route('packages.index') }}">
                            Paket Wisata
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('events*') ? 'active' : '' }}"
                           href="{{ route('landing.events') }}">
                            Event
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}"
                           href="{{ route('about') }}">
                            Tentang
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}"
                           href="{{ route('contact') }}">
                            Kontak
                        </a>
                    </li>
                </ul>
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
