<div x-data="displaySystem()" x-init="initSystem()"
    class="relative w-full h-screen font-sans bg-slate-950 text-white overflow-hidden flex flex-col">

    @php
        $activeTheme = $settings->theme;

        $theme = [
            'main' => $activeTheme->main_color ?? '#10b981',
            'dark' => $activeTheme->dark_color ?? '#064e3b',
            'light' => $activeTheme->light_color ?? '#a7f3d0',
        ];

        $globalSpeed = $settings->running_text_speed ?? 5;
        $duration = max(10, 65 - $globalSpeed * 5);
        $apiHost = parse_url($settings->api_jadwal_sholat ?? 'https://api.myquran.com', PHP_URL_HOST);
    @endphp

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Amiri+Quran&display=swap');

        :root {
            --theme-main: {{ $theme['main'] }};
            --theme-dark: {{ $theme['dark'] }};
            --theme-light: {{ $theme['light'] }};
        }

        .bg-theme-dark {
            background-color: var(--theme-dark);
        }

        .bg-theme-main {
            background-color: var(--theme-main);
        }

        .text-theme-main {
            color: var(--theme-main);
        }

        .text-theme-light {
            color: var(--theme-light);
        }

        .border-theme-main {
            border-color: var(--theme-main);
        }

        .shadow-theme-glow {
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.4), 0 0 15px var(--theme-main);
        }

        .shadow-theme-text {
            text-shadow: 0 0 20px var(--theme-main);
        }

        .islamic-pattern {
            background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.03"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }

        .font-arab {
            font-family: 'Amiri Quran', serif;
            font-weight: 400;
        }

        .marquee-preview {
            overflow: hidden;
            display: flex;
            align-items: center;
            position: relative;
            width: 100%;
            height: 100%;
        }

        .marquee-content {
            display: flex;
            gap: 5rem;
            width: max-content;
            animation: marquee linear infinite;
            padding-left: 100%;
        }

        .marquee-item {
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        @keyframes marquee {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        @keyframes spin-slow {
            100% {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }

        .dynamic-stack {
            display: grid;
        }

        .dynamic-stack>* {
            grid-area: 1 / 1;
        }
    </style>

    <div x-show="!started"
        class="absolute inset-0 z-[100] bg-slate-950 flex flex-col items-center justify-center backdrop-blur-3xl">
        <div class="text-center">
            <svg class="w-32 h-32 text-theme-main mx-auto mb-6 animate-pulse" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h1 class="text-5xl font-black text-white uppercase tracking-widest mb-4">Display TV Ready</h1>
            <p class="text-slate-400 mb-8 font-bold tracking-widest">Mode: {{ $tipeTempat }}</p>
            <button @click="startDisplay()"
                class="bg-theme-main text-white px-12 py-6 rounded-full font-black text-2xl uppercase tracking-[0.2em] shadow-theme-glow hover:scale-105 transition-all">
                Mulai
            </button>
        </div>
    </div>

    <div class="absolute inset-0 z-0">
        @if ($settings && $settings->background_image)
            <img src="{{ Storage::url($settings->background_image) }}" class="w-full h-full object-cover opacity-30">
        @else
            <div class="w-full h-full"
                style="background: radial-gradient(ellipse at top right, var(--theme-dark), #0f172a, #000000);"></div>
        @endif
        <div class="absolute inset-0 islamic-pattern"></div>
    </div>

    <header x-show="mode === 'standby'"
        class="relative w-full z-20 flex justify-between items-center px-10 py-6 bg-black/40 backdrop-blur-xl border-b border-white/10 shadow-xl shrink-0">
        <div class="flex items-center gap-6">
            @if ($settings && $settings->logo_path)
                <img src="{{ Storage::url($settings->logo_path) }}"
                    class="w-24 h-24 rounded-full object-cover border-4 border-theme-main shadow-theme-glow shrink-0">
            @endif

            <div class="flex flex-col justify-center">
                <h1 class="text-4xl font-black tracking-tighter uppercase text-white shadow-theme-text leading-none">
                    {{ $settings->nama_masjid ?? 'MASJID DIGITAL' }}
                </h1>
                <p class="text-sm font-medium text-slate-300 mt-1 mb-2 opacity-90 line-clamp-1">
                    {{ $settings->alamat ?? 'Alamat tempat belum dikonfigurasi' }}
                </p>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-theme-light tracking-widest uppercase flex items-center gap-1">
                        <svg class="w-4 h-4 text-theme-main" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $settings->kota_nama ?? 'Pekanbaru' }}
                    </span>
                    <span class="text-slate-600">•</span>
                    <span
                        class="text-[9px] font-black text-slate-400 uppercase tracking-widest bg-white/5 px-2 py-0.5 rounded border border-white/10">
                        {{ $tipeTempat }} Mode
                    </span>
                </div>
            </div>
        </div>
        <div class="text-right">
            <div class="text-6xl font-black text-white tracking-widest leading-none drop-shadow-2xl tabular-nums"
                x-text="time">00:00:00</div>
            <div class="text-xl font-medium text-theme-main mt-2 uppercase tracking-wide">
                <span x-text="dateGregorian"></span> &bull; <span
                    class="text-white">{{ $jadwal->tanggal_hijriah ?? '' }}</span>
            </div>
        </div>
    </header>

    <main class="relative z-10 w-full flex-1 flex justify-center items-center overflow-hidden p-6 md:p-8"
        x-show="mode === 'standby'">
        <div class="w-full h-full flex gap-6 md:gap-8">

            <div class="w-[75%] shrink-0 relative rounded-[2rem] md:rounded-[3rem] overflow-hidden shadow-2xl border border-white/10 bg-black flex flex-col justify-end">

                <div class="absolute inset-0 z-0">
                    @if ($banners->count() > 0)
                        @foreach ($banners as $index => $banner)
                            <div class="absolute inset-0" x-show="activeSlide === {{ $index }}"
                                x-transition.duration.1000ms>
                                <img src="{{ Storage::url($banner->image_path) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    @endif
                </div>

                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent z-10 pointer-events-none">
                </div>

                <div class="relative w-full md:max-w-[100%] p-6 flex flex-col gap-4 z-20 items-start">

                    @if ($contents->count() > 0)
                        <div
                            class="bg-slate-950/50 backdrop-blur-md rounded-[1.5rem] p-4 md:p-5 border border-white/10 shadow-2xl relative w-full overflow-hidden ring-1 ring-white/5">
                            <div class="absolute inset-0 opacity-[0.03] islamic-pattern pointer-events-none"></div>

                            <div class="flex items-center gap-2 mb-3 relative z-10">
                                <span
                                    class="w-2.5 h-2.5 rounded-full bg-theme-main animate-pulse shadow-theme-glow"></span>
                                <h3 class="text-[10px] md:text-xs font-black text-theme-main uppercase tracking-widest">
                                    Mutiara Hikmah
                                </h3>
                            </div>

                            <div class="dynamic-stack w-full relative z-10">
                                @foreach ($contents as $idx => $content)
                                    @php
                                        // Logika Pewarnaan Berdasarkan Kata di Judul atau Kategori
                                        $keyword = strtolower(
                                            ($content->kategori ?? '') . ' ' . ($content->judul ?? ''),
                                        );

                                        if (str_contains($keyword, 'doa')) {
                                            // Tema Doa: Cyan
                                            $badgeStyle = 'bg-cyan-900/40 text-cyan-300 border-cyan-500/30';
                                            $borderStyle = 'border-cyan-500';
                                            $sumberStyle = 'bg-cyan-950/60 text-cyan-400 border-cyan-800';
                                        } elseif (
                                            str_contains($keyword, 'hadits') ||
                                            str_contains($keyword, 'hadist')
                                        ) {
                                            // Tema Hadits: Amber (Kuning)
                                            $badgeStyle = 'bg-amber-900/40 text-amber-300 border-amber-500/30';
                                            $borderStyle = 'border-amber-500';
                                            $sumberStyle = 'bg-amber-950/60 text-amber-400 border-amber-800';
                                        } else {
                                            // Default / Lainnya
                                            $badgeStyle = 'bg-black/40 text-theme-light border-theme-main/30';
                                            $borderStyle = 'border-theme-main';
                                            $sumberStyle = 'bg-white/5 text-theme-main border-white/10';
                                        }
                                    @endphp

                                    <div class="flex flex-col justify-center w-full"
                                        x-show="activeContent === {{ $idx }}"
                                        x-transition:enter="transition ease-out duration-700"
                                        x-transition:enter-start="opacity-0 translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-500"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 -translate-y-4">

                                        <h4
                                            class="text-[10px] font-bold mb-2 inline-block px-2 py-1 rounded-md border w-max {{ $badgeStyle }}">
                                            {{ $content->judul }}
                                        </h4>

                                        @if ($content->teks_arab)
                                            <p class="font-arab text-xl md:text-3xl text-white text-right mb-3 drop-shadow-md"
                                                dir="rtl" style="line-height: 1.8;">{{ $content->teks_arab }}</p>
                                        @endif

                                        <div
                                            class="border-l-2 {{ $borderStyle }} pl-3 bg-gradient-to-r from-black/40 to-transparent py-2 rounded-r-lg">

                                            <p
                                                class="text-slate-300 text-xs md:text-sm italic font-medium leading-relaxed">
                                                "{{ $content->teks_indo }}"
                                            </p>

                                            @if (!empty($content->sumber))
                                                <div
                                                    class="mt-2.5 inline-block px-2 py-0.5 rounded text-[9px] font-black tracking-widest uppercase border {{ $sumberStyle }}">
                                                    HR / Sumber: {{ $content->sumber }}
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($ceramah->count() > 0)
                        <div
                            class="bg-black/60 backdrop-blur-xl rounded-[1rem] md:rounded-[1.2rem] border border-white/20 shadow-xl relative overflow-hidden w-full h-[70px] md:h-[80px] flex items-center px-4 ring-1 ring-white/5">
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-theme-main shadow-theme-glow"></div>

                            <div class="relative w-full flex items-center h-full">
                                @foreach ($ceramah as $idx => $kajian)
                                    @php
                                        $kat = $kajian->kategori;
                                        if (str_contains($kat, 'Jumat')) {
                                            $nameStyle = 'text-emerald-300';
                                            $iconColor = 'text-emerald-400';
                                        } elseif (str_contains($kat, 'Rutin')) {
                                            $nameStyle = 'text-blue-300';
                                            $iconColor = 'text-blue-400';
                                        } elseif (str_contains($kat, 'Tarawih')) {
                                            $nameStyle = 'text-purple-300';
                                            $iconColor = 'text-purple-400';
                                        } elseif (str_contains($kat, 'Idul')) {
                                            $nameStyle = 'text-amber-300';
                                            $iconColor = 'text-amber-400';
                                        } else {
                                            $nameStyle = 'text-white';
                                            $iconColor = 'text-theme-main';
                                        }
                                    @endphp

                                    <div class="absolute inset-0 flex items-center justify-between w-full"
                                        x-show="activeCeramah === {{ $idx }}"
                                        x-transition:enter="transition ease-out duration-700 delay-300"
                                        x-transition:enter-start="opacity-0 translate-x-12"
                                        x-transition:enter-end="opacity-100 translate-x-0"
                                        x-transition:leave="transition ease-in duration-500"
                                        x-transition:leave-start="opacity-100 translate-x-0"
                                        x-transition:leave-end="opacity-0 -translate-x-12">

                                        <div class="flex items-center gap-3 w-[30%]">
                                            <div class="p-2 bg-white/5 rounded-xl border border-white/10 shrink-0">
                                                <svg class="w-5 h-5 {{ $iconColor }}" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p
                                                    class="text-[9px] font-black uppercase text-slate-400 tracking-widest">
                                                    {{ \Carbon\Carbon::parse($kajian->tanggal)->translatedFormat('l, d M Y') }}
                                                </p>
                                                <p
                                                    class="text-[11px] font-bold {{ $nameStyle }} uppercase tracking-widest mt-0.5 truncate">
                                                    {{ $kajian->kategori }}</p>
                                            </div>
                                        </div>

                                        <div class="flex-1 text-center border-l border-r border-white/10 px-4">
                                            <p
                                                class="text-xl md:text-2xl font-black text-white leading-none truncate mb-1">
                                                {{ $kajian->tokoh }}</p>
                                            <p class="text-[10px] text-slate-300 font-medium truncate">
                                                {{ $kajian->judul ?? 'Penceramah / Khatib' }}</p>
                                        </div>

                                        <div class="w-[30%] flex flex-col gap-1 items-end justify-center pl-4">
                                            @if ($kajian->imam)
                                                <div
                                                    class="text-[9px] bg-white/5 px-2 py-0.5 rounded border border-white/10 flex justify-between w-full max-w-[150px]">
                                                    <span class="text-slate-400 font-bold uppercase">Imam</span>
                                                    <span
                                                        class="text-white font-semibold truncate">{{ $kajian->imam }}</span>
                                                </div>
                                            @endif
                                            @if ($kajian->muadzin)
                                                <div
                                                    class="text-[9px] bg-white/5 px-2 py-0.5 rounded border border-white/10 flex justify-between w-full max-w-[150px]">
                                                    <span class="text-slate-400 font-bold uppercase">Muadz</span>
                                                    <span
                                                        class="text-white font-semibold truncate">{{ $kajian->muadzin }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex-1 flex flex-col gap-4 h-full min-w-0">
                <div class="flex-1 flex flex-col gap-2 min-h-0">
                    @php $waktuSholat = ['Imsak', 'Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya']; @endphp
                    @foreach ($waktuSholat as $waktu)
                        @php
                            $field = strtolower($waktu);
                            $jamFormatted = \Carbon\Carbon::parse($jadwal->$field ?? '00:00:00')->format('H:i');
                            $isSunnah = in_array($waktu, ['Imsak']);
                        @endphp

                        <div class="flex-1 relative overflow-hidden rounded-[1.2rem] px-5 flex justify-between items-center border transition-all duration-500"
                            :class="nextPrayerName === '{{ $waktu }}' ?
                                'bg-theme-main border-theme-main scale-105 shadow-theme-glow z-10' :
                                'bg-black/50 backdrop-blur-md {{ $isSunnah ? 'border-amber-500/20 bg-amber-950/20' : 'border-white/10' }}'">

                            <span class="text-lg font-bold uppercase tracking-widest"
                                :class="nextPrayerName === '{{ $waktu }}' ? 'text-white' :
                                    '{{ $isSunnah ? 'text-amber-600' : 'text-slate-400' }}'">{{ $waktu }}</span>

                            <span class="text-3xl font-black tracking-tighter tabular-nums"
                                :class="nextPrayerName === '{{ $waktu }}' ? 'text-white' :
                                    '{{ $isSunnah ? 'text-amber-400' : 'text-theme-main' }}'">{{ $jamFormatted }}</span>

                            <div x-show="nextPrayerName === '{{ $waktu }}'"
                                class="absolute -bottom-4 -right-4 text-7xl text-white opacity-20 rotate-12">
                                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z" />
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div
                    class="bg-theme-dark rounded-[1.5rem] p-5 border border-theme-main/50 shadow-[0_0_40px_rgba(0,0,0,0.8)] shrink-0 relative overflow-hidden h-[120px] flex items-center justify-center group ring-1 ring-white/10">
                    <div
                        class="absolute -right-4 -top-4 text-white/5 w-32 h-32 rotate-12 transition-transform duration-1000 group-hover:rotate-45 pointer-events-none">
                        <svg fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M21 18v1c0 1.1-.9 2-2 2H5c-1.11 0-2-.9-2-2V5c0-1.1.89-2 2-2h14c1.1 0 2 .9 2 2v1h-9c-1.11 0-2 .9-2 2v8c0 1.1.89 2 2 2h9zm-9-2h10V8H12v8zm4-2.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z" />
                        </svg>
                    </div>

                    <div class="dynamic-stack w-full h-full relative z-10">
                        <div x-show="activeRekening === 0" x-transition:enter="transition ease-out duration-700"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-500"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-4"
                            class="flex flex-col items-center justify-center w-full h-full text-center">
                            <h3
                                class="text-[10px] font-black text-theme-light uppercase tracking-[0.2em] mb-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                Total Saldo Kas
                            </h3>
                            <p
                                class="text-[2.2rem] font-black text-white tracking-tighter drop-shadow-lg leading-none">
                                <span class="text-lg opacity-70">Rp</span>
                                {{ number_format($totalSaldo, 0, ',', '.') }}
                            </p>
                        </div>

                        @if ($rekenings->count() > 0)
                            @foreach ($rekenings as $idx => $rek)
                                @php
                                    // Logika Icon Bank yang Lebih Solid (Anti Silang)
                                    $bankName = strtoupper($rek->nama_bank);

                                    // Icon Gedung Bank (Umum/Nasional)
                                    $iconBank =
                                        'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z';

                                    // Icon Kubah/Masjid (Syariah/BSI)
                                    if (str_contains($bankName, 'BSI') || str_contains($bankName, 'SYARIAH')) {
                                        $iconBank =
                                            'M12,7V3H14V2H10V3H12V7C8.13,7.03 5,10.14 5,14V21H19V14C19,10.14 15.87,7.03 12,7M7,19V14C7,11.24 9.24,9 12,9C14.76,9 17,11.24 17,14V19H7Z';
                                    }
                                @endphp
                                <div x-show="activeRekening === {{ $idx + 1 }}" style="display:none;"
                                    x-transition:enter="transition ease-out duration-700 delay-100"
                                    x-transition:enter-start="opacity-0 translate-y-4"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-500"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 -translate-y-4"
                                    class="flex flex-col items-center justify-center w-full h-full text-center px-2">

                                    <div class="flex items-center gap-2 mb-1.5">
                                        <svg class="w-5 h-5 text-theme-main shrink-0" viewBox="0 0 24 24"
                                            fill="currentColor">
                                            <path d="{{ $iconBank }}" />
                                        </svg>
                                        <h3 class="text-[12px] font-black text-white uppercase tracking-widest">
                                            {{ $rek->nama_bank }}</h3>
                                    </div>

                                    <p class="text-[1.8rem] font-black text-theme-main tracking-widest drop-shadow-lg leading-none mb-1.5"
                                        style="font-family: 'Work Sans', sans-serif !important;">
                                        {{ $rek->nomor_rekening }}
                                    </p>

                                    <div class="flex items-center gap-2">
                                        <span
                                            class="px-2 py-0.5 bg-white/5 rounded-md text-[8px] font-bold text-slate-400 uppercase tracking-tighter border border-white/10">Atas
                                            Nama</span>
                                        <p
                                            class="text-[10px] text-white uppercase font-black tracking-wider truncate max-w-[180px]">
                                            {{ $rek->nama_akun }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div x-show="mode === 'menuju_adzan'" style="display: none;"
        class="absolute inset-0 z-50 flex flex-col items-center justify-center bg-black/95 backdrop-blur-3xl">
        <h2 class="text-[4rem] font-bold text-slate-400 uppercase tracking-[0.5em] mb-6">Waktu <span
                x-text="currentPrayerName" class="text-white"></span></h2>
        <p class="text-4xl text-theme-main mb-8 tracking-widest uppercase font-black animate-pulse">Menuju Adzan</p>
        <h1 class="text-[25rem] font-black text-white leading-none tabular-nums shadow-theme-text drop-shadow-[0_0_50px_rgba(16,185,129,0.5)]"
            x-text="countdownAdzanDisplay">00:00</h1>
    </div>

    <div x-show="mode === 'waiting_iqomah'" style="display: none;"
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-black/95 backdrop-blur-3xl">
        <h2 class="text-[4rem] font-bold text-slate-400 uppercase tracking-[0.5em] mb-6 animate-pulse">Waktu <span
                x-text="currentPrayerName" class="text-white"></span> Telah Masuk</h2>
        <p class="text-4xl text-theme-main mb-8 tracking-widest uppercase font-black">Iqomah Dalam:</p>
        <h1 class="text-[25rem] font-black text-white leading-none tabular-nums shadow-theme-text"
            x-text="countdownIqomahDisplay">00:00</h1>
    </div>

    <div x-show="mode === 'sholat'" style="display: none;"
        class="fixed inset-0 z-[100] flex flex-col items-center justify-center bg-black w-full h-full">
        <div class="relative w-96 h-96 mb-16 animate-pulse">
            <div
                class="absolute inset-0 border-[12px] border-rose-600 rounded-full shadow-[0_0_100px_rgba(225,29,72,0.5)]">
            </div>
            <div class="absolute inset-0 flex items-center justify-center"><svg class="w-48 h-48 text-rose-600"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17 1.01L7 1c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-1.99-2-1.99zM17 19H7V5h10v14z" />
                </svg></div>
            <div
                class="absolute top-1/2 left-1/2 w-full h-8 bg-rose-600 -translate-x-1/2 -translate-y-1/2 -rotate-45 rounded-full shadow-[0_0_20px_rgba(0,0,0,0.5)]">
            </div>
        </div>
        <h1 class="text-[7rem] font-black text-white uppercase tracking-[0.2em] mb-8 shadow-2xl">Mohon Tenang</h1>
        <p class="text-[4rem] text-rose-500 font-black uppercase tracking-[0.3em] text-center leading-tight mb-16">
            Matikan / Silent<br>Ponsel Anda</p>
        <div
            class="flex items-center gap-12 bg-white/10 px-12 py-6 rounded-[3rem] border border-white/20 backdrop-blur-md">
            <div class="text-center">
                <p class="text-2xl text-slate-400 font-bold uppercase tracking-widest mb-2">Jam Saat Ini</p>
                <p class="text-5xl font-black text-white tracking-widest tabular-nums" x-text="time">00:00:00</p>
            </div>
            <div class="w-1 h-20 bg-white/20 rounded-full"></div>
            <div class="text-center">
                <p class="text-2xl text-theme-main font-bold uppercase tracking-widest mb-2">Sisa Waktu</p>
                <p class="text-5xl font-black text-white tracking-widest tabular-nums"
                    x-text="countdownSholatDisplay">00:00</p>
            </div>
        </div>
    </div>

    <div x-show="mode === 'standby'" class="relative w-full px-6 pb-6 pt-2 z-20 shrink-0">
        <div
            class="h-20 bg-black/60 backdrop-blur-2xl border border-white/20 rounded-[2.5rem] flex items-center overflow-hidden shadow-[0_15px_40px_rgba(0,0,0,0.6)] ring-1 ring-white/10">
            <div
                class="bg-theme-main h-full flex items-center px-10 z-30 shadow-[10px_0_30px_rgba(0,0,0,0.8)] border-r border-white/20 relative">
                <span class="text-black font-black uppercase tracking-[0.1em] text-2xl relative z-10">Informasi</span>
            </div>
            <div class="marquee-preview flex-1">
                <div class="marquee-content font-black text-4xl uppercase tracking-[0.05em]"
                    style="animation-duration: {{ $duration }}s;">
                    @forelse($runningTexts as $item)
                        @php
                            $marqueeTheme = match ($item->tipe) {
                                'ayat' => ['text' => 'text-cyan-400', 'dot' => 'text-cyan-500'],
                                'hadits' => ['text' => 'text-purple-400', 'dot' => 'text-purple-500'],
                                'ucapan' => ['text' => 'text-amber-400', 'dot' => 'text-amber-500'],
                                default => ['text' => 'text-theme-main', 'dot' => 'text-theme-main'],
                            };
                        @endphp
                        <div class="marquee-item {{ $marqueeTheme['text'] }} drop-shadow-md">

                            <svg class="w-10 h-10 {{ $marqueeTheme['dot'] }} animate-spin-slow shrink-0"
                                viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12,2L14.47,4.53L17.94,3.53L18.94,7.06L22.47,8.53L21.47,12L22.47,15.47L18.94,16.94L17.94,20.47L14.47,19.47L12,22L9.53,19.47L6.06,20.47L5.06,16.94L1.53,15.47L2.53,12L1.53,8.53L5.06,7.06L6.06,3.53L9.53,4.53L12,2Z" />
                            </svg>

                            <span class="px-4">{{ $item->teks }}</span>
                        </div>
                    @empty
                        <div class="marquee-item text-slate-400">
                            <svg class="w-10 h-10 text-slate-500 animate-spin-slow shrink-0" viewBox="0 0 24 24"
                                fill="currentColor">
                                <path
                                    d="M12,2L14.47,4.53L17.94,3.53L18.94,7.06L22.47,8.53L21.47,12L22.47,15.47L18.94,16.94L17.94,20.47L14.47,19.47L12,22L9.53,19.47L6.06,20.47L5.06,16.94L1.53,15.47L2.53,12L1.53,8.53L5.06,7.06L6.06,3.53L9.53,4.53L12,2Z" />
                            </svg>
                            <span class="px-4 normal-case font-bold">Selamat Datang di
                                {{ $settings->nama_masjid ?? 'Masjid Digital' }}.</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <audio id="audio-beep" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
    <audio id="audio-adzan"
        src="{{ isset($settings->path_adzan) && $settings->path_adzan ? Storage::url($settings->path_adzan) : asset('sounds/adzan.mp3') }}"
        preload="auto"></audio>
    <audio id="audio-adzan-subuh"
        src="{{ isset($settings->path_adzan_subuh) && $settings->path_adzan_subuh ? Storage::url($settings->path_adzan_subuh) : asset('sounds/adzan_subuh.mp3') }}"
        preload="auto"></audio>

    <script>
        function displaySystem() {
            return {
                started: false,
                time: '00:00:00',
                dateGregorian: '',
                mode: 'standby',
                nextPrayerName: '',
                currentPrayerName: '',
                activeSlide: 0,
                activeContent: 0,
                activeCeramah: 0,
                activeRekening: 0,
                tipeTempat: '{{ $tipeTempat }}',
                jadwalDB: {{ Illuminate\Support\Js::from($jadwal) }},
                settings: {{ Illuminate\Support\Js::from($settings) }},
                bannersCount: Number("{{ $banners->count() }}"),
                contentsCount: Number("{{ $contents->count() }}"),
                ceramahCount: Number("{{ $ceramah->count() }}"),
                rekeningCount: Number("{{ $rekenings->count() }}"),
                countdownAdzanDisplay: '00:00',
                countdownIqomahDisplay: '00:00',
                countdownSholatDisplay: '00:00',
                durasiSholat: {
                    'Subuh': 20,
                    'Dzuhur': 20,
                    'Ashar': 20,
                    'Maghrib': 20,
                    'Isya': 20,
                    'Jumat': 45
                },

                startDisplay() {
                    this.started = true;
                    let beep = document.getElementById('audio-beep');
                    let adzan = document.getElementById('audio-adzan');
                    if (beep) {
                        beep.play().then(() => beep.pause()).catch(() => {});
                    }
                    if (adzan) {
                        adzan.play().then(() => adzan.pause()).catch(() => {});
                    }
                    if (document.documentElement.requestFullscreen) {
                        document.documentElement.requestFullscreen().catch(() => {});
                    }
                },

                initSystem() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);

                    if (this.bannersCount > 1) {
                        setInterval(() => {
                            this.activeSlide = (this.activeSlide + 1) % this.bannersCount;
                        }, this.settings.durasi_slide_foto || 10000);
                    }
                    if (this.contentsCount > 1) {
                        setInterval(() => {
                            this.activeContent = (this.activeContent + 1) % this.contentsCount;
                        }, 15000);
                    }
                    if (this.ceramahCount > 1) {
                        setInterval(() => {
                            this.activeCeramah = (this.activeCeramah + 1) % this.ceramahCount;
                        }, 12000);
                    }

                    const totalKasSlides = 1 + this.rekeningCount;
                    if (totalKasSlides > 1) {
                        setInterval(() => {
                            this.activeRekening = (this.activeRekening + 1) % totalKasSlides;
                        }, 8000);
                    }

                    setInterval(() => {
                        let now = new Date();
                        if (now.getHours() === 0 && now.getMinutes() === 1 && now.getSeconds() === 0) window
                            .location.reload();
                    }, 1000);
                },

                updateTime() {
                    const now = new Date();
                    this.time = now.toTimeString().substring(0, 8);
                    const options = {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    };
                    this.dateGregorian = now.toLocaleDateString('id-ID', options);
                    this.checkDatabasePrayerTime(now);
                },

                checkDatabasePrayerTime(now) {
                    if (!this.jadwalDB || !this.started) return;

                    const currentSeconds = (now.getHours() * 3600) + (now.getMinutes() * 60) + now.getSeconds();
                    const prayers = [{
                            name: 'Subuh',
                            jamDB: this.jadwalDB.subuh,
                            iqomah: this.settings.iqomah_subuh || 10
                        },
                        {
                            name: 'Dzuhur',
                            jamDB: this.jadwalDB.dzuhur,
                            iqomah: this.settings.iqomah_dzuhur || 10
                        },
                        {
                            name: 'Ashar',
                            jamDB: this.jadwalDB.ashar,
                            iqomah: this.settings.iqomah_ashar || 10
                        },
                        {
                            name: 'Maghrib',
                            jamDB: this.jadwalDB.maghrib,
                            iqomah: this.settings.iqomah_maghrib || 10
                        },
                        {
                            name: 'Isya',
                            jamDB: this.jadwalDB.isya,
                            iqomah: this.settings.iqomah_isya || 10
                        }
                    ];

                    if (now.getDay() === 5 && this.tipeTempat !== 'Mushola') {
                        let dzuhurIndex = prayers.findIndex(p => p.name === 'Dzuhur');
                        if (dzuhurIndex !== -1) {
                            prayers[dzuhurIndex].name = 'Jumat';
                            prayers[dzuhurIndex].iqomah = 5;
                        }
                    }

                    let nextP = null;
                    let activeMode = 'standby';

                    for (let p of prayers) {
                        if (!p.jamDB) continue;

                        let timeParts = p.jamDB.split(':');
                        if (timeParts.length < 2) continue;

                        let h = parseInt(timeParts[0]);
                        let m = parseInt(timeParts[1]);
                        let s = timeParts[2] ? parseInt(timeParts[2]) : 0;

                        let adzanStartSeconds = (h * 3600) + (m * 60) + s;
                        let preAdzanSeconds = adzanStartSeconds - 30;
                        let iqomahEndSeconds = adzanStartSeconds + (p.iqomah * 60);
                        let sholatEndSeconds = iqomahEndSeconds + (this.durasiSholat[p.name] * 60);

                        if (adzanStartSeconds > currentSeconds && !nextP) {
                            nextP = p.name;
                        }

                        if (currentSeconds >= preAdzanSeconds && currentSeconds < adzanStartSeconds) {
                            activeMode = 'menuju_adzan';
                            this.currentPrayerName = p.name;
                            let sisaDetikAdzan = adzanStartSeconds - currentSeconds;
                            this.countdownAdzanDisplay = `00:${sisaDetikAdzan.toString().padStart(2, '0')}`;
                            if ([10, 5, 4, 3, 2, 1].includes(sisaDetikAdzan)) {
                                this.playBeep();
                            }
                            break;
                        }

                        if (currentSeconds === adzanStartSeconds) {
                            this.playAdzan(p.name);
                        }

                        if (currentSeconds >= adzanStartSeconds && currentSeconds < iqomahEndSeconds) {
                            activeMode = 'waiting_iqomah';
                            this.currentPrayerName = p.name;
                            let sisaDetikTotal = iqomahEndSeconds - currentSeconds;
                            let sisaMenit = Math.floor(sisaDetikTotal / 60).toString().padStart(2, '0');
                            let sisaDetik = (sisaDetikTotal % 60).toString().padStart(2, '0');
                            this.countdownIqomahDisplay = `${sisaMenit}:${sisaDetik}`;
                            if (sisaDetikTotal <= 10 && sisaDetikTotal > 0) {
                                this.playBeep();
                            }
                            break;
                        } else if (currentSeconds >= iqomahEndSeconds && currentSeconds < sholatEndSeconds) {
                            activeMode = 'sholat';
                            this.currentPrayerName = p.name;
                            let sisaDetikSholatTotal = sholatEndSeconds - currentSeconds;
                            let sisaMenitSholat = Math.floor(sisaDetikSholatTotal / 60).toString().padStart(2, '0');
                            let sisaDetikSholat = (sisaDetikSholatTotal % 60).toString().padStart(2, '0');
                            this.countdownSholatDisplay = `${sisaMenitSholat}:${sisaDetikSholat}`;
                            break;
                        }
                    }

                    this.mode = activeMode;
                    this.nextPrayerName = nextP || 'Subuh';
                },

                playBeep() {
                    let beep = document.getElementById('audio-beep');
                    if (beep) {
                        beep.volume = 1;
                        beep.currentTime = 0;
                        beep.play().catch(() => {});
                    }
                },

                playAdzan(prayerName) {
                    let adzanId = (prayerName === 'Subuh') ? 'audio-adzan-subuh' : 'audio-adzan';
                    let adzan = document.getElementById(adzanId);

                    if (!adzan || !adzan.src) {
                        adzan = document.getElementById('audio-adzan');
                    }

                    if (adzan) {
                        adzan.volume = 1;
                        adzan.currentTime = 0;
                        adzan.play().catch(() => {});
                    }
                }
            }
        }
    </script>
</div>
