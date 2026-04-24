<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'unsafe-inline' 'unsafe-eval' https://app.midtrans.com https://app.sandbox.midtrans.com https://cdn.tailwindcss.com https://unpkg.com; connect-src 'self' https://app.midtrans.com https://app.sandbox.midtrans.com;">
    <title>E-Menu App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    @yield('header')
</head>
<body class="bg-gray-50 text-gray-800 antialiased font-sans">
    <div class="max-w-4xl mx-auto md:max-w-2xl lg:max-w-4xl xl:max-w-6xl bg-white min-h-screen shadow relative mt-0 sm:mt-10 mb-0 sm:mb-10 sm:rounded-xl overflow-hidden pb-32">
        <!-- Header -->
        <header class="bg-gradient-to-r from-orange-400 to-red-500 text-white p-6 rounded-b-3xl shadow-lg relative z-10 w-full mb-6">
            <div class="flex items-center gap-4">
                <img src="{{ asset('images/logo.png') }}" alt="Moody Foodielicious Logo" class="h-16 w-auto rounded shadow-sm bg-white/20 p-1">
                <div>
                    <h1 class="text-3xl font-extrabold tracking-tight">E-Menu Order</h1>
                    <p class="text-sm font-medium mt-1 opacity-90">Pesan makanan favoritmu disini</p>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="w-full relative z-0 px-4 sm:px-6">
            @if(session('success'))
                <div class="max-w-md mx-auto mb-4 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-md mx-auto mb-4 p-4 bg-red-100 text-red-700 rounded-xl border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
