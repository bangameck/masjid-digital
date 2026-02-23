<div class="space-y-8 md:space-y-8 pb-10 work-sans-all relative pt-6 md:pt-0">
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap'); */

        .work-sans-all,
        .work-sans-all * {
            font-family: 'Work Sans', sans-serif !important;
            font-variant-numeric: tabular-nums;
        }

        .apexcharts-tooltip {
            background: #fff !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            border-radius: 12px !important;
        }

        .apexcharts-tooltip-title {
            background: #f8fafc !important;
            border-bottom: 1px solid #e2e8f0 !important;
            font-family: 'Work Sans', sans-serif !important;
            font-weight: 800 !important;
        }

        .apexcharts-text {
            font-family: 'Work Sans', sans-serif !important;
            font-weight: 600 !important;
            fill: #64748b !important;
        }

        /* Hilangkan Scrollbar tapi tetap bisa di-scroll */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <script src="{{ asset('assets/js/apexcharts/3.41.0/apexcharts.min.js') }}"></script>

    <div class="space-y-8 md:space-y-8 pb-10 work-sans-all relative pt-6 md:pt-0">
        <style>
            /* @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap'); */
            .work-sans-all,
            .work-sans-all * {
                font-family: 'Work Sans', sans-serif !important;
                font-variant-numeric: tabular-nums;
            }

            .apexcharts-tooltip {
                background: #fff !important;
                border-color: #e2e8f0 !important;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
                border-radius: 12px !important;
            }

            .apexcharts-tooltip-title {
                background: #f8fafc !important;
                border-bottom: 1px solid #e2e8f0 !important;
                font-family: 'Work Sans', sans-serif !important;
                font-weight: 800 !important;
            }

            .apexcharts-text {
                font-family: 'Work Sans', sans-serif !important;
                font-weight: 600 !important;
                fill: #64748b !important;
            }

            /* Hilangkan Scrollbar tapi tetap bisa di-scroll */
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .hide-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }
        </style>

        <script src="{{ asset('assets/js/apexcharts/3.41.0/apexcharts.min.js') }}"></script>

        <div
            class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-sm flex flex-col md:flex-row items-center gap-6 relative overflow-hidden mb-8">
            <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl -mr-10 -mt-10"></div>

            <div class="relative shrink-0">
                <div class="absolute inset-0 bg-emerald-400 rounded-full blur-md opacity-50 animate-pulse"></div>

                @if ($setting && $setting->logo_path)
                    <img src="{{ Storage::url($setting->logo_path) }}"
                        class="relative w-24 h-24 md:w-28 md:h-28 object-cover rounded-full ring-4 ring-emerald-400 shadow-[0_0_25px_rgba(16,185,129,0.5)] bg-white z-10">
                @else
                    <div
                        class="relative w-24 h-24 md:w-28 md:h-28 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center ring-4 ring-emerald-400 shadow-[0_0_25px_rgba(16,185,129,0.5)] z-10">
                        <svg class="w-12 h-12 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                @endif
            </div>

            <div class="text-center md:text-left flex-1 space-y-2 z-10">
                <h1 class="text-2xl md:text-4xl font-black text-slate-800 tracking-tight uppercase leading-none">
                    {{ $setting->nama_masjid ?? 'MASJID DIGITAL' }}
                </h1>
                <p class="text-slate-500 font-bold text-xs md:text-sm max-w-2xl leading-relaxed">
                    {{ $setting->alamat ?? 'Alamat masjid belum diatur di menu pengaturan.' }}
                </p>
                <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-2">
                    <span
                        class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                        Laporan Transparan
                    </span>
                    <span
                        class="px-3 py-1 bg-slate-50 text-slate-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-slate-100">
                        {{ $setting->kota_nama ?? 'Pekanbaru, Riau' }}
                    </span>
                </div>
            </div>

            <div class="shrink-0 z-10">
                <a href="{{ route('login') }}"
                    class="group px-6 py-3 bg-slate-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all hover:bg-emerald-500 flex items-center gap-2 shadow-xl shadow-slate-200">
                    <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                        </path>
                    </svg>
                    Portal Pengurus
                </a>
            </div>
        </div>
        <div
            class="flex items-center gap-4 w-full md:w-auto px-4 py-4 bg-slate-900 rounded-[2rem] border border-white/5 shadow-2xl mb-8 group overflow-hidden relative">
            <div
                class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
            </div>

            <div
                class="w-12 h-12 bg-white/10 text-emerald-400 rounded-2xl flex items-center justify-center border border-white/10 shrink-0 relative z-10">
                <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="relative z-10">
                <h2 class="text-xl font-black text-white tracking-tight uppercase leading-none">Transparansi Keuangan
                </h2>
                <p
                    class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1.5 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                    Laporan Terbuka Untuk Jamaah
                </p>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-sm overflow-hidden p-6 md:p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-black text-lg md:text-xl text-slate-800 uppercase tracking-tight">Salurkan Infaq &
                    Sedekah
                </h3>
                @if ($rekenings->count() > 1)
                    <div
                        class="flex items-center gap-1 text-[9px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-50 px-3 py-1.5 rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                        Geser
                    </div>
                @endif
            </div>

            <div class="flex overflow-x-auto snap-x snap-mandatory hide-scrollbar gap-4 md:gap-6 pb-2">
                @forelse($rekenings as $rek)
                    <div
                        class="snap-center shrink-0 w-[85vw] md:w-[350px] bg-gradient-to-br from-slate-50 to-white border border-slate-200 rounded-[2rem] p-6 hover:-translate-y-1 transition-transform shadow-sm">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center border border-emerald-200 shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-black text-slate-800 text-sm uppercase leading-tight">
                                    {{ $rek->nama_bank }}
                                </h5>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">A/N:
                                    {{ $rek->nama_akun }}</p>
                            </div>
                        </div>
                        <p
                            class="text-emerald-600 font-black tracking-widest tabular-nums font-mono text-xl md:text-2xl bg-emerald-50/50 p-3 rounded-xl text-center border border-emerald-100/50">
                            {{ $rek->nomor_rekening }}
                        </p>
                    </div>
                @empty
                    <div
                        class="w-full py-8 text-center bg-slate-50 rounded-[2rem] border border-dashed border-slate-200">
                        <p class="font-black text-xs text-slate-400 uppercase tracking-widest">Belum ada data rekening
                            publik</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div
                class="bg-slate-900 p-8 rounded-[2.5rem] relative overflow-hidden shadow-2xl flex flex-col justify-center gap-4 min-h-45">
                <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl -mr-10 -mt-10">
                </div>

                <div>
                    <p
                        class="text-slate-400 font-bold text-[10px] uppercase tracking-widest mb-1 flex items-center gap-2">
                        Total Keseluruhan Kas
                        <span
                            class="bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded text-[8px] tracking-normal">All
                            Time</span>
                    </p>
                    <h2 class="text-3xl md:text-4xl font-black text-white tracking-tighter">
                        Rp {{ number_format($saldoAllTime, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="w-full h-px bg-white/10 my-1"></div>

                <div>
                    <p
                        class="text-slate-400 font-bold text-[10px] uppercase tracking-widest mb-1 flex items-center gap-2">
                        Saldo Akhir Periode
                        <span class="bg-white/10 text-slate-300 px-2 py-0.5 rounded text-[8px] tracking-normal">
                            @if ($filter_mode == 'rentang')
                                {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d M') }} -
                                {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d M Y') }}
                            @else
                                Akhir
                                {{ \Carbon\Carbon::create((int) ($tahun_filter ?? date('Y')), (int) $bulan_filter, 1)->translatedFormat('F Y') }}
                            @endif
                        </span>
                    </p>
                    <div class="flex items-center gap-3 mt-1">
                        <h2 class="text-xl md:text-2xl font-black text-slate-200 tracking-tighter leading-none">
                            Rp {{ number_format($saldoAkhir, 0, ',', '.') }}
                        </h2>
                        <div class="bg-white/10 px-2.5 py-1 rounded-lg backdrop-blur-md border border-white/5 shrink-0">
                            <span class="text-[9px] text-emerald-400 font-black uppercase">
                                {{ empty($sub_kategori_filter) ? 'Semua Jenis' : $sub_kategori_filter }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col justify-between min-h-45">
                <div>
                    <div class="flex justify-between items-start">
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">Pemasukan (Periode
                            Ini)
                        </p>
                        <div class="p-2 bg-emerald-50 rounded-xl text-emerald-500"><svg class="w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                            </svg></div>
                    </div>
                    <h2 class="text-3xl font-black text-emerald-600 tracking-tighter mt-2">
                        + Rp {{ number_format($pemasukanPeriodeIni, 0, ',', '.') }}
                    </h2>
                </div>
                <p class="text-[10px] text-slate-400 font-bold mt-4 flex items-center gap-2">
                    Periode:
                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md">
                        @if ($filter_mode == 'rentang')
                            {{ \Carbon\Carbon::parse($start_date)->format('d M y') }} -
                            {{ \Carbon\Carbon::parse($end_date)->format('d M y') }}
                        @else
                            {{ \Carbon\Carbon::create((int) ($tahun_filter ?? date('Y')), (int) $bulan_filter, 1)->translatedFormat('F Y') }}
                        @endif
                    </span>
                </p>
            </div>

            <div
                class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col justify-between min-h-45">
                <div>
                    <div class="flex justify-between items-start">
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">Pengeluaran (Periode
                            Ini)</p>
                        <div class="p-2 bg-rose-50 rounded-xl text-rose-500"><svg class="w-5 h-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                            </svg></div>
                    </div>
                    <h2 class="text-3xl font-black text-rose-500 tracking-tighter mt-2">
                        - Rp {{ number_format($pengeluaranPeriodeIni, 0, ',', '.') }}
                    </h2>
                </div>
                <p class="text-[10px] text-slate-400 font-bold mt-4 flex items-center gap-2">
                    Periode:
                    <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md">
                        @if ($filter_mode == 'rentang')
                            {{ \Carbon\Carbon::parse($start_date)->format('d M y') }} -
                            {{ \Carbon\Carbon::parse($end_date)->format('d M y') }}
                        @else
                            {{ \Carbon\Carbon::create((int) ($tahun_filter ?? date('Y')), (int) $bulan_filter, 1)->translatedFormat('F Y') }}
                        @endif
                    </span>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 px-2 gap-4">
                    <h3 class="font-black text-lg text-slate-800 uppercase tracking-tight shrink-0">Statistik Arus Kas
                    </h3>

                    <div class="flex flex-wrap items-center gap-2">
                        <select wire:model.live="sub_kategori_filter"
                            class="bg-emerald-50 text-emerald-700 border-none rounded-xl text-xs font-bold py-2 focus:ring-0 cursor-pointer max-w-35 truncate">
                            <option value="">Semua Jenis</option>
                            @foreach ($availableSubKategoris as $sub)
                                <option value="{{ $sub }}">{{ $sub }}</option>
                            @endforeach
                        </select>

                        <div class="h-6 w-px bg-slate-200 mx-1"></div>

                        <button wire:click="exportPdf" wire:loading.attr="disabled"
                            class="bg-slate-900 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-700 transition-all flex items-center gap-2 shadow-lg shrink-0">
                            <svg wire:loading wire:target="exportPdf" class="animate-spin h-3 w-3 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <svg wire:loading.remove wire:target="exportPdf" class="w-4 h-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Laporan PDF
                        </button>

                        <div class="h-6 w-px bg-slate-200 mx-1"></div>

                        <select wire:model.live="filter_mode"
                            class="bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-700 py-2 focus:ring-0 cursor-pointer">
                            <option value="bulan">Per Bulan</option>
                            <option value="rentang">Rentang Waktu</option>
                        </select>

                        @if ($filter_mode == 'rentang')
                            <div class="flex items-center gap-1 bg-slate-50 rounded-xl px-2 border border-slate-100">
                                <input type="date" wire:model.live="start_date"
                                    class="bg-transparent border-none text-xs font-bold text-slate-700 py-2 focus:ring-0 w-[110px]">

                                <span class="text-slate-400 text-xs font-bold">-</span>

                                <input type="date" wire:model.live="end_date" min="{{ $start_date }}"
                                    max="{{ \Carbon\Carbon::parse($start_date)->addDays(31)->format('Y-m-d') }}"
                                    class="bg-transparent border-none text-xs font-bold text-slate-700 py-2 focus:ring-0 w-[110px]">
                            </div>
                        @else
                            <select wire:model.live="bulan_filter"
                                class="bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-700 py-2 focus:ring-0 cursor-pointer">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}">
                                        {{ \Carbon\Carbon::create()->month((int) $m)->translatedFormat('F') }}</option>
                                @endforeach
                            </select>
                            <select wire:model.live="tahun_filter"
                                class="bg-slate-50 border-none rounded-xl text-xs font-bold text-slate-700 py-2 focus:ring-0 cursor-pointer">
                                @foreach (range(date('Y') - 2, date('Y') + 2) as $y)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>

                <div id="financeChart" wire:ignore></div>
            </div>

            <div
                class="bg-emerald-500 p-8 rounded-[2.5rem] shadow-xl shadow-emerald-200 flex flex-col justify-center items-center text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('{{ asset('assets/images/patterns/arabesque.png') }}'); background-repeat: repeat;">
                </div>
                <div class="relative z-10 space-y-4">
                    <div
                        class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto backdrop-blur-sm border border-white/30">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tight">Amanah & Transparan</h3>
                        <p class="text-emerald-50 text-xs font-medium mt-2 leading-relaxed">
                            Halaman ini didedikasikan agar seluruh jamaah dapat memantau langsung arus kas masjid. Insya
                            Allah dana umat dikelola dengan transparan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl overflow-hidden min-h-125 relative">
            <div
                class="px-10 py-8 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/30">
                <h3 class="font-black text-xl text-slate-800 uppercase tracking-tight">Riwayat Transaksi</h3>

                <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                    <select wire:model.live="sub_kategori_table_filter"
                        class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold w-full md:w-48 focus:ring-emerald-500 cursor-pointer text-emerald-700">
                        <option value="">Filter Jenis (Semua)</option>
                        @foreach ($availableSubKategoris as $sub)
                            <option value="{{ $sub }}">{{ $sub }}</option>
                        @endforeach
                    </select>

                    <div class="relative w-full md:w-auto">
                        <input wire:model.live="search" type="text" placeholder="Cari keterangan..."
                            class="pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold w-full md:w-64 focus:ring-emerald-500">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="text-slate-400 text-[9px] uppercase tracking-[0.2em] font-black border-b border-slate-50 select-none">
                            <th class="px-8 py-6 cursor-pointer group hover:text-emerald-500 transition-colors"
                                wire:click="sortBy('tanggal')">
                                <div class="flex items-center gap-2">
                                    Tanggal
                                    <svg class="w-3 h-3 {{ $sortColumn == 'tanggal' ? 'text-emerald-500' : 'text-slate-200 group-hover:text-emerald-300 transition-colors' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($sortColumn == 'tanggal' && $sortDirection == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        @elseif($sortColumn == 'tanggal' && $sortDirection == 'desc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M19 9l-7 7-7-7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        @endif
                                    </svg>
                                </div>
                            </th>

                            <th class="px-6 py-6 cursor-pointer group hover:text-emerald-500 transition-colors"
                                wire:click="sortBy('sumber_atau_tujuan')">
                                <div class="flex items-center gap-2">
                                    Keterangan / Sumber
                                    <svg class="w-3 h-3 {{ $sortColumn == 'sumber_atau_tujuan' ? 'text-emerald-500' : 'text-slate-200 group-hover:text-emerald-300 transition-colors' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($sortColumn == 'sumber_atau_tujuan' && $sortDirection == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        @elseif($sortColumn == 'sumber_atau_tujuan' && $sortDirection == 'desc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M19 9l-7 7-7-7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        @endif
                                    </svg>
                                </div>
                            </th>

                            <th class="px-6 py-6 text-center cursor-pointer group hover:text-emerald-500 transition-colors"
                                wire:click="sortBy('kategori')">
                                <div class="flex items-center justify-center gap-2">
                                    Jenis
                                    <svg class="w-3 h-3 {{ $sortColumn == 'kategori' ? 'text-emerald-500' : 'text-slate-200 group-hover:text-emerald-300 transition-colors' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($sortColumn == 'kategori' && $sortDirection == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        @elseif($sortColumn == 'kategori' && $sortDirection == 'desc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M19 9l-7 7-7-7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        @endif
                                    </svg>
                                </div>
                            </th>

                            <th class="px-8 py-6 text-right cursor-pointer group hover:text-emerald-500 transition-colors"
                                wire:click="sortBy('nominal')">
                                <div class="flex items-center justify-end gap-2">
                                    Nominal
                                    <svg class="w-3 h-3 {{ $sortColumn == 'nominal' ? 'text-emerald-500' : 'text-slate-200 group-hover:text-emerald-300 transition-colors' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($sortColumn == 'nominal' && $sortDirection == 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 15l7-7 7 7"></path>
                                        @elseif($sortColumn == 'nominal' && $sortDirection == 'desc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M19 9l-7 7-7-7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        @endif
                                    </svg>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($transaksi as $item)
                            <tr class="hover:bg-slate-50 transition-all">
                                <td class="px-8 py-5">
                                    <div class="font-black text-slate-700 text-sm">
                                        {{ $item->tanggal->format('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">
                                        {{ $item->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-700 text-xs">{{ $item->sumber_atau_tujuan }}
                                    </div>
                                    @if ($item->bukti_path)
                                        <button wire:click="showImage('{{ Storage::url($item->bukti_path) }}')"
                                            class="text-[9px] text-emerald-500 font-black uppercase mt-1 inline-flex items-center gap-1 hover:underline cursor-pointer">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                </path>
                                            </svg>
                                            Lihat Bukti Lampiran
                                        </button>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span
                                        class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $item->kategori == 'pemasukan' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-500' }}">
                                        {{ $item->kategori }}
                                    </span>
                                    <div
                                        class="mt-2 text-[10px] font-bold text-slate-500 flex items-center justify-center gap-1">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                        {{ $item->sub_kategori }}
                                    </div>
                                </td>
                                <td
                                    class="px-8 py-5 text-right font-mono font-bold text-sm {{ $item->kategori == 'pemasukan' ? 'text-emerald-600' : 'text-rose-500' }}">
                                    {{ $item->kategori == 'pemasukan' ? '+' : '-' }} Rp
                                    {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-8 py-10 text-center font-black text-slate-300 uppercase tracking-widest text-xs">
                                    Belum ada transaksi di periode ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-8 border-t border-slate-50 flex justify-center">{{ $transaksi->links() }}</div>
        </div>

        @if ($showImageModal)
            <div
                class="fixed inset-0 z-[10000] flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-6 animate-fade-in">
                <div class="relative max-w-5xl w-full h-full flex flex-col items-center justify-center">
                    <button wire:click="closeImageModal"
                        class="absolute top-0 right-0 z-50 bg-white/10 p-3 rounded-full text-white hover:bg-rose-500 hover:text-white transition-all shadow-xl backdrop-blur-md border border-white/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                    <img src="{{ $selectedImageUrl }}"
                        class="max-w-full max-h-[90vh] rounded-4xl shadow-2xl object-contain border-4 border-slate-800">
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-4">Bukti Transaksi Digital
                    </p>
                </div>
            </div>
        @endif

        <div
            class="mt-12 pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
            <p class="text-[11px] font-black text-slate-500np tracking-widest">
                &copy; 2026 | Masjid Digital by
                <a href="https://instagram.com/bangameck" target="_blank"
                    class="text-emerald-500 hover:text-emerald-600 hover:underline transition-colors">
                    RadevankaProject
                </a>
            </p>

            <div class="bg-slate-100 px-6 py-3 rounded-2xl flex flex-col md:flex-row items-center gap-3">
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Ingin masjid Anda memiliki
                    sistem transparan seperti ini?</span>
                <a href="https://github.com/bangameck/masjid-digital" target="_blank"
                    class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shadow-lg shadow-emerald-200">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                            d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Download Gratis
                </a>
            </div>
        </div>

        <script>
            document.addEventListener('livewire:initialized', () => {
                var options = {
                    series: [],
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'Work Sans, sans-serif'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    colors: ['#10b981', '#f43f5e'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.2,
                            stops: [0, 90, 100]
                        }
                    },
                    xaxis: {
                        categories: [],
                        labels: {
                            style: {
                                fontFamily: 'Work Sans, sans-serif',
                                fontSize: '10px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: (val) => {
                                return (val / 1000).toFixed(0) + 'k'
                            },
                            style: {
                                fontFamily: 'Work Sans, sans-serif',
                                fontSize: '10px'
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                            }
                        }
                    },
                    noData: {
                        text: 'Memuat Grafik...',
                        align: 'center',
                        verticalAlign: 'middle'
                    }
                };

                var chart = new ApexCharts(document.querySelector("#financeChart"), options);
                chart.render();

                Livewire.on('update-chart', (event) => {
                    const data = event.data || event[0];
                    if (data) {
                        chart.updateOptions({
                            xaxis: {
                                categories: data.labels
                            },
                            series: [{
                                name: 'Pemasukan',
                                data: data.income
                            }, {
                                name: 'Pengeluaran',
                                data: data.expense
                            }]
                        });
                    }
                });
            });
        </script>
    </div>
