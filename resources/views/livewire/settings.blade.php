<div class="space-y-8 pb-20 work-sans-all">
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap'); */

        .work-sans-all,
        .work-sans-all * {
            font-family: 'Work Sans', sans-serif !important;
        }

        /* TomSelect Theme Match */
        .ts-control {
            border-radius: 0.75rem !important;
            background-color: transparent !important;
            border: 1px solid #cbd5e1 !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.875rem !important;
            color: #0f172a !important;
            box-shadow: none !important;
        }

        .ts-control.focus {
            border-color: #10b981 !important;
        }

        .ts-dropdown {
            border-radius: 0.75rem !important;
            font-size: 0.875rem !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
            border: 1px solid #e2e8f0 !important;
            margin-top: 4px;
        }

        .ts-dropdown .active {
            background-color: #ecfdf5 !important;
            color: #059669 !important;
            font-weight: bold;
        }
    </style>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Aplikasi</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Konfigurasi identitas, visual display, dan parameter waktu
                sholat.</p>
        </div>

        @if ($canEdit)
            <button wire:click="save" wire:loading.attr="disabled"
                class="fixed bottom-6 right-6 md:static z-50 flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-full shadow-xl shadow-emerald-500/30 transition-all transform hover:-translate-y-1 active:scale-95">
                <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                    </path>
                </svg>
                <svg wire:loading wire:target="save" class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                <span class="font-black tracking-widest uppercase text-xs">Simpan Pengaturan</span>
            </button>
        @else
            <div
                class="flex items-center gap-3 bg-amber-50 border border-amber-100 px-5 py-3 rounded-2xl shadow-sm animate-fade-in">
                <div class="p-2 bg-amber-500 rounded-lg text-white shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 15v2m0-8V7m0 0v2m-9 1l1 1h16l1-1v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4z"></path>
                        <circle cx="12" cy="11" r="9" stroke="currentColor" stroke-width="2"></circle>
                    </svg>
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest leading-none mb-1">Mode
                        Lihat Saja</span>
                    <p class="text-[11px] font-bold text-amber-700 leading-tight">
                        Role kamu : <span
                            class="underline uppercase decoration-amber-300 decoration-2">{{ auth()->user()->role }}</span>.
                        Kamu hanya
                        dapat melihat informasi dan tidak dapat mengubah inputan nya.
                    </p>
                </div>
            </div>
        @endif
    </div>

    <div class="space-y-2">
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                class="bg-emerald-50 text-emerald-700 px-6 py-4 rounded-2xl border border-emerald-100 flex items-center gap-3 shadow-sm font-bold text-sm">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('media_success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="bg-blue-50 text-blue-700 px-6 py-4 rounded-2xl border border-blue-100 flex items-center gap-3 shadow-sm font-bold text-sm animate-pulse">
                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                {{ session('media_success') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-2 space-y-8">

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                <h3 class="font-black text-xl text-slate-800 mb-8 flex items-center gap-3 tracking-tight">
                    <div class="p-2 bg-emerald-50 rounded-xl text-emerald-500"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg></div>
                    Identitas Tempat
                </h3>

                <div class="mb-8 flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="flex flex-col">
                        <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Tipe Tempat
                            Ibadah</span>
                        <span class="text-sm font-bold text-slate-700 mt-1">Pilih sebutan untuk header Display</span>
                    </div>
                    <div class="flex items-center gap-3 bg-white p-1 rounded-xl shadow-sm border border-slate-200">
                        <button {{ !$canEdit ? 'disabled' : '' }} wire:click="$set('tipe_tempat', 'Masjid')"
                            class="px-6 py-2 rounded-lg text-sm font-black uppercase tracking-widest transition-all {{ $tipe_tempat == 'Masjid' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-50' }}">Masjid</button>
                        <button {{ !$canEdit ? 'disabled' : '' }} wire:click="$set('tipe_tempat', 'Mushola')"
                            class="px-6 py-2 rounded-lg text-sm font-black uppercase tracking-widest transition-all {{ $tipe_tempat == 'Mushola' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-400 hover:bg-slate-50' }}">Mushola</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="relative group">
                        <input type="text" wire:model="nama_masjid" {{ !$canEdit ? 'disabled' : '' }}
                            id="nama_masjid"
                            class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3.5 text-slate-900 font-bold focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent transition-all"
                            placeholder=" " />
                        <label for="nama_masjid"
                            class="absolute top-3.5 left-4 text-slate-500 transition-all duration-200 ease-out pointer-events-none">Nama
                            {{ $tipe_tempat }}</label>
                        @error('nama_masjid')
                            <span
                                class="text-[10px] text-rose-500 font-bold absolute -bottom-5 left-0">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative group">
                        <select wire:model="zona_waktu" {{ !$canEdit ? 'disabled' : '' }} id="zona_waktu"
                            class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3.5 text-slate-900 font-bold focus:border-emerald-500 focus:outline-none focus:ring-0">
                            <option value="Asia/Jakarta">WIB (Asia/Jakarta)</option>
                            <option value="Asia/Makassar">WITA (Asia/Makassar)</option>
                            <option value="Asia/Jayapura">WIT (Asia/Jayapura)</option>
                        </select>
                        <label for="zona_waktu"
                            class="absolute top-3.5 left-4 text-emerald-500 font-black text-[10px] uppercase tracking-widest bg-white px-1 -translate-y-5 transition-all pointer-events-none">Zona
                            Waktu</label>
                    </div>

                    <div class="md:col-span-2 relative group mt-2">
                        <textarea wire:model="alamat" {{ !$canEdit ? 'disabled' : '' }} id="alamat" rows="2"
                            class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3.5 text-slate-900 font-bold focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent transition-all"
                            placeholder=" "></textarea>
                        <label for="alamat"
                            class="absolute top-3.5 left-4 text-slate-500 transition-all duration-200 ease-out pointer-events-none">Alamat
                            Lengkap</label>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden"
                wire:ignore x-data="mapComponent({ lat: @entangle('latitude'), lng: @entangle('longitude') })">
                <div class="absolute top-0 left-0 w-2 h-full bg-blue-500"></div>

                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="font-black text-xl text-slate-800 flex items-center gap-3 tracking-tight">
                            <div class="p-2 bg-blue-50 rounded-xl text-blue-500"><svg class="w-6 h-6" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg></div>
                            Lokasi Maps
                        </h3>
                        <p class="text-sm font-medium text-slate-500 mt-2">Geser pin merah atau paste koordinat dari
                            Google Maps.</p>
                    </div>
                </div>

                <div class="relative mb-6">
                    <input type="text" x-model="coordString" {{ !$canEdit ? 'disabled' : '' }}
                        @input.debounce.500ms="updateMapFromInput()" id="koordinat"
                        class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-slate-50 px-4 py-4 text-slate-900 font-black font-mono focus:border-blue-500 focus:outline-none focus:ring-0 placeholder-transparent"
                        placeholder=" " />
                    <label for="koordinat"
                        class="absolute top-4 left-4 text-slate-500 transition-all duration-200 ease-out pointer-events-none">Koordinat
                        (Lat, Lng)</label>
                    <div class="absolute right-4 top-4 text-blue-400"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg></div>
                </div>

                <div id="map" class="w-full h-80 rounded-3xl z-0 border-2 border-slate-200 shadow-inner">
                </div>

                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('mapComponent', ({
                            lat,
                            lng
                        }) => ({
                            map: null,
                            marker: null,
                            coordString: '',
                            currentLat: lat || 0.51788,
                            currentLng: lng || 101.44737,
                            init() {
                                this.coordString = `${this.currentLat}, ${this.currentLng}`;
                                this.map = L.map('map').setView([this.currentLat, this.currentLng], 15);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap'
                                }).addTo(this.map);
                                this.marker = L.marker([this.currentLat, this.currentLng], {
                                    draggable: @js($canEdit)
                                }).addTo(this.map);
                                this.marker.on('dragend', (event) => {
                                    const pos = this.marker.getLatLng();
                                    this.updateCoords(pos.lat, pos.lng);
                                });
                                this.map.on('click', (e) => {
                                    if (@js($canEdit)) {
                                        this.marker.setLatLng(e.latlng);
                                        this.updateCoords(e.latlng.lat, e.latlng.lng);
                                        this.map.panTo(e.latlng);
                                    }
                                });
                            },
                            updateCoords(lat, lng) {
                                lat = parseFloat(lat).toFixed(6);
                                lng = parseFloat(lng).toFixed(6);
                                this.coordString = `${lat}, ${lng}`;
                                this.currentLat = lat;
                                this.currentLng = lng;
                            },
                            updateMapFromInput() {
                                const parts = this.coordString.split(',');
                                if (parts.length === 2) {
                                    const lat = parseFloat(parts[0]);
                                    const lng = parseFloat(parts[1]);
                                    if (!isNaN(lat) && !isNaN(lng)) {
                                        this.marker.setLatLng([lat, lng]);
                                        this.map.flyTo([lat, lng], 16, {
                                            animate: true,
                                            duration: 1.5
                                        });
                                        this.currentLat = lat;
                                        this.currentLng = lng;
                                    }
                                }
                            }
                        }))
                    })
                </script>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden mt-6">
                <div class="absolute top-0 left-0 w-2 h-full bg-purple-500"></div>
                <h3 class="font-black text-xl text-slate-800 mb-8 flex items-center gap-3 tracking-tight">
                    <div class="p-2 bg-purple-50 rounded-xl text-purple-500"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg></div>
                    Parameter Waktu Sholat
                </h3>

                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <table class="w-full text-sm text-left">
                        <thead
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4">Waktu</th>
                                <th class="px-6 py-4 text-center">Iqomah (Menit)</th>
                                <th class="px-6 py-4 text-center">Koreksi (+/- Menit)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach (['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $waktu)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-black text-slate-700 uppercase tracking-widest">
                                        {{ $waktu }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <input type="number" wire:model="iqomah.{{ $waktu }}"
                                                {{ !$canEdit ? 'disabled' : '' }}
                                                class="w-20 px-4 py-2 rounded-xl border border-slate-300 font-bold text-center focus:ring-purple-500 focus:border-purple-500">
                                            <span
                                                class="text-slate-400 text-[10px] font-bold uppercase tracking-widest">Mnt</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center">
                                            <input type="number" wire:model="koreksi.{{ $waktu }}"
                                                {{ !$canEdit ? 'disabled' : '' }}
                                                class="w-20 px-4 py-2 rounded-xl border border-slate-300 font-bold text-center focus:ring-rose-500 focus:border-rose-500 text-rose-600"
                                                placeholder="0">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden mt-6">
                <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                <h3 class="font-black text-xl text-slate-800 mb-8 flex items-center gap-3 tracking-tight">
                    <div class="p-2 bg-emerald-50 rounded-xl text-emerald-500"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg></div>
                    Konfigurasi API & Integrasi
                </h3>

                <link href="{{ asset('assets/css/tom-select/tom-select.css') }}" rel="stylesheet">
                <script src="{{ asset('assets/js/tom-select/tom-select.complete.min.js') }}"></script>  

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div
                        class="md:col-span-2 bg-slate-50 p-6 rounded-2xl border border-slate-200 {{ !$canEdit ? 'opacity-80' : '' }}">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">
                            Pilih Kota / Kab (MyQuran API)
                        </label>

                        <div wire:ignore x-data="{
                            ts: null,
                            canEdit: @js($canEdit),
                            init() {
                                this.ts = new TomSelect(this.$refs.selectInput, {
                                    valueField: 'id',
                                    labelField: 'lokasi',
                                    searchField: 'lokasi',
                                    placeholder: this.canEdit ? 'Ketik nama kota (misal: Pekanbaru)...' : 'Akses Terkunci',
                                    load: (query, callback) => {
                                        if (!query.length || !this.canEdit) return callback();
                                        $wire.searchCity(query).then(data => {
                                            callback(data);
                                        });
                                    },
                                    onChange: (value) => {
                                        if (!this.canEdit) return;
                                        let item = this.ts.options[value];
                                        if (item) {
                                            $wire.set('kota_id', item.id);
                                            $wire.set('kota_nama', item.lokasi);
                                        }
                                    }
                                });

                                let initId = '{{ $kota_id }}';
                                let initNama = '{{ $kota_nama }}';
                                if (initId) {
                                    this.ts.addOption({ id: initId, lokasi: initNama });
                                    this.ts.setValue(initId, true);
                                }

                                if (!this.canEdit) {
                                    this.ts.disable();
                                }
                            }
                        }">
                            <select x-ref="selectInput" class="w-full"></select>
                        </div>

                        <div
                            class="flex items-center gap-2 mt-4 bg-white px-4 py-2 w-fit rounded-lg border border-slate-200 shadow-sm">
                            <div
                                class="w-2.5 h-2.5 {{ $canEdit ? 'bg-emerald-500 animate-pulse' : 'bg-slate-300' }} rounded-full">
                            </div>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">
                                Kota Terpilih: <span
                                    class="{{ $canEdit ? 'text-emerald-600' : 'text-slate-500' }} font-black">{{ $kota_nama }}
                                    ({{ $kota_id }})</span>
                            </p>
                        </div>
                    </div>

                    <div class="relative group">
                        <input type="text" wire:model="api_cari_lokasi" {{ !$canEdit ? 'disabled' : '' }}
                            id="api_cari_lokasi"
                            class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3 text-slate-900 font-medium focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent"
                            placeholder=" " />
                        <label for="api_cari_lokasi"
                            class="absolute top-3 left-4 text-emerald-500 font-black text-[10px] uppercase tracking-widest bg-white px-1 -translate-y-4 transition-all pointer-events-none">API
                            Cari Lokasi</label>
                    </div>

                    <div class="relative group">
                        <input type="text" wire:model="api_jadwal_sholat" {{ !$canEdit ? 'disabled' : '' }}
                            id="api_jadwal_sholat"
                            class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3 text-slate-900 font-medium focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent"
                            placeholder=" " />
                        <label for="api_jadwal_sholat"
                            class="absolute top-3 left-4 text-emerald-500 font-black text-[10px] uppercase tracking-widest bg-white px-1 -translate-y-4 transition-all pointer-events-none">API
                            Jadwal Sholat</label>
                    </div>

                    <div class="md:col-span-2 relative group">
                        <input type="text" wire:model="api_hijriah" {{ !$canEdit ? 'disabled' : '' }}
                            id="api_hijriah"
                            class="floating-input peer block w-full appearance-none rounded-xl border border-slate-300 bg-transparent px-4 py-3 text-slate-900 font-medium focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent"
                            placeholder=" " />
                        <label for="api_hijriah"
                            class="absolute top-3 left-4 text-emerald-500 font-black text-[10px] uppercase tracking-widest bg-white px-1 -translate-y-4 transition-all pointer-events-none">API
                            Konversi Hijriah</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-8">

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-orange-500"></div>
                <h3 class="font-black text-xl text-slate-800 mb-8 flex items-center gap-3 tracking-tight">
                    <div class="p-2 bg-orange-50 rounded-xl text-orange-500"><svg class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg></div>
                    Media Visual
                </h3>

                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('imageUploader', (modelName) => ({
                            isUploading: false,
                            progress: 0,
                            async handleUpload(event) {
                                const file = event.target.files[0];
                                if (!file) return;
                                this.isUploading = true;
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
                                        const maxSize = (modelName === 'background_image') ? 1920 : 500;
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
                                        fetch(dataUrl).then(res => res.blob()).then(blob => {
                                            const fileNew = new File([blob], file.name, {
                                                type: 'image/jpeg'
                                            });
                                            this.progress = 80;
                                            @this.upload(modelName, fileNew, () => {
                                                this.progress = 100;
                                                setTimeout(() => {
                                                    this.isUploading = false;
                                                }, 1000);
                                            }, () => {
                                                this.isUploading = false;
                                                alert('Upload Gagal');
                                            }, (e) => {
                                                this.progress = e.detail.progress;
                                            });
                                        });
                                    }
                                }
                            }
                        }));
                    });
                </script>

                <div class="mb-8" x-data="imageUploader('logo')">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Logo
                        (Otomatis Tersimpan)</label>
                    <div
                        class="flex items-center gap-6 bg-slate-50 p-4 rounded-2xl border border-slate-200 {{ !$canEdit ? 'opacity-75' : '' }}">
                        <div
                            class="relative w-24 h-24 rounded-2xl bg-white border border-slate-200 overflow-hidden shrink-0 shadow-sm">
                            @if ($old_logo)
                                <img src="{{ asset('storage/' . $old_logo) }}"
                                    class="w-full h-full object-contain p-2">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300"><svg
                                        class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg></div>
                            @endif
                            <div x-show="isUploading"
                                class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center backdrop-blur-sm z-10">
                                <span class="text-white font-black text-xs mb-1" x-text="progress + '%'"></span>
                                <div class="w-16 bg-slate-600 rounded-full h-1.5">
                                    <div class="bg-emerald-400 h-full rounded-full transition-all"
                                        :style="'width: ' + progress + '%'"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input type="file" id="logo_upload" class="hidden" accept="image/*"
                                @change="handleUpload" {{ !$canEdit ? 'disabled' : '' }}>
                            <label for="logo_upload"
                                class="inline-flex px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg transition
                        {{ $canEdit ? 'bg-emerald-500 text-white cursor-pointer hover:bg-emerald-600' : 'bg-slate-300 text-slate-500 cursor-not-allowed pointer-events-none' }}">
                                {{ $canEdit ? 'Ganti Logo' : 'Akses Terkunci' }}
                            </label>
                            <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase">Akan di-compress otomatis
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mb-8" x-data="imageUploader('background_image')">
                    <label
                        class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3 block">Background
                        TV (Otomatis Tersimpan)</label>
                    <div
                        class="relative w-full h-48 rounded-[2rem] bg-slate-100 border-2 border-dashed border-slate-300 overflow-hidden shadow-sm transition
                {{ $canEdit ? 'hover:border-emerald-400 group cursor-pointer' : 'opacity-60 cursor-not-allowed' }}">

                        <input type="file"
                            class="absolute inset-0 w-full h-full opacity-0 z-20 {{ $canEdit ? 'cursor-pointer' : 'cursor-not-allowed' }}"
                            accept="image/*" @change="handleUpload" {{ !$canEdit ? 'disabled' : '' }}
                            x-bind:disabled="isUploading">

                        @if ($old_background)
                            <img src="{{ asset('storage/' . $old_background) }}"
                                class="w-full h-full object-cover opacity-80">
                        @else
                            <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <span
                                    class="text-sm font-bold tracking-tight">{{ $canEdit ? 'Klik untuk Ganti Background' : 'Read Only' }}</span>
                            </div>
                        @endif

                        <div x-show="isUploading"
                            class="absolute inset-0 z-30 bg-black/70 flex flex-col items-center justify-center backdrop-blur-md">
                            <span class="text-emerald-400 font-black text-2xl mb-2" x-text="progress + '%'"></span>
                            <div class="w-1/2 bg-slate-700 rounded-full h-2 overflow-hidden shadow-inner">
                                <div class="bg-emerald-500 h-full transition-all duration-200"
                                    :style="'width: ' + progress + '%'"></div>
                            </div>
                            <p
                                class="text-[10px] text-slate-300 uppercase tracking-widest mt-3 font-bold animate-pulse">
                                Memproses & Menyimpan...</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Video Profil /
                            Kajian</label>
                        <span
                            class="text-[9px] font-bold text-white bg-slate-400 px-2 py-1 rounded-md uppercase tracking-wider">MP4,
                            Max 200MB</span>
                    </div>

                    @if ($old_video)
                        <div
                            class="mb-4 bg-slate-900 rounded-2xl overflow-hidden shadow-lg border border-slate-800 relative group">
                            <div
                                class="absolute top-2 left-2 bg-rose-500 text-white text-[9px] font-black px-2 py-1 rounded-md uppercase tracking-widest z-10 flex items-center gap-1 shadow-md">
                                <div class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div> Video Aktif
                            </div>
                            <video wire:key="video-preview-{{ $old_video }}"
                                class="w-full h-40 object-cover opacity-80 group-hover:opacity-100 transition-opacity"
                                controls controlsList="nodownload">
                                <source src="{{ asset('storage/' . $old_video) }}" type="video/mp4">
                            </video>
                        </div>
                    @endif

                    <div class="relative w-full rounded-2xl border-2 border-dashed border-slate-300 transition overflow-hidden {{ $canEdit ? 'bg-slate-50 hover:border-emerald-400 group' : 'bg-slate-100 opacity-60' }}"
                        x-data="{ uploading: false, progress: 0, isSaving: @entangle('isVideoSaving') }" x-on:livewire-upload-start="uploading = true; progress = 0;"
                        x-on:livewire-upload-finish="uploading = false;"
                        x-on:livewire-upload-error="uploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress;">

                        <input type="file" wire:model="video"
                            class="absolute inset-0 w-full h-full opacity-0 z-20 {{ $canEdit ? 'cursor-pointer' : 'cursor-not-allowed' }}"
                            accept="video/mp4" {{ !$canEdit ? 'disabled' : '' }}
                            x-bind:disabled="uploading || isSaving">

                        <div class="flex flex-col items-center justify-center py-8 text-center px-4">
                            <div
                                class="w-12 h-12 bg-white rounded-full flex items-center justify-center mb-3 shadow-sm border border-slate-100 {{ $canEdit ? 'group-hover:scale-110 transition-transform' : '' }}">
                                <svg class="w-5 h-5 {{ $canEdit ? 'text-slate-400 group-hover:text-emerald-500' : 'text-slate-300' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                            </div>
                            <span
                                class="text-sm font-bold text-slate-700">{{ $canEdit ? 'Upload Video Baru (Auto Save)' : 'Akses Upload Terkunci' }}</span>
                        </div>

                        <div x-show="uploading || isSaving" x-transition
                            class="absolute inset-0 z-30 bg-white/95 backdrop-blur-md flex flex-col items-center justify-center px-8">
                            <span class="text-sm font-black text-emerald-600 mb-2 uppercase tracking-widest"
                                x-text="progress === 100 ? 'Menyimpan...' : 'Upload: ' + progress + '%'"></span>
                            <div class="w-full bg-slate-200 rounded-full h-3 mb-2 overflow-hidden shadow-inner">
                                <div class="bg-emerald-500 h-full transition-all duration-200 flex items-center justify-end"
                                    :class="progress === 100 ? 'animate-pulse' : ''"
                                    :style="'width: ' + progress + '%'"></div>
                            </div>
                            <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest animate-pulse"
                                x-show="progress === 100">Jangan tutup halaman...</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-blue-500"></div>
                <h3 class="font-black text-xl text-slate-800 mb-8 flex items-center gap-3 tracking-tight">
                    <div class="p-2 bg-blue-50 rounded-xl text-blue-500"><svg class="w-6 h-6" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z" />
                        </svg></div>
                    Audio Panggilan Sholat
                </h3>

                <div
                    class="mb-6 bg-slate-50 p-6 rounded-3xl border border-slate-200 {{ !$canEdit ? 'opacity-75' : '' }}">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4">Adzan 4
                        Waktu</label>
                    @if ($old_adzan)
                        <div class="mb-4 p-3 bg-white rounded-xl border border-slate-200 flex flex-col gap-2">
                            <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest px-1">File Aktif
                            </p>
                            <audio wire:key="audio-adzan-{{ $old_adzan }}" controls class="h-8 w-full">
                                <source src="{{ Storage::url($old_adzan) }}" type="audio/mpeg">
                            </audio>
                        </div>
                    @endif

                    <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                        x-on:livewire-upload-finish="uploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <label
                            class="relative flex items-center justify-center w-full h-16 border-2 border-dashed rounded-xl transition-all overflow-hidden
                    {{ $canEdit ? 'border-emerald-200 bg-emerald-50/50 hover:bg-emerald-100 cursor-pointer group' : 'border-slate-200 bg-slate-100 cursor-not-allowed' }}">
                            <input type="file" wire:model="adzan" class="hidden" accept="audio/mpeg"
                                {{ !$canEdit ? 'disabled' : '' }} x-bind:disabled="uploading" />

                            <div class="flex items-center gap-3" x-show="!uploading">
                                <svg class="w-5 h-5 {{ $canEdit ? 'text-emerald-500 group-hover:scale-110' : 'text-slate-300' }} transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <span
                                    class="text-xs font-black uppercase tracking-widest {{ $canEdit ? 'text-emerald-700' : 'text-slate-400' }}">
                                    {{ $canEdit ? 'Ganti MP3 Adzan' : 'Akses Terkunci' }}
                                </span>
                            </div>

                            <div x-show="uploading"
                                class="absolute inset-0 bg-emerald-500 flex items-center justify-center">
                                <span class="text-white font-black text-xs uppercase tracking-widest"
                                    x-text="'Upload ' + progress + '%'"></span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 {{ !$canEdit ? 'opacity-75' : '' }}">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4">Khusus
                        Adzan Subuh</label>
                    @if ($old_adzan_subuh)
                        <div class="mb-4 p-3 bg-white rounded-xl border border-slate-200 flex flex-col gap-2">
                            <p class="text-[9px] font-bold text-blue-500 uppercase tracking-widest px-1">File Subuh
                                Aktif</p>
                            <audio wire:key="audio-subuh-{{ $old_adzan_subuh }}" controls class="h-8 w-full">
                                <source src="{{ Storage::url($old_adzan_subuh) }}" type="audio/mpeg">
                            </audio>
                        </div>
                    @endif

                    <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                        x-on:livewire-upload-finish="uploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <label
                            class="relative flex items-center justify-center w-full h-16 border-2 border-dashed rounded-xl transition-all overflow-hidden
                    {{ $canEdit ? 'border-blue-200 bg-blue-50/50 hover:bg-blue-100 cursor-pointer group' : 'border-slate-200 bg-slate-100 cursor-not-allowed' }}">
                            <input type="file" wire:model="adzan_subuh" class="hidden" accept="audio/mpeg"
                                {{ !$canEdit ? 'disabled' : '' }} x-bind:disabled="uploading" />

                            <div class="flex items-center gap-3" x-show="!uploading">
                                <svg class="w-5 h-5 {{ $canEdit ? 'text-blue-500 group-hover:scale-110' : 'text-slate-300' }} transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <span
                                    class="text-xs font-black uppercase tracking-widest {{ $canEdit ? 'text-blue-700' : 'text-slate-400' }}">
                                    {{ $canEdit ? 'Ganti MP3 Subuh' : 'Akses Terkunci' }}
                                </span>
                            </div>

                            <div x-show="uploading"
                                class="absolute inset-0 bg-blue-500 flex items-center justify-center">
                                <span class="text-white font-black text-xs uppercase tracking-widest"
                                    x-text="'Upload ' + progress + '%'"></span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-slate-800"></div>
                <h3 class="font-black text-xl text-slate-800 mb-8 flex items-center gap-3 tracking-tight">Tampilan
                    Layar</h3>

                <div class="space-y-8">
                    <div x-data="{ val: @entangle('running_text_speed').live }">
                        <div class="flex justify-between mb-3 items-center">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Speed
                                Running Text</label>
                            <span
                                class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-black border border-slate-200"
                                x-text="val"></span>
                        </div>
                        <input type="range" {{ !$canEdit ? 'disabled' : '' }} x-model="val" min="1"
                            max="20" step="1"
                            class="w-full h-2.5 bg-slate-200 rounded-full appearance-none cursor-pointer accent-slate-800">
                    </div>

                    <div x-data="{ val: @entangle('durasi_slide_foto').live }">
                        <div class="flex justify-between mb-3 items-center">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Durasi Slide
                                Gambar</label>
                            <div
                                class="flex items-baseline gap-1 bg-slate-100 px-3 py-1 rounded-lg border border-slate-200">
                                <span class="text-slate-700 text-xs font-black" x-text="val / 1000"></span>
                                <span class="text-[9px] text-slate-500 font-bold uppercase">Detik</span>
                            </div>
                        </div>
                        <input type="range" {{ !$canEdit ? 'disabled' : '' }} x-model="val" min="1000"
                            max="10000" step="1000"
                            class="w-full h-2.5 bg-slate-200 rounded-full appearance-none cursor-pointer accent-slate-800">
                    </div>

                    <div class="pt-6 border-t border-slate-100">
                        <div class="flex justify-between items-center mb-6">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Warna Tema
                                Display</label>
                            <a href="{{ route('settings.themes') }}" wire:navigate
                                class="px-4 py-2 bg-slate-100 text-slate-500 hover:bg-emerald-500 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">Kelola
                                Warna</a>
                        </div>

                        <div class="flex flex-wrap gap-4">
                            @foreach ($availableThemes as $theme)
                                <label class="relative cursor-pointer group" wire:key="theme-{{ $theme->id }}">
                                    <input type="radio" wire:model="theme_color" {{ !$canEdit ? 'disabled' : '' }}
                                        value="{{ $theme->name }}" class="sr-only peer">
                                    <div class="w-14 h-14 rounded-2xl border-4 border-white shadow-md transition-all peer-checked:ring-4 peer-checked:scale-110 flex items-center justify-center overflow-hidden"
                                        style="background-color: {{ $theme->main_color }}; --tw-ring-color: {{ $theme->main_color }}80">
                                        <svg class="w-6 h-6 text-white opacity-0 peer-checked:opacity-100 transition-opacity drop-shadow-sm"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span
                                        class="text-[9px] font-black uppercase text-center block mt-2 text-slate-400 group-hover:text-slate-800 tracking-widest transition-colors">{{ $theme->label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
