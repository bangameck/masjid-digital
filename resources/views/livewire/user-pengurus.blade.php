<div class="space-y-8 md:space-y-8 pb-20 work-sans-all">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@100..900&display=swap');
        .work-sans-all, .work-sans-all * { font-family: 'Work Sans', sans-serif !important; font-variant-numeric: tabular-nums; }

        /* Fix Modal Scrollbar */
        .modal-scroll::-webkit-scrollbar { width: 6px; }
        .modal-scroll::-webkit-scrollbar-track { background: transparent; }
        .modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>

    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between items-center gap-8">
        <div class="flex items-center gap-6">
            <div class="w-20 h-20 bg-emerald-50 rounded-4xl flex items-center justify-center border border-emerald-100 text-emerald-600 shadow-inner">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase leading-none">Manajemen SDM</h1>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mt-2 flex items-center gap-2"><span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span> Pengurus & Akun Sistem</p>
            </div>
        </div>

        <div class="flex items-center gap-2 bg-slate-50 p-2 rounded-4xl border border-slate-200 shadow-inner">
            <button wire:click="switchTab('pengurus')" class="px-8 py-3 rounded-3xl font-black text-[10px] uppercase tracking-widest transition-all {{ $activeTab == 'pengurus' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">Struktur Pengurus</button>

            @if(auth()->user()->role === 'superadmin')
            <button wire:click="switchTab('users')" class="px-8 py-3 rounded-3xl font-black text-[10px] uppercase tracking-widest transition-all {{ $activeTab == 'users' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600' }}">Akun Admin</button>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-[3.5rem] border border-slate-100 shadow-2xl shadow-slate-200/40 overflow-hidden min-h-125 relative">
        <div class="px-12 py-10 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center bg-slate-50/30 gap-4">
            <div class="relative w-full md:w-auto">
                <input wire:model.live="search" type="text" placeholder="Cari data..." class="pl-12 pr-6 py-3 bg-white border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:ring-emerald-500 focus:border-emerald-500 w-full md:w-80 shadow-sm transition-all">
                <svg class="w-5 h-5 text-slate-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            @if($canEdit)
                <button wire:click="create" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-xl active:scale-95 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah {{ $activeTab == 'users' ? 'Superadmin' : 'Pengurus' }}
                </button>
            @else
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 px-5 py-3 rounded-2xl shadow-sm animate-fade-in w-full md:w-auto">
                    <div class="p-2 bg-amber-500 rounded-lg text-white shadow-sm shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m0-8V7m0 0v2m-9 1l1 1h16l1-1v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4z"></path>
                            <circle cx="12" cy="11" r="9" stroke="currentColor" stroke-width="2"></circle>
                        </svg>
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
            <div class="fixed top-6 left-1/2 -translate-x-1/2 bg-emerald-500 text-white px-8 py-4 rounded-2xl font-black text-xs shadow-[0_10px_40px_rgba(16,185,129,0.5)] z-99999 animate-bounce flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="fixed top-6 left-1/2 -translate-x-1/2 bg-rose-500 text-white px-8 py-4 rounded-2xl font-black text-xs shadow-[0_10px_40px_rgba(244,63,94,0.5)] z-99999 animate-bounce flex items-center gap-3">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-slate-400 text-[9px] uppercase tracking-[0.2em] font-black border-b border-slate-50 bg-slate-50/40">
                        @if ($activeTab == 'pengurus')
                            <th class="px-12 py-8">Profil</th><th class="px-6 py-8">Jabatan</th><th class="px-6 py-8">Kontak</th><th class="px-6 py-8 text-center">Status</th>
                        @else
                            <th class="px-12 py-8">Nama Superadmin</th><th class="px-6 py-8">Email Login</th><th class="px-6 py-8">Terdaftar</th>
                        @endif
                        <th class="px-12 py-8 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($data as $item)
                        <tr class="hover:bg-slate-50 transition-all duration-300 group">
                            @if ($activeTab == 'pengurus')
                                <td class="px-12 py-6">
                                    <div class="flex items-center gap-5">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 overflow-hidden border border-slate-200 shrink-0">
                                            @if ($item->foto_path) <img src="{{ Storage::url($item->foto_path) }}" class="w-full h-full object-cover">
                                            @else <div class="w-full h-full flex items-center justify-center text-slate-300"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg></div> @endif
                                        </div>
                                        <div class="font-black text-slate-800 text-sm">{{ $item->nama }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-6 font-bold text-xs text-slate-500 tracking-wide">{{ $item->jabatan }}</td>
                                <td class="px-6 py-6 font-bold text-xs text-slate-500">{{ $item->no_hp ?? '-' }}</td>
                                <td class="px-6 py-6 text-center">
                                    @if($canEdit)
                                        <button wire:click="toggleStatus({{ $item->id }})" class="px-4 py-2 rounded-xl text-[10px] font-black tracking-widest transition-all {{ $item->is_active ? 'bg-emerald-100 text-emerald-600 hover:bg-emerald-200' : 'bg-rose-100 text-rose-500 hover:bg-rose-200' }}">{{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}</button>
                                    @else
                                        <span class="px-4 py-2 rounded-xl text-[10px] font-black tracking-widest {{ $item->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-500' }}">{{ $item->is_active ? 'Aktif' : 'Non-Aktif' }}</span>
                                    @endif
                                </td>
                            @else
                                <td class="px-12 py-6 font-black text-slate-800 text-sm">
                                    {{ $item->name }}
                                    @if($item->id === auth()->id()) <span class="text-[8px] bg-emerald-100 text-emerald-600 px-2 py-0.5 rounded-md ml-2 uppercase">Anda</span> @endif
                                </td>
                                <td class="px-6 py-6 font-bold text-xs text-slate-500">{{ $item->email }}</td>
                                <td class="px-6 py-6 font-bold text-xs text-slate-400">{{ $item->created_at->format('d M Y') }}</td>
                            @endif

                            <td class="px-12 py-6 text-right">
                                @if($canEdit)
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                        <button wire:click="edit({{ $item->id }})" class="p-2 bg-slate-100 rounded-xl text-slate-600 hover:bg-emerald-500 hover:text-white transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></button>
                                        @if($activeTab == 'pengurus' || ($activeTab == 'users' && $item->id !== auth()->id()))
                                        <button wire:click="deleteId({{ $item->id }})" class="p-2 bg-rose-50 rounded-xl text-rose-500 hover:bg-rose-500 hover:text-white transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest block text-right">Read Only</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-12 py-20 text-center font-black text-slate-300 tracking-widest text-xs">Tidak ada data ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-10 border-t border-slate-50 bg-slate-50/20 flex justify-center">{{ $data->links() }}</div>
    </div>

    @if ($isModalOpen && $canEdit)
        <div class="fixed inset-0 z-9999 flex items-center justify-center bg-slate-900/80 backdrop-blur-md p-4">
            <div class="bg-white rounded-[2.5rem] p-8 md:p-10 w-full max-w-3xl shadow-2xl relative overflow-hidden animate-fade-in max-h-[90vh] overflow-y-auto modal-scroll">

                <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-6">
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ $isEditMode ? 'Edit' : 'Tambah' }} {{ $activeTab == 'users' ? 'Superadmin' : 'Data Pengurus' }}</h3>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Lengkapi Form di bawah ini</p>
                    </div>
                    <button wire:click="closeModal" class="bg-slate-100 p-3 rounded-full hover:bg-rose-100 hover:text-rose-500 transition-all rotate-0 hover:rotate-90">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="space-y-6">
                    @if ($activeTab == 'pengurus')
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            <div class="w-full md:w-1/3 flex flex-col gap-4" x-data="{
                                isCompressing: false, progress: 0, statusText: '', origSize: '', newSize: '',
                                async compressImage(event) {
                                    const file = event.target.files[0];
                                    if (!file) return;

                                    this.isCompressing = true; this.progress = 10;
                                    let kb = file.size / 1024;
                                    this.origSize = kb > 1024 ? (kb / 1024).toFixed(2) + 'MB' : kb.toFixed(0) + 'Kb';
                                    this.statusText = 'Membaca...';

                                    const reader = new FileReader();
                                    reader.readAsDataURL(file);
                                    reader.onload = (e) => {
                                        const img = new Image(); img.src = e.target.result;
                                        img.onload = () => {
                                            this.progress = 30; this.statusText = 'Menyesuaikan...';
                                            const canvas = document.createElement('canvas');
                                            let width = img.width; let height = img.height; const maxSize = 800;
                                            if (width > height) { if (width > maxSize) { height *= maxSize / width; width = maxSize; } }
                                            else { if (height > maxSize) { width *= maxSize / height; height = maxSize; } }
                                            canvas.width = width; canvas.height = height;
                                            const ctx = canvas.getContext('2d'); ctx.drawImage(img, 0, 0, width, height);

                                            const targetSize = 50 * 1024;
                                            const attemptCompress = (q) => {
                                                canvas.toBlob((blob) => {
                                                    if (blob.size > targetSize && q > 0.1) {
                                                        this.progress += 10; this.statusText = 'Kompresi...';
                                                        attemptCompress(q - 0.15);
                                                    } else {
                                                        this.newSize = (blob.size / 1024).toFixed(0) + 'Kb';
                                                        this.statusText = `${this.origSize} ➔ ${this.newSize}`;
                                                        this.progress = 80;
                                                        const fileNew = new File([blob], file.name, { type: 'image/jpeg' });
                                                        @this.upload('foto', fileNew, () => {
                                                            this.progress = 100;
                                                            setTimeout(() => { this.isCompressing = false; }, 2000);
                                                        });
                                                    }
                                                }, 'image/jpeg', q);
                                            };
                                            attemptCompress(0.8);
                                        }
                                    }
                                }
                            }">
                                <div class="relative w-full aspect-square bg-slate-50/50 rounded-[2.5rem] border-2 border-dashed border-slate-300 overflow-hidden group hover:bg-emerald-50 hover:border-emerald-300 transition-colors cursor-pointer">
                                    @if ($foto) <img src="{{ $foto->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @elseif($foto_path) <img src="{{ Storage::url($foto_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 group-hover:text-emerald-500 transition-colors">
                                            <svg class="w-10 h-10 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[9px] font-black uppercase tracking-widest">Pilih Foto</span>
                                        </div>
                                    @endif
                                    <input type="file" accept="image/*" @change="compressImage($event)" class="absolute inset-0 opacity-0 cursor-pointer z-10">

                                    <div x-show="isCompressing" class="absolute inset-0 bg-slate-900/80 backdrop-blur-sm flex flex-col items-center justify-center text-white z-20 transition-all p-4">
                                        <div class="w-full bg-slate-700 rounded-full h-2 mb-3 overflow-hidden">
                                            <div class="bg-emerald-500 h-2 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
                                        </div>
                                        <div class="text-[10px] font-black uppercase tracking-widest text-emerald-400 mb-1" x-text="progress + '%'"></div>
                                        <div class="text-[8px] font-bold tracking-widest text-slate-300 text-center" x-text="statusText"></div>
                                    </div>
                                </div>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest text-center">Otomatis Kompres &lt; 50Kb</p>
                            </div>

                            <div class="w-full md:w-2/3 grid grid-cols-1 gap-6">
                                <div class="relative mt-2">
                                    <input type="text" id="nama_pengurus" wire:model="nama_pengurus" class="floating-input block w-full rounded-2xl border border-slate-300 bg-white px-5 py-4 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 transition-colors" placeholder=" " />
                                    <label for="nama_pengurus" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Nama Lengkap</label>
                                    @error('nama_pengurus') <span class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-2 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="relative mt-2">
                                    <select id="jabatan" wire:model="jabatan" class="floating-input block w-full rounded-2xl border border-slate-300 bg-white px-5 py-4 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 transition-colors cursor-pointer" placeholder=" ">
                                        <option value="" hidden></option>
                                        <option value="Ketua">Ketua</option>
                                        <option value="Sekertaris">Sekertaris</option>
                                        <option value="Bendahara">Bendahara</option>
                                        <option value="Penasehat">Penasehat</option>
                                        <option value="Humas">Humas</option>
                                        <option value="Sosial">Sosial</option>
                                        <option value="Operator">Operator</option>
                                        <option value="Marbot">Marbot</option>
                                        <option value="Lainnya">Lainnya...</option>
                                    </select>
                                    <label for="jabatan" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Pilih Jabatan</label>
                                    @error('jabatan') <span class="text-[10px] text-rose-500 font-bold uppercase mt-1 ml-2 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="relative mt-2">
                                    <input type="text" id="no_hp" wire:model="no_hp" class="floating-input block w-full rounded-2xl border border-slate-300 bg-white px-5 py-4 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 transition-colors" placeholder=" " />
                                    <label for="no_hp" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">No. HP / WhatsApp</label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50/50 p-6 rounded-[2.5rem] border border-slate-100 mt-6 space-y-5 relative overflow-hidden">
                            <h4 class="text-[10px] font-black text-emerald-600 uppercase tracking-[0.2em] flex items-center gap-2 relative z-10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Akses Login Aplikasi
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 relative z-10">
                                <div class="relative mt-2">
                                    <input type="email" id="email" wire:model="email" class="floating-input block w-full rounded-2xl border border-slate-300 bg-white px-5 py-4 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 transition-colors" placeholder=" " />
                                    <label for="email" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Alamat Email</label>
                                    @error('email') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="relative mt-2" x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" id="password" wire:model="password" class="floating-input block w-full rounded-2xl border border-slate-300 bg-white px-5 py-4 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 transition-colors pr-12" placeholder=" " />
                                    <label for="password" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">{{ $isEditMode ? 'Ganti Password (Opsional)' : 'Password Baru' }}</label>

                                    <button type="button" @click="show = !show" class="absolute right-4 top-4 text-slate-400 hover:text-emerald-500 transition-colors">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="show" class="w-5 h-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.533-3.034M10.125 5.175A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.533 3.034M9 9l6 6m-6 0l6-6"></path></svg>
                                    </button>

                                    @error('password') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-6">
                            <div class="relative mt-2">
                                <input type="text" id="name" wire:model="name" class="floating-input block w-full rounded-2xl border border-slate-300 bg-white px-5 py-4 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 transition-colors" placeholder=" " />
                                <label for="name" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Nama Superadmin</label>
                                @error('name') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="relative mt-2">
                                    <input type="email" id="email_admin" wire:model="email" class="floating-input block w-full rounded-2xl border border-slate-300 bg-white px-5 py-4 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 transition-colors" placeholder=" " />
                                    <label for="email_admin" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">Email Login</label>
                                    @error('email') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="relative mt-2" x-data="{ show: false }">
                                    <input :type="show ? 'text' : 'password'" id="password_admin" wire:model="password" class="floating-input block w-full rounded-2xl border border-slate-300 bg-white px-5 py-4 text-sm font-bold text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-0 transition-colors pr-12" placeholder=" " />
                                    <label for="password_admin" class="absolute top-4 left-5 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">{{ $isEditMode ? 'Ganti Password (Opsional)' : 'Password' }}</label>

                                    <button type="button" @click="show = !show" class="absolute right-4 top-4 text-slate-400 hover:text-emerald-500 transition-colors">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <svg x-show="show" class="w-5 h-5" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.533-3.034M10.125 5.175A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.533 3.034M9 9l6 6m-6 0l6-6"></path></svg>
                                    </button>

                                    @error('password') <span class="text-[10px] text-rose-500 font-bold mt-1 ml-2 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex gap-4 mt-10 pt-6 border-t border-slate-100">
                    <button wire:click="closeModal" class="w-1/3 py-4 rounded-2xl font-black text-slate-400 text-xs uppercase tracking-widest hover:bg-slate-50 transition-all border border-transparent hover:border-slate-200">Batal</button>
                    <button wire:click="{{ $isEditMode ? 'update' : 'store' }}" wire:loading.attr="disabled" class="w-2/3 py-4 bg-emerald-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-emerald-600 shadow-lg shadow-emerald-200 transition-all flex items-center justify-center gap-3 active:scale-[0.98]">
                        <svg wire:loading wire:target="{{ $isEditMode ? 'update' : 'store' }}" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span wire:loading.remove wire:target="{{ $isEditMode ? 'update' : 'store' }}">Simpan {{ $isEditMode ? 'Perubahan' : 'Data' }}</span>
                        <span wire:loading wire:target="{{ $isEditMode ? 'update' : 'store' }}">Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($isDeleteModalOpen && $canEdit)
        <div class="fixed inset-0 z-9999 flex items-center justify-center bg-rose-900/80 backdrop-blur-md p-4">
            <div class="bg-white rounded-[2.5rem] p-10 w-full max-w-md text-center shadow-2xl animate-fade-in relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-2 bg-linear-to-r from-rose-500 to-rose-300"></div>
                <div class="w-24 h-24 bg-rose-50 border-[6px] border-white shadow-lg rounded-full flex items-center justify-center mx-auto mb-6 text-rose-500 relative z-10">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-3xl font-black text-slate-800 tracking-tight mb-2">Hapus Data?</h3>
                <p class="text-slate-500 font-medium text-sm mb-8 px-4">Data admin/pengurus ini beserta akses loginnya akan dihapus permanen.</p>
                <div class="flex gap-4">
                    <button wire:click="closeModal" class="flex-1 py-4 rounded-2xl font-black text-slate-500 text-xs uppercase tracking-widest bg-slate-50 hover:bg-slate-100 transition-colors border border-slate-200">Batal</button>
                    <button wire:click="delete" class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-rose-600 shadow-lg shadow-rose-200 transition-all active:scale-95">Ya, Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>
