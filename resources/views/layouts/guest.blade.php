<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS CDN (Ensures styling works even if Vite breaks) -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light">
        <div class="container d-flex flex-column justify-content-center align-items-center min-vh-100 py-5">
            <div class="mb-4 text-center">
                <a href="/">
                    <img src="{{ asset('images/logo.png') }}" alt="Moody Foodielicious Logo" class="img-fluid rounded shadow-sm" style="max-height: 120px; object-fit: contain; background: #fff; padding: 10px;">
                </a>
            </div>

            <div class="card shadow-lg w-100 border-0" style="max-width: 450px; border-radius: 1rem; overflow: hidden;">
                <div class="card-body p-4 p-sm-5 bg-white">
                    {{ $slot }}
                </div>
            </div>
            
            <div class="mt-4 text-center text-muted small">
                &copy; {{ date('Y') }} Moody Foodielicious. All rights reserved.
            </div>
        </div>
    </body>
</html>
