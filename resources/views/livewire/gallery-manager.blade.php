<?php
    $initialImages = [];
    if(isset($photos) && $viewMode != 'albums') {
        $initialImages = $photos->map(fn($p) => [
            'id' => $p->id,
            'url' => Storage::url($p->file_path),
            'caption' => $p->caption
        ])->values()->toArray();
    }
?>

<div class="space-y-8 md:space-y-8 pb-10 work-sans-all"
     x-data="galleryManager()"
     x-init="initData({{ \Illuminate\Support\Js::from($initialImages) }})"
     @refresh-lightbox.window="updateImages($event.detail.data)"
>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap');
        .work-sans-all, .work-sans-all * { font-family: 'Work Sans', sans-serif !important; }
        .floating-input:focus ~ label, .floating-input:not(:placeholder-shown) ~ label, .floating-input:not([value=""]) ~ label {
            transform: translateY(-1.25rem) scale(0.8); background-color: white; padding-left: 4px; padding-right: 4px; color: #10b981; font-weight: 800; text-transform: uppercase;
        }
        .no-scroll { overflow: hidden; }
        .progress-bar-transition { transition: width 0.3s ease-out; }
        .lightbox-z-index { z-index: 99999 !important; }
    </style>

    <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-xl p-8 md:p-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div>
                @if($viewMode == 'albums')
                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none mb-2">Galeri Kegiatan</h1>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">Dokumentasi & Arsip Digital</p>
                @else
                    <button wire:click="backToAlbums" class="mb-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-emerald-500 flex items-center gap-1 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Kembali ke Album
                    </button>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none">{{ $activeGalleryTitle }}</h1>
                @endif
            </div>

            @if($viewMode == 'albums')
                @if($canEdit)
                    <button wire:click="createGallery" class="bg-emerald-500 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Buat Album
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
            @endif
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-emerald-500 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest text-center shadow-lg animate-fade-in">
            {{ session('message') }}
        </div>
    @endif

    @if($viewMode == 'albums')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($galleries as $gallery)
                <div class="group bg-white rounded-[2.5rem] border border-slate-100 shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col h-full">
                    <div class="relative h-56 bg-slate-100 overflow-hidden cursor-pointer" wire:click="openGallery({{ $gallery->id }})">
                        @if($gallery->photos->count() > 0)
                            <img src="{{ Storage::url($gallery->photos->first()->file_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-slate-50">
                                <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span class="text-[9px] font-black uppercase tracking-widest">Album Kosong</span>
                            </div>
                        @endif
                        <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-md text-white px-3 py-1 rounded-full text-[10px] font-black uppercase flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $gallery->photos->count() }} Foto
                        </div>
                    </div>
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-black text-slate-800 leading-tight mb-1 group-hover:text-emerald-500 transition-colors cursor-pointer" wire:click="openGallery({{ $gallery->id }})">
                                    {{ $gallery->nama_kegiatan }}
                                </h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    {{ $gallery->tanggal_kegiatan->format('d M Y') }}
                                </p>
                            </div>
                            @if($canEdit)
                                <button wire:click="editGallery({{ $gallery->id }})" class="text-slate-300 hover:text-emerald-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                            @endif
                        </div>
                        <p class="text-sm text-slate-500 line-clamp-2 mb-6 flex-1">
                            {{ $gallery->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $gallery->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }}">
                                {{ $gallery->is_active ? 'Published' : 'Draft' }}
                            </span>
                            @if($canEdit)
                                <button wire:click="deleteGalleryId({{ $gallery->id }})" class="text-rose-300 hover:text-rose-500 transition-colors text-xs font-bold uppercase tracking-wide">Hapus</button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center font-black text-slate-300 uppercase tracking-widest text-sm">Belum ada album kegiatan</div>
            @endforelse
        </div>
        <div class="mt-8">{{ $galleries->links() }}</div>

    @else
        <div class="space-y-8">

            @if($canEdit)
                <div class="relative w-full h-56 border-4 border-dashed border-emerald-100 rounded-[2.5rem] bg-emerald-50/30 flex flex-col items-center justify-center group hover:bg-emerald-50 hover:border-emerald-300 transition-all cursor-pointer overflow-hidden px-8">
                    <input type="file" multiple accept="image/*" @change="processFiles($event)" class="absolute inset-0 opacity-0 cursor-pointer z-50">

                    <div x-show="!isUploading" class="text-center transition-all group-hover:scale-105">
                        <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4 text-emerald-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                        </div>
                        <h3 class="text-lg font-black text-slate-700 uppercase tracking-tight">Upload Foto</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Kompresi Max 100Kb (Otomatis)</p>
                    </div>

                    <div x-show="isUploading" class="w-full max-w-lg text-center space-y-3" x-transition style="display: none;">
                        <div class="flex justify-between items-end">
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest text-left"><span class="block text-slate-400">Proses:</span> <span x-text="currentFileName" class="truncate max-w-50 block"></span></p>
                            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest text-right"><span x-text="filesProcessed"></span> / <span x-text="totalFiles"></span></p>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden shadow-inner"><div class="bg-linear-to-r from-emerald-400 to-emerald-600 h-full progress-bar-transition" :style="'width: ' + uploadProgress + '%'"></div></div>
                        <div class="flex justify-center gap-4 pt-2">
                            <div class="bg-white/50 px-3 py-1 rounded-lg border border-slate-200"><p class="text-[9px] font-bold text-slate-400 uppercase">Original</p><p class="text-xs font-black text-rose-500" x-text="formatBytes(totalOriginalSize)"></p></div>
                            <div class="flex items-center text-slate-300"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></div>
                            <div class="bg-white/50 px-3 py-1 rounded-lg border border-slate-200"><p class="text-[9px] font-bold text-slate-400 uppercase">Compressed</p><p class="text-xs font-black text-emerald-500" x-text="formatBytes(totalCompressedSize)"></p></div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 pb-24">
                @forelse($photos as $index => $photo)
                    <div class="group relative aspect-square rounded-2xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm hover:shadow-xl transition-all">

                        @if($canEdit)
                            <div class="absolute top-2 left-2 z-30">
                                <input type="checkbox" wire:model.live="selectedPhotos" value="{{ $photo->id }}" class="w-5 h-5 rounded-lg border-2 border-white/50 bg-black/20 text-emerald-500 focus:ring-0 checked:bg-emerald-500 checked:border-emerald-500 cursor-pointer shadow-sm">
                            </div>
                        @endif

                        <img src="{{ Storage::url($photo->file_path) }}"
                             @click="openLightbox({{ $index }})"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 cursor-zoom-in">

                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-3 pointer-events-none">
                            <div class="flex justify-end gap-2 pointer-events-auto">
                                <button type="button" wire:click="downloadPhoto({{ $photo->id }})" class="bg-white/20 hover:bg-emerald-500 hover:text-white text-white p-2 rounded-lg backdrop-blur-sm transition-colors" title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4-4m0 0l-4-4m4 4v12"></path></svg>
                                </button>
                                @if($canEdit)
                                    <button type="button" wire:click="confirmDeletePhoto({{ $photo->id }})" class="bg-white/20 hover:bg-rose-500 hover:text-white text-white p-2 rounded-lg backdrop-blur-sm transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                @endif
                            </div>

                            @if($canEdit)
                                <input type="text" value="{{ $photo->caption }}" wire:blur="updateCaption({{ $photo->id }}, $event.target.value)" class="pointer-events-auto w-full bg-white/20 backdrop-blur-md border-none rounded-lg text-white placeholder-white/70 text-[10px] font-bold px-2 py-1 focus:bg-white focus:text-slate-900 focus:ring-0 transition-all" placeholder="Caption...">
                            @else
                                <div class="pointer-events-none w-full bg-black/40 backdrop-blur-sm rounded-lg text-white text-[10px] font-bold px-2 py-1 truncate">{{ $photo->caption }}</div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center font-black text-slate-300 uppercase tracking-widest text-xs">Belum ada foto di album ini</div>
                @endforelse
            </div>

            @if(count($selectedPhotos) > 0 && $canEdit)
                <div class="fixed bottom-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-6 py-3 rounded-full shadow-2xl z-40 flex items-center gap-6 animate-bounce-in">
                    <span class="text-xs font-bold text-slate-400">{{ count($selectedPhotos) }} Foto Dipilih</span>
                    <div class="h-4 w-px bg-slate-700"></div>
                    <button type="button" wire:click="confirmBulkDelete" class="flex items-center gap-2 text-rose-500 hover:text-rose-400 font-bold text-xs uppercase tracking-wider transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus Terpilih
                    </button>
                </div>
            @endif

            <template x-if="lightboxOpen">
                <div class="fixed inset-0 lightbox-z-index bg-black/95 backdrop-blur-sm flex items-center justify-center">
                    <button type="button" @click="closeLightbox()" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors z-50 p-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="relative w-full h-full flex items-center justify-center p-4 md:p-10">
                        <button type="button" @click="prevImage()" class="absolute left-4 md:left-10 text-white/30 hover:text-white transition-colors p-4 rounded-full hover:bg-white/10 z-50">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <div class="relative max-w-full max-h-full flex flex-col items-center">
                            <template x-if="images[currentImageIndex]">
                                <img :src="images[currentImageIndex].url" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl border border-white/10">
                            </template>
                            <div class="mt-4 text-center" x-show="images[currentImageIndex]">
                                <p class="text-white font-bold text-lg tracking-wide" x-text="images[currentImageIndex]?.caption || 'Tanpa Caption'"></p>
                                <p class="text-slate-500 text-xs font-mono mt-1">
                                    <span x-text="currentImageIndex + 1"></span> / <span x-text="images.length"></span>
                                </p>
                            </div>
                        </div>
                        <button type="button" @click="nextImage()" class="absolute right-4 md:right-10 text-white/30 hover:text-white transition-colors p-4 rounded-full hover:bg-white/10 z-50">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>
    @endif

    @if($isModalOpen && $canEdit)
        <div class="fixed inset-0 z-9999 flex items-center justify-center bg-slate-900/90 backdrop-blur-sm p-4 animate-fade-in">
            <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-lg shadow-2xl relative overflow-hidden">
                 <div class="absolute top-0 left-0 w-full h-2 bg-linear-to-r from-emerald-500 to-emerald-300"></div>
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight">{{ $isEditMode ? 'Edit' : 'Buat' }} Album</h3>
                    <label class="relative inline-flex items-center cursor-pointer group">
                        <input type="checkbox" wire:model="is_active" class="sr-only peer">
                        <div class="w-14 h-8 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                        <span class="ml-3 text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-emerald-500 transition-colors">{{ $is_active ? 'Publik' : 'Draft' }}</span>
                    </label>
                </div>
                <div class="space-y-6">
                    <div class="relative">
                        <input type="text" wire:model="nama_kegiatan" id="nama_kegiatan" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                        <label for="nama_kegiatan" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Nama Kegiatan</label>
                        @error('nama_kegiatan') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="relative">
                        <input type="date" wire:model="tanggal_kegiatan" id="tanggal_kegiatan" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" " />
                        <label for="tanggal_kegiatan" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Tanggal Pelaksanaan</label>
                        @error('tanggal_kegiatan') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2">{{ $message }}</span> @enderror
                    </div>
                    <div class="relative">
                        <textarea wire:model="deskripsi" id="deskripsi" rows="3" class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-300 bg-white px-5 py-4 text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 placeholder-transparent font-bold" placeholder=" "></textarea>
                        <label for="deskripsi" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Deskripsi Singkat</label>
                    </div>
                </div>
                <div class="flex gap-4 mt-8 border-t border-slate-100 pt-6">
                    <button wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                    <button wire:click="{{ $isEditMode ? 'updateGallery' : 'storeGallery' }}" class="flex-1 py-4 bg-emerald-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-200 transition-all">Simpan Album</button>
                </div>
            </div>
        </div>
    @endif

    @if($isDeleteModalOpen && $canEdit)
        <div class="fixed inset-0 z-9999 flex items-center justify-center bg-rose-900/90 backdrop-blur-sm p-4 animate-fade-in">
            <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-md text-center shadow-2xl relative overflow-hidden">
                <div class="w-24 h-24 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500 shadow-inner"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></div>
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Hapus Album?</h3>
                <p class="text-slate-500 font-medium text-sm mb-8">Semua foto di dalam album ini akan ikut terhapus permanen.</p>
                <div class="flex gap-4">
                    <button wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                    <button wire:click="deleteGallery" class="flex-1 py-4 bg-rose-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif

    @if($isDeletePhotoModalOpen && $canEdit)
        <div class="fixed inset-0 z-9999 flex items-center justify-center bg-rose-900/90 backdrop-blur-sm p-4 animate-fade-in">
            <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-md text-center shadow-2xl relative overflow-hidden">
                <div class="w-24 h-24 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500 shadow-inner"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></div>
                <h3 class="text-2xl font-black text-slate-800 uppercase tracking-tight mb-2">Hapus Foto?</h3>
                <p class="text-slate-500 font-medium text-sm mb-8">
                    @if($photoToDeleteId) Foto ini akan dihapus permanen. @else {{ count($selectedPhotos) }} foto yang dipilih akan dihapus permanen. @endif
                </p>
                <div class="flex gap-4">
                    <button type="button" wire:click="closeModal" class="flex-1 py-4 rounded-xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all">Batal</button>
                    <button type="button" wire:click="deletePhotoAction" class="flex-1 py-4 bg-rose-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function galleryManager() {
        return {
            // --- LIGHTBOX DATA ---
            lightboxOpen: false,
            currentImageIndex: 0,
            images: [],

            initData(data) {
                this.images = data;
                this.handleKeydown = this.handleKeydown.bind(this);
            },

            updateImages(data) {
                this.images = data;
            },

            openLightbox(index) {
                if (!this.images[index]) return;
                this.currentImageIndex = index;
                this.lightboxOpen = true;
                document.body.classList.add('no-scroll');
                window.addEventListener('keydown', this.handleKeydown);
            },
            closeLightbox() {
                this.lightboxOpen = false;
                document.body.classList.remove('no-scroll');
                window.removeEventListener('keydown', this.handleKeydown);
            },
            nextImage() {
                if (this.images.length === 0) return;
                this.currentImageIndex = (this.currentImageIndex + 1) % this.images.length;
            },
            prevImage() {
                if (this.images.length === 0) return;
                this.currentImageIndex = (this.currentImageIndex - 1 + this.images.length) % this.images.length;
            },
            handleKeydown(e) {
                if (!this.lightboxOpen) return;
                if (e.key === 'ArrowRight') this.nextImage();
                if (e.key === 'ArrowLeft') this.prevImage();
                if (e.key === 'Escape') this.closeLightbox();
            },

            // --- UPLOAD LOGIC ---
            isUploading: false,
            uploadProgress: 0,
            totalFiles: 0,
            filesProcessed: 0,
            currentFileName: '',
            totalOriginalSize: 0,
            totalCompressedSize: 0,

            formatBytes(bytes, decimals = 2) {
                if (!+bytes) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
            },

            async processFiles(event) {
                let files = event.target.files;
                if (files.length === 0) return;

                this.isUploading = true;
                this.totalFiles = files.length;
                this.filesProcessed = 0;
                this.uploadProgress = 0;
                this.totalOriginalSize = 0;
                this.totalCompressedSize = 0;

                for (let i = 0; i < files.length; i++) {
                    this.currentFileName = files[i].name;
                    this.totalOriginalSize += files[i].size;

                    // Kirim nama file asli ke Backend
                    @this.call('setOriginalFileName', files[i].name);

                    // Jeda UI
                    await new Promise(resolve => setTimeout(resolve, 50));

                    // Proses Kompres & Upload (< 100Kb)
                    await this.compressAndUpload(files[i]);

                    this.filesProcessed++;
                    this.uploadProgress = Math.round((this.filesProcessed / this.totalFiles) * 100);

                    // Jeda UI
                    await new Promise(resolve => setTimeout(resolve, 100));
                }

                setTimeout(() => {
                    this.isUploading = false;
                    this.uploadProgress = 0;
                    event.target.value = '';
                }, 1000);
            },

            compressAndUpload(file) {
                return new Promise((resolve) => {
                    const reader = new FileReader();
                    reader.readAsDataURL(file);
                    reader.onload = (e) => {
                        const img = new Image();
                        img.src = e.target.result;
                        img.onload = () => {
                            const canvas = document.createElement('canvas');
                            let width = img.width;
                            let height = img.height;

                            // Kita turunkan Max Size sedikit supaya lebih gampang capai target < 100Kb
                            const maxSize = 1200;
                            if (width > height) {
                                if (width > maxSize) { height *= maxSize / width; width = maxSize; }
                            } else {
                                if (height > maxSize) { width *= maxSize / height; height = maxSize; }
                            }

                            canvas.width = width;
                            canvas.height = height;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);

                            // TARGET KOMPRESI: 100Kb (100 * 1024 bytes)
                            const targetSize = 100 * 1024;

                            // Fungsi rekursif untuk menurunkan kualitas sampai tembus target
                            const attemptCompress = (q) => {
                                canvas.toBlob((blob) => {
                                    if (blob.size > targetSize && q > 0.1) {
                                        // Turunkan kualitas sebesar 15% setiap iterasi
                                        attemptCompress(q - 0.15);
                                    } else {
                                        // Target tercapai atau sudah mentok di kualitas terendah
                                        this.totalCompressedSize += blob.size;
                                        const fileNew = new File([blob], file.name, { type: 'image/jpeg' });

                                        // Upload ke Livewire
                                        @this.upload('photo', fileNew, (uploadedFilename) => {
                                            @this.call('savePhoto').then(() => {
                                                resolve(); // Lanjut loop file berikutnya
                                            });
                                        }, () => {
                                            console.error('Upload failed');
                                            resolve(); // Lanjut meskipun gagal supaya tidak nyangkut
                                        });
                                    }
                                }, 'image/jpeg', q);
                            };

                            // Mulai kompresi di kualitas 80%
                            attemptCompress(0.8);
                        }
                    }
                });
            }
        }
    }
</script>
