<?php
/**
 * Aplikasi Masjid Digital
 * * @author RadevankaProject (@bangameck)
 * @link https://github.com/bangameck/masjid-digital
 * @license MIT
 * * Dibuat dengan niat amal jariyah untuk digitalisasi masjid.
 * Tolong jangan hapus hak cipta ini.
 */

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Keuangan;
use App\Models\JadwalSholat;
use App\Models\AppSetting;
use App\Models\RunningText;
use App\Models\Pengurus;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

#[Layout('components.layouts.app')]
#[Title('Dashboard Utama')]
class Dashboard extends Component
{
    public $theme_color = 'emerald';

    public function mount()
    {
        $settings = AppSetting::first();
        if ($settings) {
            $this->theme_color = $settings->theme_color ?? 'emerald';
        }
    }

    public function changeTheme($color)
    {
        $settings = AppSetting::first() ?? AppSetting::create(['nama_masjid' => 'Masjid Digital']);
        $settings->update(['theme_color' => $color]);
        $this->theme_color = $color;

        return $this->redirectRoute('dashboard', navigate: true);
    }

    public function render()
    {
        // 1. DATA RINGKASAN
        $totalPemasukan = Keuangan::where('kategori', 'pemasukan')->sum('nominal');
        $totalPengeluaran = Keuangan::where('kategori', 'pengeluaran')->sum('nominal');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $bulanIni = date('m');
        $tahunIni = date('Y');

        $pemasukanBulanIni = Keuangan::where('kategori', 'pemasukan')
            ->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->sum('nominal');
        $pengeluaranBulanIni = Keuangan::where('kategori', 'pengeluaran')
            ->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni)->sum('nominal');

        $jadwal = JadwalSholat::where('tanggal', date('Y-m-d'))->first();
        $total_info = RunningText::where('is_active', true)->count();

        // 2. DATA GRAFIK 1 TAHUN TERAKHIR (12 Bulan)
        $chartCategories = [];
        $chartPemasukan = [];
        $chartPengeluaran = [];

        // Loop mundur 12 bulan
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->startOfMonth()->subMonths($i);
            $chartCategories[] = $date->translatedFormat('M Y');

            $chartPemasukan[] = Keuangan::where('kategori', 'pemasukan')
                ->whereMonth('tanggal', $date->month)->whereYear('tanggal', $date->year)->sum('nominal');
            $chartPengeluaran[] = Keuangan::where('kategori', 'pengeluaran')
                ->whereMonth('tanggal', $date->month)->whereYear('tanggal', $date->year)->sum('nominal');
        }

        // 3. BREAKDOWN SUB-KATEGORI BULAN INI
        $subKategori = Keuangan::whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->selectRaw('sub_kategori, kategori, SUM(nominal) as total')
            ->groupBy('sub_kategori', 'kategori')
            ->orderByDesc('total')
            ->get();

        // 4. DATA PENGURUS BERDASARKAN HIRARKI
        $hierarchy = [
            'ketua' => 1,
            'sekretaris' => 2,
            'sekertaris' => 2, // Mengantisipasi typo saat input
            'bendahara' => 3,
            'penasehat' => 4,
            'humas' => 5,
            'sosial' => 6,
            'operator' => 7,
            'marbot' => 8,
        ];

        // Ambil data dan urutkan menggunakan closure berdasarkan array hierarchy
        $pengurus = Pengurus::where('is_active', true)->get()->sortBy(function($item) use ($hierarchy) {
            return $hierarchy[strtolower($item->jabatan)] ?? 99; // 99 untuk jabatan 'Lainnya...'
        })->values();

        return view('livewire.dashboard', [
            'saldo' => $saldo,
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            'jadwal' => $jadwal,
            'total_info' => $total_info,
            'chartData' => [
                'categories' => $chartCategories,
                'pemasukan' => $chartPemasukan,
                'pengeluaran' => $chartPengeluaran,
            ],
            'subKategori' => $subKategori,
            'pengurus' => $pengurus
        ]);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
