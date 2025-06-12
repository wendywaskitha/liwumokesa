<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Visit Liwu Mokesa')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .hero-section {
            background-size: cover;
            background-position: center;
            height: 70vh;
            position: relative;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.7));
        }

        .destination-card {
            transition: all 0.3s ease;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .destination-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            backdrop-filter: blur(5px);
        }

        /* Override Bootstrap colors with amber theme */
        .bg-primary {
            background-color: #F59E0B !important;
        }

        .btn-primary {
            background-color: #F59E0B !important;
            border-color: #F59E0B !important;
        }

        .btn-primary:hover {
            background-color: #D97706 !important;
            border-color: #D97706 !important;
        }

        .text-primary {
            color: #F59E0B !important;
        }

        .btn-outline-primary {
            color: #F59E0B !important;
            border-color: #F59E0B !important;
        }

        .btn-outline-primary:hover {
            background-color: #F59E0B !important;
            color: white !important;
        }

        /* Footer styling */
        footer a {
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Header (Using partial) -->
    @include('partials.landing._header')

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer (Using partial) -->
    @include('partials.landing._footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
