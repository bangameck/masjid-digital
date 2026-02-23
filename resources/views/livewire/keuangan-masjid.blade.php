<div class="space-y-8 md:space-y-8 pb-10 work-sans-all relative">
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap'); */

        .work-sans-all,
        .work-sans-all * {
            font-family: 'Work Sans', sans-serif !important;
            font-variant-numeric: tabular-nums;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
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

        /* Custom TomSelect */
        .ts-control {
            border-radius: 0.75rem !important;
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            padding: 0.6rem 1rem !important;
            font-family: 'Work Sans', sans-serif !important;
            font-size: 0.875rem !important;
            font-weight: 700 !important;
            color: #334155 !important;
        }

        .ts-control.focus {
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2) !important;
            border-color: #10b981 !important;
        }

        .ts-dropdown {
            border-radius: 0.75rem !important;
            font-family: 'Work Sans', sans-serif !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid #e2e8f0 !important;
        }

        .ts-dropdown .option {
            padding: 0.5rem 1rem !important;
        }

        .ts-dropdown .active {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
        }

        .ts-wrapper.single .ts-control:after {
            right: 1rem !important;
        }
    </style>

    <link href="{{ asset('assets/css/tom-select/tom-select.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/tom-select/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/js/apexcharts/3.41.0/apexcharts.min.js') }}"></script>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div
            class="bg-slate-900 p-8 rounded-[2.5rem] relative overflow-hidden shadow-2xl flex flex-col justify-center gap-4 min-h-45">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-500/20 rounded-full blur-3xl -mr-10 -mt-10"></div>

            <div>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest mb-1 flex items-center gap-2">
                    Total Keseluruhan Kas
                    <span class="bg-emerald-500/20 text-emerald-300 px-2 py-0.5 rounded text-[8px] tracking-normal">All
                        Time</span>
                </p>
                <h2 class="text-3xl md:text-4xl font-black text-white tracking-tighter">
                    Rp {{ number_format($saldoAllTime, 0, ',', '.') }}
                </h2>
            </div>

            <div class="w-full h-px bg-white/10 my-1"></div>
            <div>
                <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest mb-1 flex items-center gap-2">
                    Saldo Akhir Periode
                    <span class="bg-white/10 text-slate-300 px-2 py-0.5 rounded text-[8px] tracking-normal">
                        @if ($filter_mode == 'rentang')
                            {{ \Carbon\Carbon::parse($start_date)->translatedFormat('d M') }} -
                            {{ \Carbon\Carbon::parse($end_date)->translatedFormat('d M Y') }}
                        @else
                            Akhir
                            {{ \Carbon\Carbon::create((int) $tahun_filter, (int) $bulan_filter, 1)->translatedFormat('F Y') }}
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
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">Pemasukan (Periode Ini)
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
            <p class="text-[10px] text-slate-400 font-bold mt-4">Periode:
                @if ($filter_mode == 'rentang')
                    {{ \Carbon\Carbon::parse($start_date)->format('d M y') }} -
                    {{ \Carbon\Carbon::parse($end_date)->format('d M y') }}
                @else
                    {{ \Carbon\Carbon::create()->month((int) $bulan_filter)->translatedFormat('F Y') }}
                @endif
            </p>
        </div>

        <div
            class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col justify-between min-h-45">
            <div>
                <div class="flex justify-between items-start">
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">Pengeluaran (Periode Ini)
                    </p>
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
            <p class="text-[10px] text-slate-400 font-bold mt-4">Periode:
                @if ($filter_mode == 'rentang')
                    {{ \Carbon\Carbon::parse($start_date)->format('d M y') }} -
                    {{ \Carbon\Carbon::parse($end_date)->format('d M y') }}
                @else
                    {{ \Carbon\Carbon::create()->month((int) $bulan_filter)->translatedFormat('F Y') }}
                @endif
            </p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 px-2 gap-4">
                <h3 class="font-black text-lg text-slate-800 uppercase tracking-tight shrink-0">Statistik Arus Kas</h3>

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
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
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
                        PDF
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

        @if ($canEdit)
            <div class="bg-emerald-500 p-8 rounded-[2.5rem] shadow-xl shadow-emerald-200 flex flex-col justify-center items-center text-center text-white relative overflow-hidden group cursor-pointer"
                wire:click="create">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('{{ asset('assets/images/patterns/arabesque.png') }}'); background-repeat: repeat;">
                </div>
                <div class="relative z-10 space-y-6">
                    <div
                        class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto backdrop-blur-sm group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black uppercase tracking-tight">Catat Transaksi</h3>
                        <p class="text-emerald-100 text-xs font-medium mt-1">Input pemasukan atau pengeluaran baru</p>
                    </div>
                    <button
                        class="w-full bg-white text-emerald-600 py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-50 transition-all shadow-lg">
                        Tambah Data
                    </button>
                </div>
            </div>
        @else
            <div
                class="bg-emerald-500 p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col justify-center items-center min-h-45">
                <div
                    class="flex items-center gap-3 bg-amber-50 border border-amber-100 px-5 py-3 rounded-2xl shadow-sm animate-fade-in">
                    <div class="p-2 bg-amber-500 rounded-lg text-white shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 15v2m0-8V7m0 0v2m-9 1l1 1h16l1-1v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4z"></path>
                            <circle cx="12" cy="11" r="9" stroke="currentColor" stroke-width="2">
                            </circle>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="text-[10px] font-black text-amber-600 uppercase tracking-widest leading-none mb-1">Mode
                            Lihat Saja</span>
                        <p class="text-[10px] font-bold text-amber-700 leading-tight">
                            Role kamu : <span
                                class="underline uppercase decoration-amber-300 decoration-2">{{ auth()->user()->role }}</span>.
                            Kamu hanya dapat melihat informasi dan tidak dapat mengubah inputan nya.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl overflow-hidden relative">
        <div class="px-10 py-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
            <h3 class="font-black text-xl text-slate-800 uppercase tracking-tight">Manajemen Rekening Kas</h3>
            <span
                class="text-xs font-bold text-slate-400 bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm">Tampil
                di Display TV</span>
        </div>

        @if (session()->has('rekening_message'))
            <div
                class="bg-emerald-50 text-emerald-600 px-10 py-3 text-xs font-black uppercase tracking-widest border-b border-emerald-100">
                <span class="inline-block animate-pulse mr-2">●</span> {{ session('rekening_message') }}
            </div>
        @endif

        <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-1 bg-slate-50 p-6 rounded-4xl border border-slate-100">
                <h4 class="font-black text-sm text-emerald-600 uppercase tracking-widest mb-6">
                    {{ $isEditRekening ? 'Edit Data Rekening' : 'Tambah Rekening Baru' }}
                </h4>
                @if ($canEdit)
                    <form wire:submit.prevent="saveRekening" class="space-y-6">
                        <div class="relative z-0 w-full group">
                            <input type="text" wire:model="nama_bank" id="nama_bank"
                                class="block py-2.5 px-0 w-full text-sm text-slate-900 bg-transparent border-0 border-b-2 border-slate-300 appearance-none focus:outline-none focus:ring-0 focus:border-emerald-500 peer font-bold"
                                placeholder=" " required />
                            <label for="nama_bank"
                                class="peer-focus:font-black absolute text-[10px] text-slate-500 uppercase tracking-widest duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-left peer-focus:left-0 peer-focus:text-emerald-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nama
                                Bank (Cth: BSI)</label>
                            @error('nama_bank')
                                <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="relative z-0 w-full group">
                            <input type="text" wire:model="nomor_rekening" id="nomor_rekening"
                                class="block py-2.5 px-0 w-full text-sm text-slate-900 bg-transparent border-0 border-b-2 border-slate-300 appearance-none focus:outline-none focus:ring-0 focus:border-emerald-500 peer font-black tabular-nums tracking-widest"
                                placeholder=" " required />
                            <label for="nomor_rekening"
                                class="peer-focus:font-black absolute text-[10px] text-slate-500 uppercase tracking-widest duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-left peer-focus:left-0 peer-focus:text-emerald-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nomor
                                Rekening</label>
                            @error('nomor_rekening')
                                <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="relative z-0 w-full group">
                            <input type="text" wire:model="nama_akun" id="nama_akun"
                                class="block py-2.5 px-0 w-full text-sm text-slate-900 bg-transparent border-0 border-b-2 border-slate-300 appearance-none focus:outline-none focus:ring-0 focus:border-emerald-500 peer font-bold"
                                placeholder=" " required />
                            <label for="nama_akun"
                                class="peer-focus:font-black absolute text-[10px] text-slate-500 uppercase tracking-widest duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-left peer-focus:left-0 peer-focus:text-emerald-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Atas
                                Nama (A/N)</label>
                            @error('nama_akun')
                                <span class="text-xs text-rose-500 font-bold block mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex gap-2 pt-2">
                            <button type="submit"
                                class="flex-1 bg-emerald-500 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-emerald-600 transition-all">
                                {{ $isEditRekening ? 'Update' : 'Simpan' }}
                            </button>
                            @if ($isEditRekening)
                                <button type="button" wire:click="resetRekeningForm"
                                    class="flex-1 bg-slate-200 text-slate-500 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-300 transition-all">
                                    Batal
                                </button>
                            @endif
                        </div>
                    </form>
                @else
                    <div
                        class="flex items-center gap-3 bg-amber-50 border border-amber-100 px-4 py-4 rounded-2xl shadow-sm animate-fade-in mt-4">
                        <div class="p-2 bg-amber-500 rounded-lg text-white shadow-sm shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 15v2m0-8V7m0 0v2m-9 1l1 1h16l1-1v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4z"></path>
                                <circle cx="12" cy="11" r="9" stroke="currentColor" stroke-width="2">
                                </circle>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-[9px] font-black text-amber-600 uppercase tracking-widest leading-none mb-1">Terkunci</span>
                            <p class="text-[10px] font-bold text-amber-700 leading-tight">
                                Hanya Superadmin, Operator, dan Bendahara yang dapat mengubah data rekening.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="md:col-span-2 space-y-3">
                @forelse($rekenings as $rek)
                    <div
                        class="bg-white border border-slate-100 rounded-2xl p-4 flex justify-between items-center shadow-sm hover:shadow-md transition-shadow group">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center shrink-0 border border-emerald-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-black text-slate-800 text-sm uppercase">{{ $rek->nama_bank }}</h5>
                                <p
                                    class="text-emerald-600 font-black tracking-widest tabular-nums font-mono text-base">
                                    {{ $rek->nomor_rekening }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">A/N:
                                    {{ $rek->nama_akun }}</p>
                            </div>
                        </div>
                        @if ($canEdit)
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="editRekening({{ $rek->id }})"
                                    class="p-2 bg-slate-100 rounded-lg text-slate-500 hover:bg-emerald-500 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                </button>
                                <button wire:click="deleteRekening({{ $rek->id }})"
                                    class="p-2 bg-rose-50 rounded-lg text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Read
                                Only</span>
                        @endif
                    </div>
                @empty
                    <div
                        class="h-full flex flex-col items-center justify-center text-slate-300 py-10 border-2 border-dashed border-slate-100 rounded-2xl">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                        <p class="font-black text-xs uppercase tracking-widest">Belum ada data rekening</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl overflow-hidden min-h-125 relative mt-8">
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
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        @if (session()->has('message'))
            <div
                class="absolute top-4 right-1/2 translate-x-1/2 bg-emerald-500 text-white px-8 py-4 rounded-2xl font-black text-xs shadow-xl z-50 animate-bounce">
                {{ session('message') }}
            </div>
        @endif

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
                                <div class="font-black text-slate-700 text-sm">{{ $item->tanggal->format('d M Y') }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase">
                                    {{ $item->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-bold text-slate-700 text-xs">{{ $item->sumber_atau_tujuan }}</div>
                                @if ($item->bukti_path)
                                    <button wire:click="showImage('{{ Storage::url($item->bukti_path) }}')"
                                        class="text-[9px] text-emerald-500 font-black uppercase mt-1 inline-flex items-center gap-1 hover:underline cursor-pointer">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg> Lihat Bukti
                                    </button>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <span
                                    class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $item->kategori == 'pemasukan' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-500' }}">
                                    {{ $item->kategori }}
                                </span>
                                <div class="mt-2 text-[10px] font-bold text-slate-500 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg> {{ $item->sub_kategori }}
                                </div>
                            </td>
                            <td
                                class="px-6 py-5 text-right font-mono font-bold text-sm {{ $item->kategori == 'pemasukan' ? 'text-emerald-600' : 'text-rose-500' }}">
                                {{ $item->kategori == 'pemasukan' ? '+' : '-' }} Rp
                                {{ number_format($item->nominal, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if ($canEdit)
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="edit({{ $item->id }})"
                                            class="p-2 bg-slate-100 rounded-lg text-slate-500 hover:bg-emerald-500 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="deleteId({{ $item->id }})"
                                            class="p-2 bg-rose-50 rounded-lg text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Read
                                        Only</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="px-8 py-10 text-center font-black text-slate-300 uppercase tracking-widest text-xs">
                                Belum ada transaksi di periode ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-8 border-t border-slate-50 flex justify-center">{{ $transaksi->links() }}</div>
    </div>

    @if ($isModalOpen)
        <div
            class="fixed inset-0 z-9999 flex items-center justify-center bg-slate-900/90 backdrop-blur-sm p-4 overflow-y-auto">
            <div class="bg-white rounded-[2.5rem] p-8 w-full max-w-lg shadow-2xl relative mt-10 md:mt-0">
                <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight mb-6">
                    {{ $isEditMode ? 'Edit' : 'Catat' }} Transaksi
                </h3>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Tanggal</label>
                            <input type="date" wire:model="tanggal"
                                class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2 text-sm font-bold text-slate-700 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kategori</label>
                            <select wire:model="kategori"
                                class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2 text-sm font-bold text-slate-700 focus:ring-emerald-500">
                                <option value="pemasukan">Pemasukan (+)</option>
                                <option value="pengeluaran">Pengeluaran (-)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div wire:ignore x-data="{
                            ts: null,
                            init() {
                                this.ts = new TomSelect(this.$refs.selectInput, {
                                    create: true,
                                    sortField: { field: 'text', direction: 'asc' },
                                    onChange: (value) => {
                                        $wire.set('sub_kategori', value);
                                    }
                                });
                                let currentVal = $wire.get('sub_kategori');
                                if (currentVal) {
                                    this.ts.addOption({ value: currentVal, text: currentVal });
                                    this.ts.setValue(currentVal, true);
                                }
                            }
                        }">
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Jenis
                                Transaksi</label>
                            <select x-ref="selectInput" placeholder="Ketik / Pilih...">
                                <option value="">Pilih / Ketik...</option>
                                @foreach ($availableSubKategoris as $sub)
                                    <option value="{{ $sub }}">{{ $sub }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label
                                class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Sumber
                                / Tujuan</label>
                            <input type="text" wire:model="sumber_atau_tujuan" placeholder="Contoh: Hamba Allah"
                                class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2 text-sm font-bold text-slate-700 focus:ring-emerald-500">
                        </div>
                    </div>

                    <div class="flex justify-between -mt-2">
                        <div class="w-1/2 pr-2">
                            @error('sub_kategori')
                                <span class="text-[10px] text-rose-500 font-bold block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-1/2 pl-2">
                            @error('sumber_atau_tujuan')
                                <span class="text-[10px] text-rose-500 font-bold block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div x-data="{
                        displayValue: '',
                        init() {
                            this.$watch('$wire.nominal', (value) => {
                                if (value) this.displayValue = this.formatRupiah(value);
                            });
                            if ($wire.nominal) this.displayValue = this.formatRupiah($wire.nominal);
                        },
                        formatRupiah(val) {
                            if (!val) return '';
                            return val.toString().replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                        },
                        updateNominal(e) {
                            let raw = e.target.value.replace(/\./g, '');
                            $wire.set('nominal', raw);
                            this.displayValue = this.formatRupiah(raw);
                        }
                    }">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Nominal
                            (Rp)</label>
                        <input type="text" x-model="displayValue" @input="updateNominal" placeholder="0"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2 text-lg font-black text-slate-800 focus:ring-emerald-500">
                        @error('nominal')
                            <span class="text-xs text-rose-500 font-bold">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Keterangan
                            (Opsional)</label>
                        <textarea wire:model="keterangan" rows="2"
                            class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-2 text-sm font-bold text-slate-700 focus:ring-emerald-500"></textarea>
                    </div>

                    <div x-data="{
                        isCompressing: false,
                        originalSize: 0,
                        compressedSize: 0,
                        progress: 0,
                        formatBytes(bytes) {
                            if (bytes === 0) return '0 Bytes';
                            const k = 1024;
                            const sizes = ['Bytes', 'KB', 'MB'];
                            const i = Math.floor(Math.log(bytes) / Math.log(k));
                            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                        },
                        async compressImage(event) {
                            const file = event.target.files[0];
                            if (!file) return;
                            this.originalSize = file.size;
                            this.isCompressing = true;
                            this.progress = 10;
                            const reader = new FileReader();
                            reader.readAsDataURL(file);
                            reader.onload = (e) => {
                                const img = new Image();
                                img.src = e.target.result;
                                img.onload = () => {
                                    const canvas = document.createElement('canvas');
                                    let width = img.width;
                                    let height = img.height;
                                    const maxSize = 800;
                                    if (width > height) {
                                        if (width > maxSize) {
                                            height *= maxSize / width;
                                            width = maxSize;
                                        }
                                    } else {
                                        if (height > maxSize) {
                                            width *= maxSize / height;
                                            height = maxSize;
                                        }
                                    }
                                    canvas.width = width;
                                    canvas.height = height;
                                    const ctx = canvas.getContext('2d');
                                    ctx.drawImage(img, 0, 0, width, height);
                                    let quality = 0.9;
                                    let dataUrl = canvas.toDataURL('image/jpeg', quality);
                                    this.progress = 50;
                                    while (dataUrl.length > 80 * 1024 && quality > 0.1) {
                                        quality -= 0.1;
                                        dataUrl = canvas.toDataURL('image/jpeg', quality);
                                    }
                                    this.progress = 80;
                                    fetch(dataUrl).then(res => res.blob()).then(blob => {
                                        this.compressedSize = blob.size;
                                        const fileNew = new File([blob], file.name, { type: 'image/jpeg' });
                                        @this.upload('bukti', fileNew, () => {
                                            this.isCompressing = false;
                                            this.progress = 100;
                                        });
                                    });
                                }
                            }
                        }
                    }">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1 block">Bukti
                            Foto / Struk</label>

                        <div class="flex gap-4 items-start">
                            <div
                                class="relative w-20 h-20 bg-slate-100 rounded-xl overflow-hidden border border-slate-200 group shrink-0">
                                @if ($bukti)
                                    <img src="{{ $bukti->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif($bukti_path)
                                    <img src="{{ Storage::url($bukti_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <input type="file" accept="image/*" @change="compressImage($event)"
                                    class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                <div x-show="isCompressing"
                                    class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center text-white z-20">
                                    <svg class="w-6 h-6 animate-spin mb-1 text-emerald-400"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 space-y-2" x-show="originalSize > 0">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kompresi</p>
                                <div class="flex items-center gap-2 text-[10px] font-bold">
                                    <span class="text-rose-500" x-text="formatBytes(originalSize)"></span>
                                    <svg class="w-3 h-3 text-slate-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                    <span class="text-emerald-500" x-text="formatBytes(compressedSize)"></span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-emerald-500 h-full transition-all duration-300"
                                        :style="'width: ' + progress + '%'"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button wire:click="closeModal"
                        class="flex-1 py-3 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50">Batal</button>
                    <button wire:click="{{ $isEditMode ? 'update' : 'store' }}"
                        class="flex-1 py-3 bg-emerald-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 shadow-lg">Simpan</button>
                </div>
            </div>
        </div>
    @endif

    @if ($isDeleteModalOpen)
        <div class="fixed inset-0 z-9999 flex items-center justify-center bg-rose-900/90 backdrop-blur-sm p-4">
            <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-md text-center shadow-2xl">
                <div
                    class="w-20 h-20 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Hapus Transaksi?</h3>
                <p class="text-slate-500 font-medium text-sm mb-8">Data yang dihapus tidak dapat dikembalikan.</p>
                <div class="flex gap-4">
                    <button wire:click="closeModal"
                        class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50">Batal</button>
                    <button wire:click="delete"
                        class="flex-1 py-4 bg-rose-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200">Ya,
                        Hapus</button>
                </div>
            </div>
        </div>
    @endif

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
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-4">Bukti Transaksi Digital</p>
            </div>
        </div>
    @endif

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
                            },
                            {
                                name: 'Pengeluaran',
                                data: data.expense
                            }
                        ]
                    });
                }
            });
        });
    </script>
</div>
