<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-slate-800 antialiased bg-slate-50">
    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="/" class="flex items-center gap-2">
                        <div
                            class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold">
                            U</div>
                        <span class="font-bold text-xl tracking-tight text-slate-900">Unilak Hostel</span>
                    </a>
                </div>
                <div class="hidden md:flex space-x-8">
                    <a href="/" class="text-slate-600 hover:text-blue-600 transition-colors font-medium">Home</a>
                    <a href="/#features"
                        class="text-slate-600 hover:text-blue-600 transition-colors font-medium">Features</a>
                    <a href="/#about" class="text-slate-600 hover:text-blue-600 transition-colors font-medium">About</a>
                    <a href="/#contact"
                        class="text-slate-600 hover:text-blue-600 transition-colors font-medium">Contact</a>
                </div>
                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/student/dashboard') }}"
                                class="text-sm font-semibold text-slate-600 hover:text-blue-600">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600">Log
                                in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-4 py-2 rounded-full bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-24 pb-12 bg-slate-50">
        <div class="w-full sm:max-w-md bg-white shadow-xl overflow-hidden rounded-2xl border border-slate-100">
            {{ $slot }}
        </div>
    </div>
</body>

</html>