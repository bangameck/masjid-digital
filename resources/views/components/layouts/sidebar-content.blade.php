<div class="flex flex-col h-full bg-slate-950">
    <div class="flex flex-col items-center justify-center h-20 bg-slate-950 px-6 border-b border-slate-800 shrink-0">
        <h1 class="text-xl font-black tracking-tighter text-emerald-400 leading-none">MASJID<span class="text-white">DIGITAL</span></h1>
        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-1.5 line-clamp-1 text-center">
            {{ $settings->nama_masjid ?? 'Sistem Manajemen' }}
        </p>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
            Dashboard
        </x-nav-link>

        <div class="pt-4 pb-2">
            <p class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Manajemen Utama</p>
        </div>
        <x-nav-link href="{{ route('settings') }}" :active="request()->routeIs('settings')" icon="settings">
            Pengaturan Masjid
        </x-nav-link>
        <x-nav-link href="{{ route('keuangan.masjid') }}" :active="request()->routeIs('keuangan.masjid')" icon="wallet">Laporan Kas</x-nav-link>
        <x-nav-link href="{{ route('user.pengurus') }}" :active="request()->routeIs('user.pengurus')" icon="users">Data Pengurus</x-nav-link>

        <div class="pt-4 pb-2">
            <p class="px-3 text-[10px] font-black text-slate-500 uppercase tracking-[0.2em]">Live Display TV</p>
        </div>
        <x-nav-link href="{{ route('banner') }}" :active="request()->routeIs('banner')" icon="banner">Banner TV Dislpay</x-nav-link>
        <x-nav-link href="{{ route('jadwal.sholat') }}" :active="request()->routeIs('jadwal.sholat')" icon="calendar">Waktu Sholat</x-nav-link>
        <x-nav-link href="{{ route('schedule') }}" :active="request()->routeIs('schedule')" icon="task">Jadwal Ceramah</x-nav-link>
        <x-nav-link href="{{ route('doa.hadist') }}" :active="request()->routeIs('doa.hadist')" icon="content">Doa & Hadist</x-nav-link>
        <x-nav-link href="{{ route('running.text') }}" :active="request()->routeIs('running.text')" icon="tv">Running Text</x-nav-link>
        <x-nav-link href="{{ route('gallery') }}" :active="request()->routeIs('gallery')" icon="photo">Galeri Dokumentasi</x-nav-link>
    </nav>

    <div class="p-4 border-t border-slate-800 bg-slate-950/50">
        <a href="javascript:void(0)" @click="confirmLogout = true"
           class="flex items-center gap-3 p-2 rounded-2xl bg-white/5 border border-white/5 hover:bg-rose-500/10 hover:border-rose-500/30 transition-all duration-300 group cursor-pointer">

            <div class="w-10 h-10 rounded-xl bg-linear-to-br from-emerald-500 to-emerald-700 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-emerald-900/20 shrink-0 overflow-hidden relative">
                @if(auth()->user()->pengurus && auth()->user()->pengurus->foto_path)
                    <img src="{{ Storage::url(auth()->user()->pengurus->foto_path) }}" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                @endif

                <div class="absolute inset-0 bg-rose-500/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </div>

            <div class="flex-1 min-w-0">
                <p class="text-xs font-black text-white truncate uppercase tracking-wider group-hover:text-rose-50 transition-colors">
                    {{ auth()->user()->name ?? 'Administrator' }}
                </p>
                <div class="text-[10px] font-bold text-slate-400 group-hover:text-rose-400 transition-colors flex items-center gap-1 mt-0.5">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar Sistem
                </div>
            </div>
        </a>
    </div>
</div>
