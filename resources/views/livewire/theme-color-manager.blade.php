<div class="space-y-8 md:space-y-8 pb-10 work-sans-all">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap');
        .work-sans-all, .work-sans-all * { font-family: 'Work Sans', sans-serif !important; }
        .floating-input:focus ~ label, .floating-input:not(:placeholder-shown) ~ label {
            transform: translateY(-1.25rem) scale(0.8); background-color: white; padding-left: 4px; padding-right: 4px; color: #10b981; font-weight: 800; text-transform: uppercase;
        }
    </style>

    {{-- HEADER --}}
    <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-xl p-8 md:p-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none mb-2">Warna Tema</h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Sesuaikan Palet Layar Display TV</p>
            </div>

            @if(!$canEdit)
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
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-500 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest text-center shadow-lg animate-fade-in">
            {{ session('message') }}
        </div>
    @endif

    {{-- KONTEN UTAMA --}}
    <div class="grid grid-cols-1 {{ $canEdit ? 'lg:grid-cols-3' : 'lg:grid-cols-1' }} gap-8">

        {{-- FORM INPUT (Hanya tampil jika canEdit true) --}}
        @if($canEdit)
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-xl h-fit relative overflow-hidden lg:col-span-1">
            <div class="absolute top-0 left-0 w-full h-2 bg-linear-to-r from-emerald-500 to-emerald-300"></div>
            <h3 class="font-black text-xl text-slate-800 uppercase tracking-tight mb-8">{{ $is_edit ? 'Edit' : 'Tambah' }} Warna</h3>

            <form wire:submit.prevent="{{ $is_edit ? 'update' : 'store' }}" class="space-y-6">
                <div class="relative">
                    <input type="text" wire:model="name" id="name" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                    <label for="name" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">ID Nama (Tanpa Spasi)</label>
                    @error('name') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                </div>

                <div class="relative">
                    <input type="text" wire:model="label" id="label" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                    <label for="label" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Label Tampilan</label>
                    @error('label') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                </div>

                <div class="bg-slate-50 p-5 rounded-3xl border border-slate-100 space-y-4">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Setup Palet Hex</p>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="flex flex-col items-center">
                            <input type="color" wire:model="main_color" class="w-12 h-12 rounded-full cursor-pointer border-4 border-white shadow-md bg-transparent [&::-webkit-color-swatch-wrapper]:p-0 [&::-webkit-color-swatch]:rounded-full [&::-webkit-color-swatch]:border-none" title="Utama">
                            <span class="text-[9px] font-bold text-slate-500 uppercase mt-2">Utama</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <input type="color" wire:model="dark_color" class="w-12 h-12 rounded-full cursor-pointer border-4 border-white shadow-md bg-transparent [&::-webkit-color-swatch-wrapper]:p-0 [&::-webkit-color-swatch]:rounded-full [&::-webkit-color-swatch]:border-none" title="Gelap">
                            <span class="text-[9px] font-bold text-slate-500 uppercase mt-2">Gelap</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <input type="color" wire:model="light_color" class="w-12 h-12 rounded-full cursor-pointer border-4 border-white shadow-md bg-transparent [&::-webkit-color-swatch-wrapper]:p-0 [&::-webkit-color-swatch]:rounded-full [&::-webkit-color-swatch]:border-none" title="Terang">
                            <span class="text-[9px] font-bold text-slate-500 uppercase mt-2">Terang</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full bg-emerald-500 text-white py-4 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-200 transition-all">
                        {{ $is_edit ? 'Simpan Perubahan' : 'Simpan Tema' }}
                    </button>
                    @if($is_edit)
                        <button type="button" wire:click="resetInput" class="w-full bg-slate-100 text-slate-500 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">Batal</button>
                    @endif
                </div>
            </form>
        </div>
        @endif

        {{-- DAFTAR TEMA --}}
        <div class="{{ $canEdit ? 'lg:col-span-2' : 'w-full' }}">
            <div class="grid grid-cols-1 md:grid-cols-2 {{ !$canEdit ? 'lg:grid-cols-3' : '' }} gap-6">
                @forelse($colors as $color)
                <div class="group bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-lg hover:shadow-2xl transition-all duration-300 flex items-center justify-between">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-3xl shadow-md flex flex-col overflow-hidden border-2 border-slate-50 shrink-0 transform group-hover:scale-110 transition-transform">
                            <div class="flex-1" style="background-color: {{ $color->main_color }}" title="Utama: {{ $color->main_color }}"></div>
                            <div class="h-1/3 flex">
                                <div class="flex-1" style="background-color: {{ $color->dark_color }}" title="Gelap: {{ $color->dark_color }}"></div>
                                <div class="flex-1" style="background-color: {{ $color->light_color }}" title="Terang: {{ $color->light_color }}"></div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-800 text-lg leading-none mb-1">{{ $color->label }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $color->name }}</p>
                        </div>
                    </div>

                    @if($canEdit)
                        <div class="flex flex-col gap-2 shrink-0">
                            <button wire:click="edit({{ $color->id }})" class="p-3 bg-slate-50 text-slate-400 rounded-xl hover:bg-emerald-50 hover:text-emerald-500 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button wire:click="deleteId({{ $color->id }})" class="p-3 bg-slate-50 text-slate-400 rounded-xl hover:bg-rose-50 hover:text-rose-500 transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    @endif
                </div>
                @empty
                    <div class="col-span-full py-20 text-center font-black text-slate-300 uppercase tracking-widest text-xs">Belum ada tema warna</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL HAPUS --}}
    @if($isDeleteModalOpen && $canEdit)
    <div class="fixed inset-0 z-9999 flex items-center justify-center bg-rose-900/90 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-[3rem] p-10 w-full max-w-md text-center shadow-2xl relative overflow-hidden">
            <div class="w-24 h-24 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500 shadow-inner">
                 <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Hapus Warna?</h3>
            <p class="text-slate-500 font-medium text-sm mb-8">Warna tema ini tidak akan tersedia lagi untuk display TV.</p>
            <div class="flex gap-4">
                <button wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                <button wire:click="delete" class="flex-1 py-4 bg-rose-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all">Ya, Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>
