    <div class="space-y-8 md:space-y-8 pb-20 work-sans-all">
        <style>
            /* @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap'); */
            .work-sans-all, .work-sans-all * { font-family: 'Work Sans', sans-serif !important; }

            .marquee-preview {
                overflow: hidden;
                display: flex;
                align-items: center;
                position: relative;
                width: 100%;
            }

            .marquee-content {
                display: flex;
                gap: 4rem;
                width: max-content;
                animation: marquee linear infinite; /* Duration dihapus disini, dipindah ke inline style */
                padding-left: 100%;
            }

            .marquee-item {
                white-space: nowrap;
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            @keyframes marquee {
                0% { transform: translateX(0); }
                100% { transform: translateX(-100%); }
            }
        </style>

        <div class="bg-slate-900 rounded-[3rem] p-2 shadow-2xl border-4 border-slate-800">
            <div class="bg-black rounded-[2.5rem] py-4 px-10 relative overflow-hidden border border-slate-700 flex items-center min-h-20">
                <div class="bg-emerald-500 text-black text-[10px] font-black px-4 py-1 rounded-full absolute left-4 z-20 shadow-lg uppercase tracking-tighter">
                    Preview Display
                </div>

                @php
                    // Speed 1 (Lambat) = 60 detik
                    // Speed 10 (Cepat) = 10 detik
                    // Rumus: 65 - (Speed * 5)
                    $duration = max(10, 65 - ($globalSpeed * 5));
                @endphp

                <div class="marquee-preview">
                    <div class="marquee-content font-bold text-lg uppercase tracking-wider" style="animation-duration: {{ $duration }}s;">
                        @forelse($data->where('is_active', true) as $item)
                            @php
                                $theme = match($item->tipe) {
                                    'ayat'   => ['text' => 'text-cyan-400', 'dot' => 'bg-cyan-500 shadow-[0_0_10px_#22d3ee]'],
                                    'hadits' => ['text' => 'text-purple-400', 'dot' => 'bg-purple-500 shadow-[0_0_10px_#c084fc]'],
                                    'ucapan' => ['text' => 'text-amber-400', 'dot' => 'bg-amber-500 shadow-[0_0_10px_#fbbf24]'],
                                    default  => ['text' => 'text-emerald-400', 'dot' => 'bg-emerald-500 shadow-[0_0_10px_#10b981]'],
                                };
                            @endphp

                            <div class="marquee-item {{ $theme['text'] }}">
                                <span class="w-2 h-2 rounded-full {{ $theme['dot'] }}"></span>
                                <span>{{ $item->teks }}</span>
                            </div>
                        @empty
                            <span class="text-slate-700 text-sm normal-case mx-auto">Belum ada teks aktif...</span>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="text-center mt-2">
                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">
                    Global Speed: {{ $globalSpeed }} (via Pengaturan Aplikasi)
                </span>
            </div>
        </div>

        <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-xl overflow-hidden min-h-125 relative">
            <div class="px-12 py-10 flex flex-col md:flex-row justify-between items-center bg-slate-50/50 gap-6 border-b border-slate-50">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none">Running Text</h1>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-2 flex items-center gap-2">
                        Informasi Berjalan pada TV Display
                    </p>
                </div>
                @if($canEdit)
                <button wire:click="create" class="bg-emerald-500 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Pesan
                </button>
                @else
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 px-5 py-3 rounded-2xl shadow-sm animate-fade-in w-full md:w-auto">
                    <div class="p-2 bg-amber-500 rounded-lg text-white shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m0-8V7m0 0v2m-9 1l1 1h16l1-1v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4z"></path><circle cx="12" cy="11" r="9" stroke="currentColor" stroke-width="2"></circle></svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-amber-600 uppercase tracking-widest leading-none mb-1">Mode Lihat Saja</span>
                        <p class="text-[10px] font-bold text-amber-700 leading-tight">
                            Role kamu <span class="underline uppercase decoration-amber-300 decoration-2">{{ auth()->user()->role }}</span> (Read-only).
                        </p>
                    </div>
                </div>
            @endif
            </div>

            @if (session()->has('message'))
                <div class="bg-emerald-500 text-white px-10 py-4 font-black text-xs uppercase tracking-widest text-center animate-pulse">
                    {{ session('message') }}
                </div>
            @endif

            <div class="p-10 space-y-4">
                @forelse($data as $item)
                    <div class="group flex flex-col md:flex-row items-start md:items-center justify-between p-6 bg-slate-50 rounded-4xl border border-slate-100 hover:border-emerald-200 hover:bg-white hover:shadow-xl transition-all duration-300 gap-4">
                        <div class="flex items-center gap-6 w-full md:w-auto">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-xs shadow-inner shrink-0
                                @if($item->tipe == 'ayat') bg-blue-100 text-blue-600 @elseif($item->tipe == 'hadits') bg-purple-100 text-purple-600 @elseif($item->tipe == 'ucapan') bg-amber-100 text-amber-600 @else bg-emerald-100 text-emerald-600 @endif">
                                {{ substr(strtoupper($item->tipe), 0, 1) }}
                            </div>

                            <div class="flex-1">
                                <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2 mb-1">
                                    <span class="bg-slate-200 px-2 py-0.5 rounded text-slate-500">{{ $item->tipe }}</span>
                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                    Urutan: #{{ $item->urutan }}
                                    <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                    <span class="flex items-center gap-1 text-slate-500" title="Individual Setting">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Set: {{ $item->kecepatan }}
                                    </span>
                                </div>
                                <h3 class="text-slate-800 font-bold text-lg leading-snug line-clamp-2">{{ $item->teks }}</h3>
                            </div>
                        </div>
                        @if($canEdit)
                        <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                            <button wire:click="toggleStatus({{ $item->id }})" class="px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all {{ $item->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-500' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                            <div class="flex gap-2">
                                <button wire:click="edit({{ $item->id }})" class="p-3 bg-white rounded-xl text-slate-400 hover:text-emerald-500 shadow-sm border border-slate-100 transition-all hover:scale-110">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click="deleteId({{ $item->id }})" class="p-3 bg-white rounded-xl text-slate-400 hover:text-rose-500 shadow-sm border border-slate-100 transition-all hover:scale-110">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="py-20 text-center font-black text-slate-300 uppercase tracking-widest text-xs">Belum ada data teks</div>
                @endforelse
            </div>
            <div class="px-10 pb-10">{{ $data->links() }}</div>
        </div>

        @if($isModalOpen && $canEdit)
        <div class="fixed inset-0 z-9999 flex items-center justify-center bg-slate-900/90 backdrop-blur-sm p-4 animate-fade-in">
            <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-lg shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-linear-to-r from-emerald-500 to-emerald-300"></div>

                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight">{{ $isEditMode ? 'Ubah' : 'Tambah' }} Pesan</h3>

                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                        <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-emerald-500 transition-colors">
                            {{ $is_active ? 'Tampil' : 'Sembunyi' }}
                        </span>
                    </label>
                </div>

                <div class="space-y-6">
                    <div class="relative" x-data="{ teks: @entangle('teks') }">
                        <textarea wire:model="teks" id="teks" rows="4"
                            class="peer block w-full rounded-2xl border border-slate-200 bg-slate-50 px-5 py-4 text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-0 font-bold transition-all text-lg" placeholder=" "></textarea>
                        <label for="teks" class="absolute left-5 top-4 text-slate-400 transition-all duration-200 peer-focus:-translate-y-8 peer-focus:text-[10px] peer-focus:font-black peer-focus:text-emerald-500 peer-placeholder-shown:translate-y-0" :class="teks ? '-translate-y-8 text-[10px] font-black text-emerald-500' : ''">Isi Teks Pesan</label>
                        @error('teks') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Tipe Konten</label>
                            <select wire:model="tipe" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:ring-emerald-500 cursor-pointer">
                                <option value="info">Informasi</option>
                                <option value="ayat">Ayat Al-Qur'an</option>
                                <option value="hadits">Al-Hadits</option>
                                <option value="ucapan">Ucapan/Salam</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 block">Nomor Urut</label>
                            <input type="number" wire:model="urutan" class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 font-bold text-slate-700 focus:ring-emerald-500 text-center">
                        </div>
                    </div>

                    <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Kecepatan Per Item
                            </label>
                            <span class="text-xs font-black text-emerald-600 bg-emerald-100 px-2 py-1 rounded">{{ $kecepatan }}</span>
                        </div>

                        <input type="range" wire:model.live="kecepatan" min="1" max="10" step="1" class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-emerald-500">
                        <div class="flex justify-between text-[9px] font-bold text-slate-400 mt-1 uppercase tracking-widest">
                            <span>Lambat</span>
                            <span>Normal</span>
                            <span>Cepat</span>
                        </div>
                        <p class="text-[9px] text-slate-400 italic mt-2 text-center">*Pengaturan ini disimpan untuk Display TV, Preview di atas menggunakan Global Speed.</p>
                    </div>
                </div>

                <div class="flex gap-4 mt-8 border-t border-slate-100 pt-6">
                    <button wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                    <button wire:click="{{ $isEditMode ? 'update' : 'store' }}" class="flex-1 py-4 bg-emerald-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-200 transition-all">Simpan</button>
                </div>
            </div>
        </div>
        @endif

        @if($isDeleteModalOpen && $canEdit)
        <div class="fixed inset-0 z-9999 flex items-center justify-center bg-rose-900/90 backdrop-blur-sm p-4 animate-fade-in">
            <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-md text-center shadow-2xl relative overflow-hidden">
                <div class="w-24 h-24 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500 shadow-inner">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Hapus Pesan?</h3>
                <p class="text-slate-500 font-medium text-sm mb-8">Pesan running text ini akan dihapus permanen dari display.</p>
                <div class="flex gap-4">
                    <button wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                    <button wire:click="delete" class="flex-1 py-4 bg-rose-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all">Ya, Hapus</button>
                </div>
            </div>
        </div>
        @endif
    </div>
