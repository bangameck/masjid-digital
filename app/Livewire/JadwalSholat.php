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

use App\Models\AppSetting;
use App\Models\JadwalSholat as JadwalModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Jadwal Sholat')]
class JadwalSholat extends Component
{
    use WithPagination;

    public $tahun_generate, $bulan_generate, $bulan_filter;
    public $isGenerating     = false;
    public $progress         = 0;
    public $statusText       = '';
    public $currentDay       = 1;
    public $totalDaysInMonth = 0;

    public $currentDate;

    // --- PROPERTY UNTUK EDIT INLINE HIJRIAH ---
    public $editingId   = null;
    public $hijriahText = '';

    // --- PROPERTY UNTUK STATUS API ---
    public $apiStatusJadwal = 'Menunggu...';
    public $apiStatusHijri  = 'Menunggu...';

    // --- RBAC ---
    public $canEdit = false;

    // --- ERROR HANDLING ---
    public $showErrorModal = false;
    public $errorMessage   = '';

    public function mount()
    {
        // Hanya Superadmin & Operator yang berhak generate & edit Hijriah
        $this->canEdit = in_array(Auth::user()->role, ['superadmin', 'operator']);

        $this->tahun_generate = date('Y');
        $this->bulan_generate = (int) date('m');
        $this->bulan_filter   = (int) date('m');

        $this->currentDate = Carbon::createFromDate($this->tahun_generate, $this->bulan_generate, 1)->format('Y-m-d');
    }

    // --- FUNGSI EDIT INLINE HIJRIAH ---
    public function editHijriah($id, $currentText)
    {
        if (! $this->canEdit) return;

        $this->editingId   = $id;
        $this->hijriahText = $currentText;
    }

    public function saveHijriah()
    {
        if (! $this->canEdit) return;

        $this->validate([
            'hijriahText' => 'required|string|max:255',
        ], [
            'hijriahText.required' => 'Tanggal Hijriah tidak boleh kosong.',
        ]);

        if ($this->editingId) {
            JadwalModel::where('id', $this->editingId)->update([
                'tanggal_hijriah' => $this->hijriahText,
            ]);
        }

        $this->editingId   = null;
        $this->hijriahText = '';

        session()->flash('hijriah_message', 'Teks Hijriah berhasil diperbarui!');
    }

    public function cancelEdit()
    {
        $this->editingId   = null;
        $this->hijriahText = '';
    }

    // --- FUNGSI GENERATE JADWAL ---
    public function startGenerate()
    {
        if (! $this->canEdit) return;

        // 1. Tampilkan modal generating ke user
        $this->isGenerating    = true;
        $this->showErrorModal  = false;
        $this->progress        = 0;
        $this->currentDay      = 1;
        $this->apiStatusJadwal = 'Mengecek Koneksi...';
        $this->apiStatusHijri  = 'Menunggu...';
        $this->statusText      = "Memeriksa ketersediaan API...";

        $setting = AppSetting::first();
        $kotaId  = $setting->kota_id ?? 'c7e1249ffc03eb9ded908c236bd1996d'; // Default Pekanbaru

        // --- VALIDASI URL KOSONG / NULL ---
        if (empty($setting->api_jadwal_sholat)) {
            $this->triggerError("URL API Jadwal Sholat masih KOSONG. Silakan isi terlebih dahulu di menu Pengaturan.");
            return;
        }

        // --- VALIDASI FORMAT URL (Harus berupa link valid http/https) ---
        if (!filter_var($setting->api_jadwal_sholat, FILTER_VALIDATE_URL)) {
            $this->triggerError("Format URL API tidak valid. Pastikan link diawali dengan http:// atau https:// di menu Pengaturan.");
            return;
        }

        $dateObj                = Carbon::createFromDate($this->tahun_generate, $this->bulan_generate, 1);
        $this->totalDaysInMonth = $dateObj->daysInMonth;

        // Format tanggal hari pertama untuk dites (Pre-flight check)
        $dateStr       = $dateObj->format('Y-m-d');
        $baseUrlJadwal = rtrim($setting->api_jadwal_sholat, '/') . '/';

        // 2. PRE-FLIGHT CHECK (KETUK PINTU API)
        try {
            $testUrl  = $baseUrlJadwal . $kotaId . '/' . $dateStr;
            $response = Http::timeout(5)->get($testUrl);

            if ($response->successful() && isset($response->json()['status']) && $response->json()['status']) {
                // STATUS 200 OK! Pintu Terbuka, Gas Hapus Data Lama & Mulai Looping
                JadwalModel::whereYear('tanggal', $this->tahun_generate)
                    ->whereMonth('tanggal', $this->bulan_generate)
                    ->delete();

                $this->statusText = "Koneksi stabil. Memulai sinkronisasi...";
                $this->dispatch('process-next-day');

            } elseif ($response->status() === 404) {
                $this->triggerError("Endpoint API tidak ditemukan (Error 404). Silakan periksa URL di Pengaturan: " . $baseUrlJadwal);
            } elseif ($response->serverError()) {
                $this->triggerError("Server API MyQuran sedang bermasalah atau Down (Error 5xx). Silakan coba beberapa saat lagi.");
            } else {
                $this->triggerError("Gagal terhubung ke API. Status Code: " . $response->status());
            }

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            // Kita ubah pesan errornya biar lebih komprehensif
            $this->triggerError("Gagal terhubung ke server. Pastikan koneksi internet PC aktif, ATAU periksa kembali kebenaran link API (domain mungkin tidak valid) di menu Pengaturan.");
        } catch (\Exception $e) {
            $this->triggerError("Terjadi kesalahan sistem: " . $e->getMessage());
        }
    }

    private function triggerError($msg)
    {
        // Matikan animasi loading, tampilkan modal error
        $this->isGenerating   = false;
        $this->errorMessage   = $msg;
        $this->showErrorModal = true;
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage   = '';
    }

    public function generateNextDay()
    {
        if (! $this->canEdit) return;

        $setting = AppSetting::first();
        $kotaId  = $setting->kota_id ?? 'c7e1249ffc03eb9ded908c236bd1996d'; // Default Pekanbaru

        $dateObj = Carbon::createFromDate($this->tahun_generate, $this->bulan_generate, $this->currentDay);

        $this->currentDate = $dateObj->format('Y-m-d');
        $dateStr           = $this->currentDate;

        $this->statusText = "Sinkronisasi: " . $dateObj->translatedFormat('d F Y');
        $this->progress   = round(($this->currentDay / $this->totalDaysInMonth) * 100);

        $baseUrlJadwal = rtrim($setting->api_jadwal_sholat, '/') . '/';
        $baseUrlHijri  = rtrim($setting->api_hijriah, '/') . '/';

        $resJadwalData = null;
        $resHijriData  = null;

        // FETCH API JADWAL PER HARI
        try {
            $resJadwal = Http::timeout(10)->get($baseUrlJadwal . $kotaId . '/' . $dateStr);
            if ($resJadwal->successful() && isset($resJadwal->json()['status']) && $resJadwal->json()['status']) {
                $resJadwalData         = $resJadwal->json()['data']['jadwal'][$dateStr] ?? $resJadwal->json()['data']['jadwal'];
                $this->apiStatusJadwal = 'Sukses ✓';
            } else {
                $this->apiStatusJadwal = 'Gagal ✗';
            }
        } catch (\Exception $e) {
            $this->apiStatusJadwal = 'Error Koneksi ⚠';
        }

        // FETCH API HIJRIAH PER HARI
        try {
            $resHijri = Http::timeout(10)->get($baseUrlHijri . $dateStr);
            if ($resHijri->successful() && isset($resHijri->json()['status']) && $resHijri->json()['status']) {
                $resHijriData         = $resHijri->json()['data']['hijr'] ?? $resHijri->json()['data'];
                $this->apiStatusHijri = 'Sukses ✓';
            } else {
                $this->apiStatusHijri = 'Gagal ✗';
            }
        } catch (\Exception $e) {
            $this->apiStatusHijri = 'Error Koneksi ⚠';
        }

        // SIMPAN KE DATABASE
        if ($resJadwalData) {
            $hijriText = $resHijriData ? ($resHijriData['today'] ?? ($resHijriData['hijr']['today'] ?? 'Tidak tersedia')) : 'Tidak tersedia';

            JadwalModel::create([
                'tanggal'         => $dateStr,
                'tanggal_hijriah' => $hijriText,
                'imsak'           => $resJadwalData['imsak'],
                'subuh'           => $resJadwalData['subuh'],
                'terbit'          => $resJadwalData['terbit'] ?? '-',
                'dhuha'           => $resJadwalData['dhuha'] ?? '-',
                'dzuhur'          => $resJadwalData['dzuhur'],
                'ashar'           => $resJadwalData['ashar'],
                'maghrib'         => $resJadwalData['maghrib'],
                'isya'            => $resJadwalData['isya'],
            ]);
        }

        // LANJUT KE HARI BERIKUTNYA ATAU SELESAI
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
            'setting'     => AppSetting::first(),
        ]);
    }
}
