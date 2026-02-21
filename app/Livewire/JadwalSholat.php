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
use Livewire\WithPagination;
use App\Models\JadwalSholat as JadwalModel;
use App\Models\AppSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Jadwal Sholat')]
class JadwalSholat extends Component
{
    use WithPagination;

    public $tahun_generate, $bulan_generate, $bulan_filter;
    public $isGenerating = false;
    public $progress = 0;
    public $statusText = '';
    public $currentDay = 1;
    public $totalDaysInMonth = 0;

    public $currentDate;

    // --- PROPERTY UNTUK EDIT INLINE HIJRIAH ---
    public $editingId = null;
    public $hijriahText = '';

    // --- PROPERTY UNTUK STATUS API ---
    public $apiStatusJadwal = 'Menunggu...';
    public $apiStatusHijri = 'Menunggu...';

    // --- RBAC ---
    public $canEdit = false;

    public function mount()
    {
        // Hanya Superadmin & Operator yang berhak generate & edit Hijriah
        $this->canEdit = in_array(Auth::user()->role, ['superadmin', 'operator']);

        $this->tahun_generate = date('Y');
        $this->bulan_generate = (int)date('m');
        $this->bulan_filter = (int)date('m');

        // Set default awal agar view tidak error saat load
        $this->currentDate = Carbon::createFromDate($this->tahun_generate, $this->bulan_generate, 1)->format('Y-m-d');
    }

    // --- FUNGSI EDIT INLINE HIJRIAH ---
    public function editHijriah($id, $currentText)
    {
        if (!$this->canEdit) return; // Proteksi Backend

        $this->editingId = $id;
        $this->hijriahText = $currentText;
    }

    public function saveHijriah()
    {
        if (!$this->canEdit) return; // Proteksi Backend

        $this->validate([
            'hijriahText' => 'required|string|max:255'
        ], [
            'hijriahText.required' => 'Tanggal Hijriah tidak boleh kosong.'
        ]);

        if ($this->editingId) {
            JadwalModel::where('id', $this->editingId)->update([
                'tanggal_hijriah' => $this->hijriahText
            ]);
        }

        $this->editingId = null;
        $this->hijriahText = '';

        session()->flash('hijriah_message', 'Teks Hijriah berhasil diperbarui!');
    }

    public function cancelEdit()
    {
        $this->editingId = null;
        $this->hijriahText = '';
    }
    // -----------------------------------

    public function startGenerate()
    {
        if (!$this->canEdit) return; // Proteksi Backend

        $this->isGenerating = true;
        $this->progress = 0;
        $this->currentDay = 1;
        $this->apiStatusJadwal = 'Menyiapkan...';
        $this->apiStatusHijri = 'Menyiapkan...';

        $date = Carbon::createFromDate($this->tahun_generate, $this->bulan_generate, 1);
        $this->totalDaysInMonth = $date->daysInMonth;

        JadwalModel::whereYear('tanggal', $this->tahun_generate)
                    ->whereMonth('tanggal', $this->bulan_generate)
                    ->delete();

        $this->statusText = "Menghubungkan ke API MyQuran...";
        $this->dispatch('process-next-day');
    }

    public function generateNextDay()
    {
        if (!$this->canEdit) return; // Proteksi Backend

        $setting = AppSetting::first();
        $kotaId = $setting->kota_id ?? 'c7e1249ffc03eb9ded908c236bd1996d'; // Default Pekanbaru

        $dateObj = Carbon::createFromDate($this->tahun_generate, $this->bulan_generate, $this->currentDay);

        $this->currentDate = $dateObj->format('Y-m-d');
        $dateStr = $this->currentDate;

        $this->statusText = "Sinkronisasi: " . $dateObj->translatedFormat('d F Y');
        $this->progress = round(($this->currentDay / $this->totalDaysInMonth) * 100);

        $baseUrlJadwal = rtrim($setting->api_jadwal_sholat, '/') . '/';
        $baseUrlHijri  = rtrim($setting->api_hijriah, '/') . '/';

        $resJadwalData = null;
        $resHijriData = null;

        // FETCH API JADWAL
        try {
            $resJadwal = Http::timeout(10)->get($baseUrlJadwal . $kotaId . '/' . $dateStr);
            if ($resJadwal->successful() && isset($resJadwal->json()['status']) && $resJadwal->json()['status']) {
                $resJadwalData = $resJadwal->json()['data']['jadwal'][$dateStr];
                $this->apiStatusJadwal = 'Sukses ✓';
            } else {
                $this->apiStatusJadwal = 'Gagal ✗';
            }
        } catch (\Exception $e) {
            $this->apiStatusJadwal = 'Error Koneksi ⚠';
        }

        // FETCH API HIJRIAH
        try {
            $resHijri = Http::timeout(10)->get($baseUrlHijri . $dateStr);
            if ($resHijri->successful() && isset($resHijri->json()['status']) && $resHijri->json()['status']) {
                $resHijriData = $resHijri->json()['data']['hijr'];
                $this->apiStatusHijri = 'Sukses ✓';
            } else {
                $this->apiStatusHijri = 'Gagal ✗';
            }
        } catch (\Exception $e) {
            $this->apiStatusHijri = 'Error Koneksi ⚠';
        }

        // Simpan Data jika keduanya ada
        if ($resJadwalData) {
            $hijriText = $resHijriData ? ($resHijriData['today'] ?? $resHijriData['hijr']['today']) : 'Tidak tersedia';

            JadwalModel::create([
                'tanggal' => $dateStr,
                'tanggal_hijriah' => $hijriText,
                'imsak'   => $resJadwalData['imsak'],
                'subuh'   => $resJadwalData['subuh'],
                'terbit'  => $resJadwalData['terbit'],
                'dhuha'   => $resJadwalData['dhuha'],
                'dzuhur'  => $resJadwalData['dzuhur'],
                'ashar'   => $resJadwalData['ashar'],
                'maghrib' => $resJadwalData['maghrib'],
                'isya'    => $resJadwalData['isya'],
            ]);
        }

        if ($this->currentDay < $this->totalDaysInMonth) {
            $this->currentDay++;
            $this->dispatch('process-next-day');
        } else {
            $this->isGenerating = false;
            $this->bulan_filter = $this->bulan_generate;
            session()->flash('message', "Alhamdulillah, Jadwal Bulan " . $dateObj->translatedFormat('F') . " Berhasil Disinkronkan!");
        }
    }

    public function render()
    {
        $jadwal = JadwalModel::whereYear('tanggal', $this->tahun_generate)
            ->when($this->bulan_filter, fn($q) => $q->whereMonth('tanggal', $this->bulan_filter))
            ->orderBy('tanggal', 'asc')
            ->paginate(31);

        return view('livewire.jadwal-sholat', [
            'data_jadwal' => $jadwal,
            'setting' => AppSetting::first()
        ]);
    }
}
