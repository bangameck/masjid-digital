<div class="space-y-8 md:space-y-8 pb-10 work-sans-all"
    x-data="{ showToast: false, toastMessage: '' }"
    @alert-error.window="showToast = true; toastMessage = $event.detail.message; setTimeout(() => showToast = false, 3000)">

    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap'); */
        .work-sans-all, .work-sans-all * { font-family: 'Work Sans', sans-serif !important; }
        .floating-input:focus ~ label, .floating-input:not(:placeholder-shown) ~ label {
            transform: translateY(-1.25rem) scale(0.8); background-color: white; padding-left: 4px; padding-right: 4px; color: #10b981; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;
        }
        /* Class khusus untuk Date & Select agar label selalu floating */
        .floating-forced ~ label {
            transform: translateY(-1.25rem) scale(0.8); background-color: white; padding-left: 4px; padding-right: 4px; color: #10b981; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em;
        }
    </style>

    <div x-show="showToast" x-transition class="fixed top-5 right-5 z-10000 bg-rose-500 text-white px-6 py-4 rounded-xl shadow-2xl font-bold text-sm flex items-center gap-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <span x-text="toastMessage"></span>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl overflow-hidden relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>

        <div class="p-10 pb-0 flex flex-col md:flex-row justify-between items-center gap-6 relative z-10">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none mb-2">Jadwal Dakwah</h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Manajemen Petugas & Penceramah</p>
            </div>
            @if($canEdit)
            <button wire:click="create" class="bg-emerald-500 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Jadwal
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

        <div class="mt-8 px-10 flex gap-2 overflow-x-auto no-scrollbar pb-4">
            @php
                $tabs = [
                    'jumat' => ['label' => 'Jumat', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    'ramadhan' => ['label' => 'Ramadhan', 'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z'],
                    'ied' => ['label' => 'Sholat Ied', 'icon' => 'M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z'],
                    'rutin' => ['label' => 'Pengajian', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ];
            @endphp

            @foreach($tabs as $key => $tab)
                <button wire:click="switchTab('{{ $key }}')"
                    class="flex items-center gap-2 px-6 py-4 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all whitespace-nowrap
                    {{ $activeTab === $key ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200' : 'bg-slate-50 text-slate-400 hover:bg-slate-100' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"></path></svg>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-500 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest text-center shadow-lg animate-fade-in">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
        @forelse($data as $item)
            <div class="group bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-slate-100 group-hover:bg-emerald-500 transition-colors"></div>
                <div class="flex justify-between items-start mb-6 pl-4">
                    <div>
                        <div class="flex items-center gap-2 text-emerald-500 font-black text-xs uppercase tracking-widest mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}
                        </div>
                        @if($activeTab == 'ramadhan')
                            <span class="bg-amber-100 text-amber-600 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider">Malam ke-{{ $item->malam_ke }}</span>
                        @elseif($activeTab == 'ied')
                            <span class="bg-purple-100 text-purple-600 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider">{{ $item->ied }}</span>
                        @endif
                    </div>
                    @if($canEdit)
                    <div class="flex gap-2">
                        <button wire:click="edit({{ $item->id }})" class="p-2 bg-slate-50 text-slate-400 rounded-lg hover:bg-emerald-50 hover:text-emerald-500 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                        <button wire:click="deleteId({{ $item->id }})" class="p-2 bg-slate-50 text-slate-400 rounded-lg hover:bg-rose-50 hover:text-rose-500 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                    </div>
                    @endif
                </div>
                <div class="pl-4 grid grid-cols-2 gap-y-4 gap-x-2">
                    <div class="col-span-2">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ ($activeTab == 'jumat' || $activeTab == 'ied') ? 'Khatib' : 'Penceramah' }}</p>
                        <h3 class="text-lg font-black text-slate-800 leading-tight">{{ ($activeTab == 'jumat' || $activeTab == 'ied') ? $item->khatib : $item->penceramah }}</h3>
                    </div>
                    @if($item->judul_ceramah)
                    <div class="col-span-2 bg-slate-50 p-3 rounded-xl border border-slate-100">
                         <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tema / Judul</p>
                         <p class="text-xs font-bold text-slate-700 italic">"{{ $item->judul_ceramah }}"</p>
                    </div>
                    @endif
                    @if($activeTab != 'rutin')
                        <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Imam</p><p class="text-sm font-bold text-slate-700">{{ $item->imam }}</p></div>
                        <div><p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-1">Muadzin/Bilal</p><p class="text-sm font-bold text-slate-700">{{ $item->muadzin ?? '-' }} @if($item->bilal) <span class="text-slate-300">/</span> {{ $item->bilal }} @endif</p></div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center font-black text-slate-300 uppercase tracking-widest text-xs">Belum ada jadwal untuk kategori ini</div>
        @endforelse
    </div>
    <div class="mt-8 px-4">{{ $data->links() }}</div>

    @if($isModalOpen && $canEdit)
    <div class="fixed inset-0 z-9999 flex items-center justify-center bg-slate-900/90 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-2xl shadow-2xl relative overflow-hidden max-h-[90vh] overflow-y-auto custom-scrollbar">
             <div class="absolute top-0 left-0 w-full h-2 bg-linear-to-r from-emerald-500 to-emerald-300"></div>

            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-8">
                {{ $isEditMode ? 'Edit' : 'Tambah' }} Jadwal <span class="text-emerald-500">{{ ucfirst($activeTab) }}</span>
            </h3>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    @if($activeTab == 'ramadhan')
                        <div class="relative">
                            <select wire:model.live="selectedRamadhanYear" class="floating-forced peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 font-bold">
                                @foreach($ramadhanYears as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                            <label class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Pilih Tahun</label>
                        </div>
                        <div class="relative">
                            <select wire:model="tanggal" class="floating-forced peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 font-bold">
                                <option value="">Pilih Tanggal</option>
                                @foreach($ramadhanDates as $rd)
                                    <option value="{{ $rd['value'] }}">{{ $rd['label'] }}</option>
                                @endforeach
                            </select>
                            <label class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Tanggal Ramadhan</label>
                        </div>

                    @elseif($activeTab == 'jumat')
                        <div class="relative" x-data>
                            <input type="date" wire:model.live="tanggal" id="tanggal"
                                @change="const day = new Date($el.value).getDay(); if(day !== 5 && $el.value !== '') { alert('Mohon pilih hari Jumat!'); $el.value = ''; @this.set('tanggal', ''); }"
                                class="floating-forced peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                            <label for="tanggal" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Tanggal (Jumat)</label>
                            @error('tanggal') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                        </div>

                    @else
                        <div class="relative">
                            <input type="date" wire:model="tanggal" id="tanggal" class="floating-forced peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                            <label for="tanggal" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Tanggal</label>
                            @error('tanggal') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    @if($activeTab == 'ramadhan')
                        <div class="relative">
                            <input type="number" wire:model="malam_ke" id="malam_ke" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                            <label for="malam_ke" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Malam Ke-</label>
                        </div>
                    @elseif($activeTab == 'ied')
                        <div class="relative">
                            <select wire:model="ied_type" id="ied_type" class="floating-forced peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 font-bold">
                                <option value="Idul Fitri">Idul Fitri</option>
                                <option value="Idul Adha">Idul Adha</option>
                            </select>
                            <label for="ied_type" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Jenis Ied</label>
                        </div>
                    @endif
                </div>

                <div class="relative">
                    @if($activeTab == 'jumat' || $activeTab == 'ied')
                        <input type="text" wire:model="khatib" id="khatib" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                        <label for="khatib" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Nama Khatib</label>
                        @error('khatib') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                    @else
                        <input type="text" wire:model="penceramah" id="penceramah" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                        <label for="penceramah" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Nama Penceramah</label>
                        @error('penceramah') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                    @endif
                </div>

                <div class="relative">
                    <textarea wire:model="judul_ceramah" id="judul_ceramah" rows="2" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" "></textarea>
                    <label for="judul_ceramah" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Judul / Tema Ceramah</label>
                </div>

                @if($activeTab != 'rutin')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="relative">
                            <input type="text" wire:model="imam" id="imam" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                            <label for="imam" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Nama Imam</label>
                            @error('imam') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                        </div>
                        <div class="relative">
                            <input type="text" wire:model="muadzin" id="muadzin" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                            <label for="muadzin" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Muadzin</label>
                        </div>
                        <div class="relative">
                            <input type="text" wire:model="bilal" id="bilal" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                            <label for="bilal" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Bilal</label>
                        </div>
                    </div>
                @endif
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
            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Hapus Jadwal?</h3>
            <p class="text-slate-500 font-medium text-sm mb-8">Data jadwal ini akan dihapus permanen.</p>
            <div class="flex gap-4">
                <button wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                <button wire:click="delete" class="flex-1 py-4 bg-rose-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all">Ya, Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>
