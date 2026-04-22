<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Instrument Sans', sans-serif;
                background-color: #f8f9fa;
            }
            .hero-section {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 2rem;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm py-3 px-4 fixed-top">
            <div class="container">
                <a class="navbar-brand fw-bold d-flex align-items-center" href="/">
                    <x-application-logo style="height: 32px; width: auto;" class="me-2 text-primary" />
                    <span>{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="ms-auto">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary btn-sm px-4">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-dark btn-sm px-3">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-4">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <div class="hero-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h1 class="display-4 fw-bold text-dark mb-4 mt-5">Selamat Datang di E-Menu</h1>
                        <p class="lead text-muted mb-5">Sistem pemesanan menu digital yang modern, cepat, dan mudah digunakan.</p>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <a href="https://laravel.com/docs" class="btn btn-primary btn-lg px-5 py-3 shadow">
                                Dokumentasi
                            </a>
                            <a href="https://github.com/adiyoga27/e-menu" class="btn btn-outline-secondary btn-lg px-5 py-3">
                                GitHub Repository
                            </a>
                        </div>

                        <div class="mt-5 pt-5 text-muted small">
                            Laravel v{{ PHP_VERSION }} (PHP v8.3)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
