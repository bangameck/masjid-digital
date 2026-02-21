<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Halaman Publik' }} - {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap');
        .work-sans-all { font-family: 'Work Sans', sans-serif !important; }

        /* Style Floating Label Global untuk Guest */
        .floating-input:focus ~ label,
        .floating-input:not(:placeholder-shown) ~ label {
            transform: translateY(-1.4rem) scale(0.75);
            background-color: white;
            padding: 0 8px;
            color: #10b981;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="h-full work-sans-all">
    @php
        $isLogin = request()->routeIs('login');
    @endphp

    <div class="min-h-screen flex justify-center bg-slate-100 relative {{ $isLogin ? 'items-center overflow-hidden' : 'py-10 overflow-y-auto' }}">

        <div class="fixed inset-0 z-0">
            @php $setting = \App\Models\AppSetting::first(); @endphp

            @if($setting && $setting->bg_login_path)
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
