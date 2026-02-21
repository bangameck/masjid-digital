<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Dashboard' }} - {{ config('app.name', 'Masjid Digital') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=work-sans:100,200,300,400,500,600,700,800,900" rel="stylesheet" />

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            .work-sans-all,
            .work-sans-all * {
                font-family: 'Work Sans', sans-serif !important;
            }

            .custom-scrollbar::-webkit-scrollbar {
                width: 4px;
                height: 4px;
            }

            .custom-scrollbar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 10px;
            }

            .custom-scrollbar-dark::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.05);
            }

            .page-fade-in {
                animation: fadeIn 0.4s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .floating-input:focus~label,
            .floating-input:not(:placeholder-shown)~label {
                transform: translateY(-1.25rem) scale(0.8);
                background-color: white;
                padding-left: 4px;
                padding-right: 4px;
                color: #10b981;
                font-weight: 800;
                text-transform: uppercase;
                line-height: 1;
                letter-spacing: 0.05em;
            }
        </style>
        @stack('style')
    </head>

    <body class="h-full overflow-hidden text-slate-900 work-sans-all bg-slate-50">

        <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false, confirmLogout: false }">

            <aside class="hidden lg:flex lg:shrink-0">
                <div class="flex flex-col w-72 shadow-2xl overflow-hidden bg-slate-950">
                    @include('layouts.sidebar-content')
                </div>
            </aside>

            <div x-show="sidebarOpen" class="fixed inset-0 z-50 flex lg:hidden" style="display: none;">
                <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm" @click="sidebarOpen = false"></div>
                <div class="relative flex flex-col flex-1 w-full max-w-xs bg-slate-950 page-fade-in">
                    <div class="absolute top-0 right-0 pt-4 -mr-12">
                        <button @click="sidebarOpen = false"
                            class="flex items-center justify-center w-10 h-10 ml-1 rounded-full text-white">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @include('layouts.sidebar-content')
                </div>
            </div>

            <div class="flex flex-col flex-1 min-w-0 overflow-hidden relative">
                <header
                    class="relative z-10 shrink-0 h-20 flex items-center justify-between px-8 bg-white/50 backdrop-blur-md border-b border-slate-100">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true"
                            class="lg:hidden p-2 text-slate-600 rounded-xl hover:bg-white transition-all">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <div class="ml-4 lg:ml-0 text-left">
                            <h2 class="text-sm font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                                Administrator</h2>
                            <p class="text-lg font-black text-slate-800 uppercase tracking-tighter leading-none">
                                {{ $title ?? 'Dashboard' }}
                            </p>
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mt-1.5">
                                {{ \App\Models\AppSetting::first()->nama_masjid ?? 'Masjid Digital' }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6">
                        <a href="/live-display" target="_blank"
                            class="hidden md:flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all duration-300 shadow-sm active:scale-95 group">
                            <svg class="w-4 h-4 text-emerald-500 group-hover:text-white transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-widest">Live Display</span>
                        </a>

                        <div class="hidden md:flex flex-col items-end mr-2" x-data="{ time: '' }"
                            x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu
                                Server</span>
                            <span class="text-sm font-black text-emerald-600 tracking-widest" x-text="time"></span>
                        </div>

                        <div @click="confirmLogout = true" title="Klik untuk Logout"
                            class="w-10 h-10 rounded-2xl bg-slate-100 shadow-sm border border-slate-200 flex items-center justify-center text-emerald-500 font-black text-sm uppercase overflow-hidden cursor-pointer hover:ring-2 hover:ring-rose-500 transition-all">
                            @if (auth()->user()->pengurus && auth()->user()->pengurus->foto_path)
                                <img src="{{ Storage::url(auth()->user()->pengurus->foto_path) }}"
                                    class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            @endif
                        </div>
                    </div>
                </header>

                <main class="flex-1 relative z-10 overflow-y-auto custom-scrollbar-dark p-6 md:p-8 pb-24">
                    <div class="max-w-7xl mx-auto page-fade-in">
                        {{ $slot }}
                    </div>
                    <footer
                        class="fixed bottom-0 right-0 left-0 lg:left-72 bg-white/80 backdrop-blur-md border-t border-slate-100 z-30 px-8 py-4">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-2">
                            <p class="text-xs font-medium text-slate-500">
                                &copy; {{ date('Y') }} | Masjid Digital v{{ env('APP_VERSION', '1.0.0') }}
                            </p>
                            <p class="text-xs font-medium text-slate-500">
                                Developed by
                                <a href="https://instagram.com/bangameck" target="_blank"
                                    class="text-emerald-600 font-bold hover:underline transition-all">
                                    RadevankaProject
                                </a>
                            </p>
                        </div>
                    </footer>
                </main>
            </div>

            <div x-show="confirmLogout" class="fixed inset-0 z-99999 flex items-center justify-center p-4"
                style="display: none;">

                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md" x-show="confirmLogout"
                    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                    @click="confirmLogout = false"></div>

                <div class="bg-white rounded-[3rem] p-10 w-full max-w-sm text-center shadow-2xl relative z-10 border border-slate-100"
                    x-show="confirmLogout" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-8 scale-95">

                    <div
                        class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Logout?</h3>
                    <p class="text-slate-500 font-medium text-xs mb-8 leading-relaxed">Sesi Anda akan diakhiri.
                        Pastikan semua perubahan data telah disimpan.</p>

                    <div class="flex gap-3">
                        <button @click="confirmLogout = false"
                            class="flex-1 py-4 rounded-2xl font-black text-slate-400 text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                        <a href="{{ route('logout') }}"
                            class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all flex items-center justify-center">Keluar</a>
                    </div>
                </div>
            </div>
        </div>

        @livewireScripts
        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('theme-changed', (event) => {
                    window.location.reload();
                });
            });
        </script>
        @stack('scripts')
    </body>

</html>
