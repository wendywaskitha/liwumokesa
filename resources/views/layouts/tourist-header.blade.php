<header>
    <nav class="bg-white shadow-sm navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="{{ url('/') }}">
                @if($logo = \App\Models\Setting::get('general.site_logo'))
                    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" height="40">
                @else
                    <span class="fw-bold text-primary">
                        {{ \App\Models\Setting::get('general.site_name', config('app.name')) }}
                    </span>
                @endif
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="mb-2 navbar-nav ms-auto mb-lg-0">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        Beranda
                    </x-nav-link>

                    <x-nav-link :href="route('destinations.index')" :active="request()->routeIs('destinations.*')">
                        Destinasi
                    </x-nav-link>

                    <x-nav-link :href="route('packages.index')" :active="request()->routeIs('packages.*')">
                        Paket Wisata
                    </x-nav-link>

                    <x-nav-link :href="route('events')" :active="request()->routeIs('events')">
                        Event
                    </x-nav-link>

                    <x-nav-link :href="route('about')" :active="request()->routeIs('about')">
                        Tentang
                    </x-nav-link>

                    <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')">
                        Kontak
                    </x-nav-link>
                </ul>

                <!-- User Menu -->
                <div class="mt-3 ms-lg-3 mt-lg-0">
                    @auth
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="btn btn-outline-primary dropdown-toggle">
                                    <i class="bi bi-person-circle"></i>
                                    {{ Auth::user()->name }}
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    <i class="bi bi-person me-2"></i>
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('tourist.dashboard')">
                                    <i class="bi bi-speedometer2 me-2"></i>
                                    {{ __('Dashboard') }}
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('Log in') }}
                        </a>

                        <a href="{{ route('register') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i>{{ __('Register') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
