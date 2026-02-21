<div class="space-y-8 md:space-y-8 pb-10 work-sans-all" x-data="bannerManager()">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap');
        .work-sans-all, .work-sans-all * { font-family: 'Work Sans', sans-serif !important; }
        .floating-input:focus ~ label, .floating-input:not(:placeholder-shown) ~ label {
            transform: translateY(-1.25rem) scale(0.8); background-color: white; padding-left: 4px; padding-right: 4px; color: #10b981; font-weight: 800; text-transform: uppercase;
        }
        .floating-forced ~ label {
            transform: translateY(-1.25rem) scale(0.8); background-color: white; padding-left: 4px; padding-right: 4px; color: #10b981; font-weight: 800; text-transform: uppercase;
        }
    </style>

    <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-xl p-8 md:p-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none mb-2">Banner Info</h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Promosi & Informasi Visual</p>
            </div>

            @if($canEdit)
                <button wire:click="create" class="bg-emerald-500 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Banner
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
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-500 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest text-center shadow-lg animate-fade-in">
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @forelse($banners as $banner)
            @php
                $isExpired = $banner->tgl_selesai && \Carbon\Carbon::parse($banner->tgl_selesai)->isPast();
            @endphp
            <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden flex flex-col">
                <div class="relative h-52 bg-slate-100 overflow-hidden">
                    <img src="{{ Storage::url($banner->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">

                    <div class="absolute top-4 left-4 flex gap-2">
                        @if(!$banner->is_active)
                            <span class="bg-slate-900 text-white px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Non-Aktif</span>
                        @elseif($isExpired)
                            <span class="bg-rose-500 text-white px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest animate-pulse">Expired</span>
                        @else
                            <span class="bg-emerald-500 text-white px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">Aktif</span>
                        @endif
                    </div>
                </div>

                <div class="p-8">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-black text-slate-800 leading-tight uppercase tracking-tighter truncate pr-4">
                            {{ $banner->judul ?? 'Tanpa Judul' }}
                        </h3>

                        @if($canEdit)
                            <div class="flex gap-2 shrink-0">
                                <button wire:click="edit({{ $banner->id }})" class="p-2 bg-slate-50 text-slate-400 rounded-xl hover:bg-emerald-50 hover:text-emerald-500 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                <button wire:click="deleteId({{ $banner->id }})" class="p-2 bg-slate-50 text-slate-400 rounded-xl hover:bg-rose-50 hover:text-rose-500 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 text-slate-400">
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold uppercase tracking-widest text-slate-300">Mulai</span>
                            <span class="text-xs font-black text-slate-600">{{ $banner->tgl_mulai ? \Carbon\Carbon::parse($banner->tgl_mulai)->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="h-6 w-px bg-slate-100"></div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-bold uppercase tracking-widest text-slate-300">Selesai</span>
                            <span class="text-xs font-black text-slate-600">{{ $banner->tgl_selesai ? \Carbon\Carbon::parse($banner->tgl_selesai)->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center font-black text-slate-300 uppercase tracking-widest text-sm">Belum ada banner terpasang</div>
        @endforelse
    </div>

    <div class="mt-8">{{ $banners->links() }}</div>

    @if($isModalOpen && $canEdit)
    <div class="fixed inset-0 z-9999 flex items-center justify-center bg-slate-900/90 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-[3rem] p-10 w-full max-w-2xl shadow-2xl relative overflow-hidden overflow-y-auto max-h-[90vh] custom-scrollbar">
            <div class="absolute top-0 left-0 w-full h-2 bg-linear-to-r from-emerald-500 to-emerald-300"></div>

            <div class="flex justify-between items-center mb-10">
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight">{{ $isEditMode ? 'Edit' : 'Upload' }} Banner</h3>

                <label class="relative inline-flex items-center cursor-pointer group">
                    <input type="checkbox" wire:model="is_active" class="sr-only peer">
                    <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                    <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-emerald-500 transition-colors">
                        {{ $is_active ? 'Aktif' : 'Draft' }}
                    </span>
                </label>
            </div>

            <div class="space-y-8">
                <div class="relative group" x-data="{
                    isProcessing: false,
                    processBanner(event) {
                        const file = event.target.files[0];
                        if(!file) return;

                        this.isProcessing = true;

                        // Menentukan target ukuran kompresi: 50% lebih kecil
                        const originalSize = file.size;
                        const targetSize = originalSize * 0.5;

                        const reader = new FileReader();
                        reader.readAsDataURL(file);
                        reader.onload = (e) => {
                            const img = new Image();
                            img.src = e.target.result;
                            img.onload = () => {
                                const canvas = document.createElement('canvas');
                                let width = img.width;
                                let height = img.height;

                                // Resolusi maksimal Standar TV HD: 1920x1080
                                const maxSize = 1920;

                                if (width > height && width > maxSize) { height *= maxSize / width; width = maxSize; }
                                else if (height > maxSize) { width *= maxSize / height; height = maxSize; }

                                canvas.width = width;
                                canvas.height = height;
                                const ctx = canvas.getContext('2d');
                                ctx.drawImage(img, 0, 0, width, height);

                                // Algoritma rekursif untuk mencapai 50% reduksi ukuran
                                const attemptCompress = (q) => {
                                    canvas.toBlob((blob) => {
                                        // Cek jika ukuran blob masih lebih besar dari target & kualitas belum terlalu rendah (batas 0.6)
                                        if (blob.size > targetSize && q > 0.6) {
                                            attemptCompress(q - 0.1);
                                        } else {
                                            const processedFile = new File([blob], file.name, { type: 'image/jpeg' });
                                            @this.upload('photo', processedFile, () => {
                                                this.isProcessing = false;
                                            });
                                        }
                                    }, 'image/jpeg', q);
                                };

                                attemptCompress(0.9); // Start dengan kualitas 90%
                            }
                        }
                    }
                }">
                    <div class="w-full h-48 border-4 border-dashed border-slate-100 rounded-[2.5rem] bg-slate-50/50 flex flex-col items-center justify-center relative overflow-hidden transition-all group-hover:border-emerald-200 group-hover:bg-emerald-50/30">
                        <input type="file" @change="processBanner($event)" class="absolute inset-0 opacity-0 cursor-pointer z-10" accept="image/*">

                        <template x-if="!isProcessing">
                            <div class="text-center">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Pilih File Banner (Max 10MB)</p>
                                <p class="text-[8px] font-bold text-slate-300 mt-1">Otomatis Kompres 50% & HD TV Ready</p>
                            </div>
                        </template>

                        <div x-show="isProcessing" class="text-center">
                            <div class="w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500 italic">Optimizing Image...</p>
                        </div>

                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                        @elseif($existingImage)
                            <img src="{{ Storage::url($existingImage) }}" class="absolute inset-0 w-full h-full object-cover">
                        @endif
                    </div>
                </div>

                <div class="relative">
                    <input type="text" wire:model="judul" id="judul" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                    <label for="judul" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Judul Banner</label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <input type="date" wire:model="tgl_mulai" id="tgl_mulai" class="floating-forced peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" />
                        <label for="tgl_mulai" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Tanggal Mulai</label>
                    </div>
                    <div class="relative">
                        <input type="date" wire:model="tgl_selesai" id="tgl_selesai" class="floating-forced peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" />
                        <label for="tgl_selesai" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Tanggal Selesai</label>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 mt-12 border-t border-slate-100 pt-8">
                <button wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                <button wire:click="{{ $isEditMode ? 'update' : 'store' }}" class="flex-1 py-4 bg-emerald-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-200 transition-all">Simpan Banner</button>
            </div>
        </div>
    </div>
    @endif

    @if($isDeleteModalOpen && $canEdit)
    <div class="fixed inset-0 z-9999 flex items-center justify-center bg-rose-900/90 backdrop-blur-sm p-4 animate-fade-in">
        <div class="bg-white rounded-[3rem] p-10 w-full max-w-md text-center shadow-2xl relative overflow-hidden">
            <div class="w-24 h-24 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500 shadow-inner">
                 <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </div>
            <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Hapus Banner?</h3>
            <p class="text-slate-500 font-medium text-sm mb-8">Gambar banner akan dihapus dari server selamanya.</p>
            <div class="flex gap-4">
                <button wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                <button wire:click="delete" class="flex-1 py-4 bg-rose-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all">Ya, Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    function bannerManager() {
        return {
            // Logic Javascript tambahan jika diperlukan di masa depan
        }
    }
</script>
