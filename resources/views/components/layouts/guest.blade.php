@php
    $setting = $settings;
    $isLogin = request()->routeIs('login');

    $masjidName = $setting->nama_masjid ?? 'Masjid Digital';
    $alamat = $setting->alamat ?? 'Lokasi Belum Diatur';
    $kota = $setting->kota_nama ?? 'Indonesia';

    $seoDescription = "Website resmi dan pusat informasi {$masjidName}. Berlokasi di {$alamat}, {$kota}. Dapatkan jadwal sholat akurat, agenda dakwah terkini, dan berbagai layanan digital masjid. Mari bersama memakmurkan masjid untuk umat yang lebih baik.";

    $seoImage =
        $setting && $setting->background_image ? url(Storage::url($setting->background_image)) : asset('favicon.ico'); // Gambar fallback jika belum upload background

    $currentUrl = url()->current();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Beranda' }} - {{ $masjidName }}</title>

        <meta name="description" content="{{ $seoDescription }}">
        <meta name="keywords"
            content="{{ $masjidName }}, jadwal sholat {{ $kota }}, masjid digital, info masjid, dakwah {{ $kota }}, jadwal ceramah, masjid terdekat">
        <meta name="author" content="RadevankaProject">
        <meta name="robots" content="index, follow">

        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ $currentUrl }}">
        <meta property="og:title" content="{{ $title ?? 'Portal Informasi' }} - {{ $masjidName }}">
        <meta property="og:description" content="{{ $seoDescription }}">
        <meta property="og:image" content="{{ $seoImage }}">
        <meta property="og:site_name" content="{{ $masjidName }}">

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ $currentUrl }}">
        <meta name="twitter:title" content="{{ $title ?? 'Portal Informasi' }} - {{ $masjidName }}">
        <meta name="twitter:description" content="{{ $seoDescription }}">
        <meta name="twitter:image" content="{{ $seoImage }}">

        <meta name="theme-color" content="#10b981">
        <link rel="stylesheet" href="{{ asset('assets/css/fonts.css') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap');

            .work-sans-all {
                font-family: 'Work Sans', sans-serif !important;
            } */

            /* Style Floating Label Global untuk Guest */
            .floating-input:focus~label,
            .floating-input:not(:placeholder-shown)~label {
                transform: translateY(-1.4rem) scale(0.75);
                background-color: white;
                padding: 0 8px;
                color: #10b981;
                font-weight: 900;
                text-transform: uppercase;
                letter-spacing: 0.1em;
            }

            .animate-fade-in {
                animation: fadeIn 0.8s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </head>

    <body class="h-full work-sans-all">

        <div
            class="min-h-screen flex justify-center bg-slate-100 relative {{ $isLogin ? 'items-center overflow-hidden' : 'py-10 overflow-y-auto' }}">

            <div class="fixed inset-0 z-0">
                @if ($setting && $setting->bg_login_path)
                    <img src="{{ Storage::url($setting->bg_login_path) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-linear-to-br from-emerald-600 to-slate-900"></div>
                @endif
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px]"></div>
            </div>

            <div class="relative z-10 w-full {{ $isLogin ? 'max-w-md' : 'max-w-7xl' }} p-4 animate-fade-in">
                {{ $slot }}
            </div>
        </div>

        @livewireScripts
    </body>

</html>
