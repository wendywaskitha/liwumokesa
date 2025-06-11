<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #4f46e5;
            --accent-color: #06b6d4;
            --success-color: #22c55e;
            --warning-color: #eab308;
            --danger-color: #ef4444;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
        }

        /* Layout */
        body {
            min-height: 100vh;
            background-color: #f1f5f9;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--dark-color);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1040;
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-brand {
            height: var(--topbar-height);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar.collapsed .welcome-text {
            display: none;
        }

        .sidebar-menu {
            padding: 1rem 0;
            height: calc(100vh - var(--topbar-height));
            overflow-y: auto;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
        }

        .sidebar-item {
            padding: 0.75rem 1.5rem;
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            white-space: nowrap;
        }

        .sidebar.collapsed .sidebar-item {
            padding: 0.75rem;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-item span {
            display: none;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            color: var(--light-color);
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-item i {
            font-size: 1.25rem;
            min-width: 24px;
            text-align: center;
            margin-right: 0.75rem;
        }

        .sidebar.collapsed .sidebar-item i {
            margin-right: 0;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
            background: #f8fafc;
            display: flex;
            flex-direction: column;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* Topbar */
        .topbar {
            position: sticky;
            top: 0;
            right: 0;
            left: 0;
            height: var(--topbar-height);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e7eb;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Content Wrapper */
        .content-wrapper {
            flex: 1 0 auto;
            padding: 1.5rem;
        }

        /* Footer */
        .footer {
            flex-shrink: 0;
            background-color: white;
            border-top: 1px solid #e5e7eb;
            padding: 1rem 1.5rem;
            margin-top: 2rem;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-brand img {
            height: 40px;
        }

        .footer-links {
            display: flex;
            gap: 1rem;
        }

        .footer-links a {
            color: var(--dark-color);
            text-decoration: none;
            font-size: 0.875rem;
        }

        .footer a:hover {
            color: var(--primary-color) !important;
        }

        /* Mobile Sidebar */
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
                box-shadow: none;
            }

            .sidebar.show {
                margin-left: 0;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1039;
                display: none;
            }

            .sidebar-backdrop.show {
                display: block;
            }

            .footer {
                text-align: center;
            }

            .footer-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .footer .text-lg-end {
                text-align: center !important;
                margin-top: 1rem;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}"
                        class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                </div>
                <div class="flex-grow-1 ms-3 welcome-text">
                    <div class="text-white small">Selamat datang,</div>
                    <h6 class="mb-0 text-white text-truncate">{{ auth()->user()->name }}</h6>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('tourist.dashboard') }}"
                class="sidebar-item {{ request()->routeIs('tourist.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('tourist.bookings.index') }}"
                class="sidebar-item {{ request()->routeIs('tourist.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span>Pemesanan</span>
            </a>

            <a href="{{ route('tourist.reviews.index') }}"
                class="sidebar-item {{ request()->routeIs('tourist.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i>
                <span>Ulasan</span>
            </a>

            <a href="{{ route('tourist.wishlist.index') }}"
                class="sidebar-item {{ request()->routeIs('tourist.wishlist.*') ? 'active' : '' }}">
                <i class="bi bi-heart"></i>
                <span>Wishlist</span>
            </a>

            <a href="{{ route('tourist.itinerary.index') }}"
                class="sidebar-item {{ request()->routeIs('tourist.itinerary.*') ? 'active' : '' }}">
                <i class="bi bi-map"></i>
                <span>Rencana Perjalanan</span>
            </a>

            <a href="{{ route('tourist.profile') }}"
                class="sidebar-item {{ request()->routeIs('tourist.profile') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span>Profil</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Topbar -->
        <div class="topbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-dark d-lg-none me-2" id="mobileSidebarToggle">
                    <i class="mb-0 bi bi-list h4"></i>
                </button>
                <div>
                    <h4 class="mb-0">@yield('title')</h4>
                    @hasSection('subtitle')
                        <p class="mb-0 text-muted small">@yield('subtitle')</p>
                    @endif
                </div>
            </div>

            <div class="gap-3 d-flex align-items-center">
                @hasSection('actions')
                    @yield('actions')
                @endif

                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('tourist.profile') }}">
                                <i class="bi bi-person me-2"></i> Profil
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                </div>
                <div class="footer-links">
                    <a href="#">Tentang Kami</a>
                    <a href="#">Kebijakan Privasi</a>
                    <a href="#">Syarat & Ketentuan</a>
                    <a href="#">Hubungi Kami</a>
                </div>
                <div class="footer-copyright">
                    &copy; {{ date('Y') }} Wisata Muna Barat. All rights reserved.
                </div>
            </div>
        </footer>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            // Mobile Sidebar Toggle
            function toggleMobileSidebar() {
                sidebar.classList.toggle('show');
                sidebarBackdrop.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            }

            mobileSidebarToggle?.addEventListener('click', toggleMobileSidebar);
            sidebarBackdrop?.addEventListener('click', toggleMobileSidebar);

            // Close sidebar when clicking menu items on mobile
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            sidebarItems.forEach(item => {
                item.addEventListener('click', () => {
                    if (window.innerWidth <= 991.98) {
                        toggleMobileSidebar();
                    }
                });
            });

            // Handle resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991.98) {
                    sidebar.classList.remove('show');
                    sidebarBackdrop.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
