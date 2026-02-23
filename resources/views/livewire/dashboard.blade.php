<div class="space-y-6 md:space-y-8 pb-10">
    <script src="{{ asset('assets/js/apexcharts/3.41.0/apexcharts.min.js') }}"></script>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-6 md:p-8 rounded-4xl border border-slate-100 shadow-sm relative overflow-hidden">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-{{ $theme_color }}-50 rounded-full blur-3xl opacity-50"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <span class="px-3 py-1 rounded-full bg-{{ $theme_color }}-50 text-{{ $theme_color }}-600 text-[10px] font-black uppercase tracking-widest border border-{{ $theme_color }}-100">
                    Control Center
                </span>
                <span class="text-xs font-semibold text-slate-400">
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    @if($jadwal && $jadwal->tanggal_hijriah) <span class="mx-1 text-slate-300">•</span> <span class="text-{{ $theme_color }}-500">{{ $jadwal->tanggal_hijriah }}</span> @endif
                </span>
            </div>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">
                Assalamualaikum, <span class="text-{{ $theme_color }}-600">{{ Auth::user()->name ?? 'Admin' }}</span>
            </h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Ringkasan statistik dan kendali layar Masjid Digital hari ini.</p>
        </div>

        <div class="flex items-center gap-4 bg-slate-50/50 px-5 py-4 rounded-2xl border border-slate-200 relative z-10 backdrop-blur-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tema Warna Display TV</span>
            <div class="flex gap-2.5">
                @foreach(['emerald', 'blue', 'violet', 'rose', 'amber'] as $color)
                    <button wire:click="changeTheme('{{ $color }}')"
                            class="w-7 h-7 rounded-full transition-all duration-300 {{ $theme_color === $color ? 'ring-4 ring-'.$color.'-200 scale-110 shadow-md' : 'hover:scale-110 border-2 border-white shadow-sm' }} bg-{{ $color }}-500"
                            title="{{ ucfirst($color) }}">
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
        <div class="bg-linear-to-br from-{{ $theme_color }}-500 to-{{ $theme_color }}-700 p-6 rounded-4xl shadow-lg shadow-{{ $theme_color }}-500/30 text-white relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <p class="text-[10px] font-black uppercase tracking-widest text-{{ $theme_color }}-100 mb-1">Total Saldo Kas</p>
                <h3 class="text-3xl font-black tracking-tighter drop-shadow-md">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                <div class="mt-4 pt-4 border-t border-white/20 flex justify-between items-center text-xs font-bold text-{{ $theme_color }}-100">
                    <span>Bulan Ini:</span>
                    <span class="flex items-center gap-1 text-white"><svg class="w-3 h-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>{{ number_format($pemasukanBulanIni, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-4xl border border-slate-100 shadow-sm flex flex-col justify-center relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <div class="p-2 bg-emerald-50 text-emerald-500 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Bulan Ini</span>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-0.5">Pemasukan</p>
            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Rp {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-white p-6 rounded-4xl border border-slate-100 shadow-sm flex flex-col justify-center relative overflow-hidden">
            <div class="flex justify-between items-start mb-2">
                <div class="p-2 bg-rose-50 text-rose-500 rounded-xl"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg></div>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Bulan Ini</span>
            </div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-0.5">Pengeluaran</p>
            <h3 class="text-2xl font-black text-slate-800 tracking-tight">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</h3>
        </div>

        <div class="bg-slate-900 p-6 rounded-4xl shadow-xl text-white relative overflow-hidden group">
            <div class="absolute -right-4 -bottom-4 text-white/5 w-24 h-24 rotate-12 group-hover:rotate-45 transition-transform duration-700"><svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg></div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Jadwal Hari Ini</p>
                    @if($jadwal)
                        <div class="flex items-end justify-between">
                            <div><p class="text-xs text-{{ $theme_color }}-400 font-bold uppercase">Dzuhur</p><p class="text-2xl font-black">{{ \Carbon\Carbon::parse($jadwal->dzuhur)->format('H:i') }}</p></div>
                            <div class="text-right"><p class="text-xs text-{{ $theme_color }}-400 font-bold uppercase">Ashar</p><p class="text-2xl font-black">{{ \Carbon\Carbon::parse($jadwal->ashar)->format('H:i') }}</p></div>
                        </div>
                    @else
                        <span class="text-rose-400 font-bold text-xs bg-rose-400/10 px-2 py-1 rounded-md">Data Kosong</span>
                    @endif
                </div>
                <a href="/live-display" target="_blank" class="mt-4 bg-white/10 hover:bg-white/20 transition-colors py-2 rounded-xl text-xs font-bold uppercase tracking-widest text-center flex justify-center items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-{{ $theme_color }}-500 animate-pulse"></span> Buka Display TV
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="md:col-span-2 bg-white p-6 md:p-8 rounded-4xl border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-black text-slate-800 tracking-tight">Trend Keuangan</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">1 Tahun Terakhir (12 Bulan)</p>
                </div>
            </div>
            <div id="financeChart" class="w-full h-75"></div>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-4xl border border-slate-100 shadow-sm flex flex-col">
            <h3 class="text-lg font-black text-slate-800 tracking-tight mb-1">Rincian Bulan Ini</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Berdasarkan Sub-Kategori</p>

            <div class="flex-1 overflow-y-auto pr-2 space-y-4 custom-scrollbar">
                @forelse($subKategori as $sub)
                    @php
                        $isPemasukan = $sub->kategori === 'pemasukan';
                        $color = $isPemasukan ? 'emerald' : 'rose';
                    @endphp
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100 hover:border-{{ $color }}-200 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-{{ $color }}-100 text-{{ $color }}-600 rounded-xl">
                                @if($isPemasukan)
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                @else
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-700 capitalize">{{ $sub->sub_kategori ?: 'Lainnya' }}</p>
                                <p class="text-[9px] font-black uppercase text-slate-400 tracking-widest">{{ $sub->kategori }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-black text-{{ $color }}-600">Rp {{ number_format($sub->total, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-slate-400 opacity-50">
                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4M12 20V4"></path></svg>
                        <p class="text-xs font-bold uppercase tracking-widest">Belum ada transaksi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div>
        <div class="flex justify-between items-end mb-4 px-2">
            <div>
                <h3 class="text-lg font-black text-slate-800 tracking-tight">Struktur Organisasi</h3>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pengurus Masjid & Staf Aktif</p>
            </div>
        </div>

        @if($pengurus->count() > 0)
            <div class="flex gap-4 overflow-x-auto pb-6 pt-2 px-2 custom-scrollbar snap-x">
                @foreach($pengurus as $person)
                    <div class="snap-start shrink-0 w-45 bg-white border border-slate-100 rounded-4xl p-5 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-300 flex flex-col items-center text-center relative overflow-hidden group">
                        <div class="absolute top-0 inset-x-0 h-16 bg-linear-to-b from-{{ $theme_color }}-50 to-white transition-colors"></div>

                        <div class="relative z-10 w-20 h-20 rounded-full border-4 border-white shadow-md overflow-hidden mb-3 bg-white">
                            @if($person->foto_path)
                                <img src="{{ Storage::url($person->foto_path) }}" class="w-full h-full object-cover">
                            @else
                                <svg class="w-full h-full text-slate-300 bg-slate-100 p-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            @endif
                        </div>

                        <span class="px-2.5 py-0.5 rounded-md bg-{{ $theme_color }}-50 text-{{ $theme_color }}-600 text-[9px] font-black uppercase tracking-widest border border-{{ $theme_color }}-100 mb-2">
                            {{ $person->jabatan }}
                        </span>
                        <h4 class="text-sm font-bold text-slate-800 leading-tight mb-1 truncate w-full" title="{{ $person->nama }}">{{ $person->nama }}</h4>

                        @if($person->no_hp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $person->no_hp) }}" target="_blank" class="flex items-center justify-center gap-1.5 text-[10px] text-slate-500 hover:text-emerald-500 font-bold tracking-wider mt-auto pt-3 transition-colors w-full border-t border-slate-50">
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12.01 2.014c-5.516 0-9.998 4.48-9.998 9.996 0 1.954.542 3.84 1.516 5.48L2 22l4.646-1.528c1.58.91 3.398 1.436 5.362 1.436 5.514 0 9.996-4.482 9.996-9.996 0-5.516-4.48-9.996-9.996-9.996zM17.34 16.51c-.24.686-1.378 1.25-1.928 1.32-.514.062-1.15.116-3.414-.82-2.73-1.13-4.512-3.95-4.66-4.148-.148-.198-1.114-1.488-1.114-2.84 0-1.352.706-2.022.956-2.288.248-.268.54-.336.722-.336.182 0 .362.002.524.01.18.006.422-.07.658.498.248.6 0 .68.804 1.96.114.288.19.614.038.918-.148.304-.226.496-.452.762-.224.266-.474.576-.68.762-.224.22-.456.46-.204.894.252.434 1.122 1.85 2.408 3.004 1.66 1.492 3.048 1.956 3.486 2.152.44.198.694.164.954-.13.26-.296 1.116-1.306 1.416-1.756.3-.45.598-.376 1.004-.22.406.156 2.576 1.214 3.018 1.436.442.222.738.336.844.524.11.188.11 1.082-.13 1.768z"/></svg>
                                {{ $person->no_hp }}
                            </a>
                        @else
                            <span class="text-[10px] text-slate-400 mt-auto pt-3 font-medium border-t border-slate-50 w-full">-</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white border border-slate-100 rounded-4xl p-8 text-center shadow-sm">
                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <p class="text-slate-500 font-bold">Belum ada data pengurus yang diinputkan.</p>
                <p class="text-xs text-slate-400 mt-1">Isi data di menu "Data Pengurus" untuk menampilkannya di sini.</p>
            </div>
        @endif
    </div>

    <div class="mt-4">
        <h3 class="font-black text-lg text-slate-800 mb-4 px-2 tracking-tight">Aksi Cepat</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="/keuangan-masjid" wire:navigate class="flex flex-col items-center justify-center p-6 bg-white border border-slate-200 rounded-4xl hover:border-{{ $theme_color }}-500 hover:bg-{{ $theme_color }}-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
                <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-{{ $theme_color }}-500 group-hover:text-white transition-colors shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <span class="font-bold text-sm text-slate-600 group-hover:text-{{ $theme_color }}-700">Input Kas</span>
            </a>

            <a href="/running-text" wire:navigate class="flex flex-col items-center justify-center p-6 bg-white border border-slate-200 rounded-4xl hover:border-{{ $theme_color }}-500 hover:bg-{{ $theme_color }}-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
                <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-{{ $theme_color }}-500 group-hover:text-white transition-colors shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <span class="font-bold text-sm text-slate-600 group-hover:text-{{ $theme_color }}-700">Running Text</span>
            </a>

            <a href="/banner" wire:navigate class="flex flex-col items-center justify-center p-6 bg-white border border-slate-200 rounded-4xl hover:border-{{ $theme_color }}-500 hover:bg-{{ $theme_color }}-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
                <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-{{ $theme_color }}-500 group-hover:text-white transition-colors shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="font-bold text-sm text-slate-600 group-hover:text-{{ $theme_color }}-700">Kelola Banner</span>
            </a>

            <a href="/settings" wire:navigate class="flex flex-col items-center justify-center p-6 bg-white border border-slate-200 rounded-4xl hover:border-{{ $theme_color }}-500 hover:bg-{{ $theme_color }}-50 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 group">
                <div class="w-14 h-14 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mb-3 group-hover:bg-{{ $theme_color }}-500 group-hover:text-white transition-colors shadow-inner">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <span class="font-bold text-sm text-slate-600 group-hover:text-{{ $theme_color }}-700">Pengaturan</span>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const chartData = @json($chartData);

            const colorIn = '#10b981'; // Emerald
            const colorOut = '#f43f5e'; // Rose

            const options = {
                series: [{
                    name: 'Pemasukan',
                    data: chartData.pemasukan
                }, {
                    name: 'Pengeluaran',
                    data: chartData.pengeluaran
                }],
                chart: {
                    type: 'area',
                    height: 300,
                    toolbar: { show: false },
                    fontFamily: 'inherit',
                    animations: { enabled: true, easing: 'easeinout', speed: 800 }
                },
                colors: [colorIn, colorOut],
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                xaxis: {
                    categories: chartData.categories,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { colors: '#94a3b8', fontWeight: 600 } }
                },
                yaxis: {
                    labels: {
                        style: { colors: '#94a3b8', fontWeight: 600 },
                        formatter: (value) => {
                            if(value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'M';
                            if(value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'K';
                            return value;
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                legend: { position: 'top', horizontalAlign: 'right' },
                tooltip: { y: { formatter: function (val) { return "Rp " + val.toLocaleString('id-ID') } } }
            };

            const chart = new ApexCharts(document.querySelector("#financeChart"), options);
            chart.render();
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
    </style>
</div>
