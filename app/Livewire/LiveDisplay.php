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
use App\Models\AppSetting;
use App\Models\JadwalSholat;
use App\Models\RunningText;
use App\Models\Banner;
use App\Models\PetugasJumat;
use App\Models\PetugasRamadhan;
use App\Models\PetugasIed;
use App\Models\PengajianRutin;
use App\Models\Content;
use App\Models\Keuangan;
use App\Models\Rekening;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.display')]
#[Title('Live Display')]
class LiveDisplay extends Component
{
    public function render()
    {
        $today = date('Y-m-d');
        $settings = AppSetting::getSettings(true);
        $tipeTempat = $settings->tipe_tempat ?? 'Masjid';

        // 1. DATA JADWAL & KOREKSI WAKTU DINAMIS
        $jadwal = JadwalSholat::where('tanggal', $today)->first();
        if ($jadwal && $settings) {
            $waktuSholat = ['imsak','subuh', 'dhuha', 'dzuhur', 'ashar', 'maghrib', 'isya'];
            foreach ($waktuSholat as $waktu) {
                $koreksiField = 'koreksi_' . $waktu;
                $koreksi = (int) ($settings->$koreksiField ?? 0);

                if ($koreksi !== 0 && isset($jadwal->$waktu)) {
                    $jadwal->$waktu = Carbon::parse($jadwal->$waktu)->addMinutes($koreksi)->format('H:i:s');
                }
            }
        }

        $runningTexts = RunningText::where('is_active', true)->orderBy('urutan')->get();

        $banners = Banner::where('is_active', true)
            ->whereDate('tgl_mulai', '<=', $today)
            ->whereDate('tgl_selesai', '>=', $today)
            ->get();

        $contents = Content::where('is_active', true)->get();

        // 2. GABUNGAN TABEL INFO KAJIAN (DINAMIS BERDASARKAN TIPE TEMPAT)
        $ceramahList = collect();

        // - Pengajian Rutin
        $rutin = PengajianRutin::where('tanggal', '>=', $today)->get()->map(function($item) {
            return (object) [
                'tanggal' => $item->tanggal, 'kategori' => 'Kajian Rutin',
                'tokoh' => $item->penceramah, 'judul' => $item->judul_ceramah,
                'imam' => null, 'muadzin' => null, 'bilal' => null
            ];
        });
        $ceramahList = $ceramahList->concat($rutin);

        // - Petugas Jumat (HANYA MUNCUL JIKA TIPE TEMPAT ADALAH MASJID)
        if ($tipeTempat !== 'Mushola') {
            $jumat = PetugasJumat::where('tanggal', '>=', $today)->get()->map(function($item) {
                return (object) [
                    'tanggal' => $item->tanggal, 'kategori' => 'Sholat Jumat',
                    'tokoh' => $item->khatib, 'judul' => $item->judul_ceramah ?? 'Khatib Jumat',
                    'imam' => $item->imam, 'muadzin' => $item->muadzin, 'bilal' => $item->bilal
                ];
            });
            $ceramahList = $ceramahList->concat($jumat);
        }

        // - Petugas Ramadhan
        $ramadhan = PetugasRamadhan::where('tanggal', '>=', $today)->get()->map(function($item) {
            return (object) [
                'tanggal' => $item->tanggal, 'kategori' => 'Tarawih (Malam ke-'.$item->malam_ke.')',
                'tokoh' => $item->penceramah, 'judul' => $item->judul_ceramah ?? 'Penceramah Tarawih',
                'imam' => $item->imam, 'muadzin' => $item->muadzin, 'bilal' => $item->bilal
            ];
        });
        $ceramahList = $ceramahList->concat($ramadhan);

        // - Petugas Ied
        $ied = PetugasIed::where('tanggal', '>=', $today)->get()->map(function($item) {
            return (object) [
                'tanggal' => $item->tanggal, 'kategori' => 'Sholat '.$item->ied,
                'tokoh' => $item->khatib, 'judul' => $item->judul_ceramah ?? 'Khatib '.$item->ied,
                'imam' => $item->imam, 'muadzin' => $item->muadzin, 'bilal' => $item->bilal
            ];
        });
        $ceramahList = $ceramahList->concat($ied);

        // Sortir dari tanggal terdekat dan ambil 5 data teratas
        $ceramah = $ceramahList->sortBy('tanggal')->take(5)->values();

        // 3. DATA KEUANGAN & REKENING CAROUSEL
        $pemasukan = Keuangan::where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaran = Keuangan::where('kategori', 'pengeluaran')->sum('nominal');
        $totalSaldo = $pemasukan - $pengeluaran;
        $rekenings = Rekening::where('is_active', true)->get();

        return view('livewire.live-display', [
            'settings' => $settings,
            'tipeTempat' => $tipeTempat,
            'jadwal' => $jadwal,
            'runningTexts' => $runningTexts,
            'banners' => $banners,
            'contents' => $contents,
            'ceramah' => $ceramah,
            'totalSaldo' => $totalSaldo,
            'rekenings' => $rekenings
        ]);
    }
}
