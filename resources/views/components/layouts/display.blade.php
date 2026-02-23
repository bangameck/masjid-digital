<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Masjid Digital Display' }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

   <style>
        /* Perbaikan: Gunakan font-family, bukan family saja */
        .font-arab { font-family: 'Amiri Quran', serif !important; }
        .font-sans { font-family: 'Work Sans', sans-serif !important; }

        /* Sembunyikan kursor mouse saat idle */
        body { cursor: none; }
    </style>
</head>
<body class="bg-black text-white overflow-hidden w-screen h-screen">
    {{ $slot }}
    @livewireScripts
</body>
</html>
