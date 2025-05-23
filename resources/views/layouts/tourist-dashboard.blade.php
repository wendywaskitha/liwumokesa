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
        }

        /* Layout */
        body {
            min-height: 100vh;
            background-color: #f1f5f9;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--dark-color);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 1040;
            transition: all 0.3s;
        }

        .sidebar-brand {
            padding: 1.5rem 2rem;
            color: var(--light-color);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-item {
            padding: 0.75rem 2rem;
            color: #94a3b8;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            color: var(--light-color);
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar-item i {
            margin-right: 0.75rem;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
            background: #f8fafc;
            transition: all 0.3s;
        }

        /* Cards */
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        /* Tables */
        .table-container {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -280px;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <h4 class="mb-0">{{ config('app.name') }}</h4>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('tourist.dashboard') }}"
               class="sidebar-item {{ request()->routeIs('tourist.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>

            <a href="{{ route('tourist.bookings.index') }}"
               class="sidebar-item {{ request()->routeIs('tourist.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                Pemesanan
            </a>

            <a href="{{ route('tourist.reviews.index') }}"
               class="sidebar-item {{ request()->routeIs('tourist.reviews.*') ? 'active' : '' }}">
                <i class="bi bi-star"></i>
                Ulasan
            </a>

            <a href="{{ route('tourist.wishlist.index') }}"
               class="sidebar-item {{ request()->routeIs('tourist.wishlist.*') ? 'active' : '' }}">
                <i class="bi bi-heart"></i>
                Wishlist
            </a>

            <a href="{{ route('tourist.itinerary.index') }}"
               class="sidebar-item {{ request()->routeIs('tourist.itinerary.*') ? 'active' : '' }}">
                <i class="bi bi-map"></i>
                Rencana Perjalanan
            </a>

            <a href="{{ route('tourist.profile') }}"
               class="sidebar-item {{ request()->routeIs('tourist.profile') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                Profil
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">@yield('title')</h4>
                @hasSection('subtitle')
                    <p class="mb-0 text-muted">@yield('subtitle')</p>
                @endif
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
                        <li><hr class="dropdown-divider"></li>
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

        <!-- Page Content -->
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Sidebar on Mobile
        document.querySelector('.sidebar-toggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });
    </script>

    @stack('scripts')
</body>
</html>
