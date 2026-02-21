<div class="bg-white rounded-[3.5rem] shadow-2xl overflow-hidden border border-white/20">

    <div class="h-2 w-full bg-linear-to-r from-emerald-500 to-emerald-300"></div>

    <div class="p-10 md:p-12">

        <div class="text-center mb-10">
            <div
                class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-slate-50 mb-6 shadow-[inset_0_2px_10px_rgba(0,0,0,0.05)] border-[6px] border-white ring-1 ring-slate-100 overflow-hidden relative group">
                @if ($setting && $setting->logo_path)
                    <img src="{{ Storage::url($setting->logo_path) }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        alt="Logo">
                @else
                    <svg class="w-10 h-10 text-emerald-500 group-hover:scale-110 transition-transform duration-500"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                @endif
            </div>

            <h2 class="text-2xl font-black text-slate-900 tracking-tighter uppercase leading-tight">
                {{ $setting->nama_masjid ?? 'Masjid Digital' }}
            </h2>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mt-2 px-4">
                {{ $setting->alamat ?? 'Silahkan Login Untuk Mengelola Sistem' }}
            </p>
        </div>

        <form wire:submit="login" class="space-y-6">
            <div class="relative">
                <input wire:model="email" type="email" id="email" required placeholder=" "
                    class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50/50 px-6 py-4 text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-0 placeholder-transparent font-bold transition-all" />
                <label for="email"
                    class="absolute top-4 left-6 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">
                    Email Address
                </label>
                @error('email')
                    <span
                        class="text-[10px] text-rose-500 font-bold mt-1 ml-2 uppercase absolute -bottom-5 left-0">{{ $message }}</span>
                @enderror
            </div>

            <div class="relative" x-data="{ show: false }">
                <input wire:model="password" :type="show ? 'text' : 'password'" id="password" required placeholder=" "
                    class="floating-input peer block w-full appearance-none rounded-2xl border border-slate-200 bg-slate-50/50 px-6 py-4 pr-12 text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none focus:ring-0 placeholder-transparent font-bold transition-all" />
                <label for="password"
                    class="absolute top-4 left-6 text-slate-400 transition-all duration-200 ease-out pointer-events-none origin-left">
                    Password
                </label>

                <button type="button" @click="show = !show"
                    class="absolute right-4 top-4 text-slate-400 hover:text-emerald-500 focus:outline-none transition-colors">
                    <svg x-show="!show" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
                @error('password')
                    <span
                        class="text-[10px] text-rose-500 font-bold mt-1 ml-2 uppercase absolute -bottom-5 left-0">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-between px-2 pt-2">
                <label class="flex items-center group cursor-pointer">
                    <input type="checkbox" wire:model="remember"
                        class="w-5 h-5 rounded-lg border-slate-300 text-emerald-500 focus:ring-emerald-500/20 transition-all cursor-pointer">
                    <span
                        class="ml-3 text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">Ingat
                        Saya</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="relative flex w-full justify-center rounded-2xl bg-slate-900 px-6 py-4 text-xs font-black uppercase tracking-[0.2em] text-white shadow-[0_10px_20px_rgba(15,23,42,0.15)] hover:bg-emerald-500 hover:shadow-[0_10px_20px_rgba(16,185,129,0.3)] hover:-translate-y-1 active:scale-95 transition-all duration-300 overflow-hidden">
                    <span wire:loading.remove>Masuk Dashboard</span>
                    <div wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span>Memverifikasi...</span>
                    </div>
                </button>
            </div>
        </form>

        <div class="mt-10 pt-8 border-t border-slate-100">
            <div class="flex items-center justify-center gap-2 mb-5">
                <div class="h-px w-8 bg-slate-200"></div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Akses Publik Jamaah</p>
                <div class="h-px w-8 bg-slate-200"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="/live-display" wire:navigate
                    class="flex flex-col items-center justify-center rounded-2xl bg-slate-50 border border-slate-200 p-4 text-center hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-600 active:scale-95 transition-all duration-300 group">
                    <div
                        class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-emerald-500 mb-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-emerald-600 transition-colors">Layar<br>Display</span>
                </a>

                <a href="{{ route('keuangan.publik') }}"
                    class="flex flex-col items-center justify-center rounded-2xl bg-slate-50 border border-slate-200 p-4 text-center hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-600 active:scale-95 transition-all duration-300 group">
                    <div
                        class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-slate-400 group-hover:text-emerald-500 mb-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="text-[10px] font-black uppercase tracking-widest text-slate-500 group-hover:text-emerald-600 transition-colors">Arus<br>Keuangan</span>
                </a>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-50">
            <p class="text-center text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] leading-relaxed">
                &copy; {{ date('Y') }} {{ $setting->nama_masjid ?? 'Masjid Digital' }}
                <br>
                <span class="mt-1 block opacity-80">Developed by
                    <a href="https://instagram.com/bangameck" target="_blank"
                        class="text-emerald-500 font-black hover:text-emerald-600 transition-all hover:tracking-[0.4em] duration-300">
                        RadevankaProject
                    </a>
                </span>
            </p>
        </div>
    </div>
</div>
