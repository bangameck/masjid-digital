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
use Carbon\Carbon;
use App\Models\PetugasJumat;
use App\Models\PetugasRamadhan;
use App\Models\PetugasIed;
use App\Models\PengajianRutin;
use App\Models\JadwalSholat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.app')]
#[Title('Jadwal Petugas & Ceramah')]
class ScheduleManager extends Component
{
    use WithPagination;

    // --- NAVIGATION STATE ---
    public $activeTab = 'jumat';
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isEditMode = false;
    public $selectedId;

    // --- FORM PROPERTIES ---
    public $tanggal;
    public $khatib, $penceramah, $imam, $muadzin, $bilal, $judul_ceramah;
    public $malam_ke, $ied_type = 'Idul Fitri';

    // --- RAMADHAN LOGIC PROPERTIES ---
    public $ramadhanYears = [];
    public $selectedRamadhanYear;
    public $ramadhanDates = []; // List tanggal hasil filter hijriah
    public $canEdit = false;

    public function mount()
    {
        $this->canEdit = in_array(Auth::user()->role, ['superadmin', 'operator', 'humas']);
        $this->tanggal = date('Y-m-d');

        // Ambil list tahun dari data jadwal sholat untuk dropdown tahun Ramadhan
        $this->ramadhanYears = JadwalSholat::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Set default year jika ada data
        if (count($this->ramadhanYears) > 0) {
            $this->selectedRamadhanYear = $this->ramadhanYears[0];
        }
    }

    /**
     * Hook: Terpicu otomatis saat tab dipindah
     */
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->resetInput();

        // Jika pindah ke Ramadhan, muat opsi tanggalnya
        if ($tab == 'ramadhan' && $this->selectedRamadhanYear) {
            $this->loadRamadhanDates($this->selectedRamadhanYear);
        }
    }

    /**
     * Hook: Validasi Backend jika user mencoba input manual tanggal non-Jumat
     */
    public function updatedTanggal($value)
    {
        if ($this->activeTab == 'jumat' && $value) {
            $date = Carbon::parse($value);
            if (!$date->isFriday()) {
                $this->tanggal = null;
                $this->dispatch('alert-error', message: 'Input ditolak! Pilih hari Jumat.');
            }
        }
    }

    /**
     * Hook: Saat tahun di dropdown Ramadhan diganti
     */
    public function updatedSelectedRamadhanYear($year)
    {
        $this->loadRamadhanDates($year);
        $this->tanggal = null; // Reset pilihan tanggal agar user memilih ulang
    }

    /**
     * Filter data dari tabel jadwal_sholat berdasarkan kata 'Ramadan'
     */
    private function loadRamadhanDates($year)
    {
        $this->ramadhanDates = JadwalSholat::whereYear('tanggal', $year)
            ->where('tanggal_hijriah', 'LIKE', '%Ramadan%')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'value' => $item->tanggal->format('Y-m-d'),
                    'label' => Carbon::parse($item->tanggal)->translatedFormat('d F Y') . ' (' . $item->tanggal_hijriah . ')'
                ];
            })->toArray();
    }

    public function render()
    {
        // Query data berdasarkan tab aktif
        $query = match ($this->activeTab) {
            'jumat'    => PetugasJumat::orderBy('tanggal', 'desc'),
            'ramadhan' => PetugasRamadhan::orderBy('tanggal', 'desc'),
            'ied'      => PetugasIed::orderBy('tanggal', 'desc'),
            'rutin'    => PengajianRutin::orderBy('tanggal', 'desc'),
        };

        return view('livewire.schedule-manager', [
            'data' => $query->paginate(10)
        ]);
    }

    // --- CRUD ACTIONS ---

    public function create()
    {
        if (!$this->canEdit) return;
        $this->resetInput();
        $this->isEditMode = false;

        // Inisialisasi data Ramadhan jika sedang di tab Ramadhan
        if ($this->activeTab == 'ramadhan' && $this->selectedRamadhanYear) {
            $this->loadRamadhanDates($this->selectedRamadhanYear);
        }

        $this->isModalOpen = true;
    }

    public function store()
    {
        if (!$this->canEdit) return;
        $this->validateData();

        if ($this->activeTab == 'jumat') {
            PetugasJumat::create($this->commonData() + ['khatib' => $this->khatib]);
        } elseif ($this->activeTab == 'ramadhan') {
            PetugasRamadhan::create($this->commonData() + [
                'penceramah' => $this->penceramah,
                'malam_ke' => $this->malam_ke
            ]);
        } elseif ($this->activeTab == 'ied') {
            PetugasIed::create($this->commonData() + [
                'khatib' => $this->khatib,
                'ied' => $this->ied_type
            ]);
        } elseif ($this->activeTab == 'rutin') {
             PengajianRutin::create([
                'tanggal' => $this->tanggal,
                'penceramah' => $this->penceramah,
                'judul_ceramah' => $this->judul_ceramah
            ]);
        }

        $this->closeModal();
        session()->flash('message', 'Data jadwal berhasil disimpan!');
    }

    public function edit($id)
    {
        if (!$this->canEdit) return;
        $this->selectedId = $id;
        $this->isEditMode = true;

        // Temukan data berdasarkan Tab
        $model = match ($this->activeTab) {
            'jumat'    => PetugasJumat::find($id),
            'ramadhan' => PetugasRamadhan::find($id),
            'ied'      => PetugasIed::find($id),
            'rutin'    => PengajianRutin::find($id),
        };

        // Mapping Field Umum
        $this->tanggal = $model->tanggal instanceof \DateTime ? $model->tanggal->format('Y-m-d') : $model->tanggal;
        $this->judul_ceramah = $model->judul_ceramah;

        // Mapping Field Khusus
        if ($this->activeTab == 'jumat' || $this->activeTab == 'ied') {
            $this->khatib = $model->khatib;
        } else {
            $this->penceramah = $model->penceramah;
        }

        if ($this->activeTab == 'ramadhan') {
            $this->malam_ke = $model->malam_ke;
            $this->selectedRamadhanYear = Carbon::parse($this->tanggal)->year;
            $this->loadRamadhanDates($this->selectedRamadhanYear);
        }

        if ($this->activeTab == 'ied') {
            $this->ied_type = $model->ied;
        }

        if ($this->activeTab != 'rutin') {
            $this->imam = $model->imam;
            $this->muadzin = $model->muadzin;
            $this->bilal = $model->bilal;
        }

        $this->isModalOpen = true;
    }

    public function update()
    {
        if (!$this->canEdit) return;
        $this->validateData();

        $data = match ($this->activeTab) {
            'jumat'    => PetugasJumat::find($this->selectedId),
            'ramadhan' => PetugasRamadhan::find($this->selectedId),
            'ied'      => PetugasIed::find($this->selectedId),
            'rutin'    => PengajianRutin::find($this->selectedId),
        };

        if ($this->activeTab == 'jumat') {
            $data->update($this->commonData() + ['khatib' => $this->khatib]);
        } elseif ($this->activeTab == 'ramadhan') {
            $data->update($this->commonData() + ['penceramah' => $this->penceramah, 'malam_ke' => $this->malam_ke]);
        } elseif ($this->activeTab == 'ied') {
            $data->update($this->commonData() + ['khatib' => $this->khatib, 'ied' => $this->ied_type]);
        } elseif ($this->activeTab == 'rutin') {
            $data->update(['tanggal' => $this->tanggal, 'penceramah' => $this->penceramah, 'judul_ceramah' => $this->judul_ceramah]);
        }

        $this->closeModal();
        session()->flash('message', 'Jadwal berhasil diperbarui!');
    }

    public function deleteId($id)
    {
        if (!$this->canEdit) return;
        $this->selectedId = $id;
        $this->isDeleteModalOpen = true;
    }

    public function delete()
    {
        if (!$this->canEdit) return;
        match ($this->activeTab) {
            'jumat'    => PetugasJumat::find($this->selectedId)->delete(),
            'ramadhan' => PetugasRamadhan::find($this->selectedId)->delete(),
            'ied'      => PetugasIed::find($this->selectedId)->delete(),
            'rutin'    => PengajianRutin::find($this->selectedId)->delete(),
        };

        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Data berhasil dihapus dari sistem!');
    }

    // --- HELPERS ---

    private function validateData()
    {
        $rules = ['tanggal' => 'required|date'];

        if ($this->activeTab == 'jumat' || $this->activeTab == 'ied') {
            $rules['khatib'] = 'required|min:3';
            $rules['imam'] = 'required';
        } else {
            $rules['penceramah'] = 'required|min:3';
        }

        if ($this->activeTab == 'ramadhan') {
            $rules['malam_ke'] = 'required|integer|min:1|max:30';
            $rules['imam'] = 'required';
        }

        $this->validate($rules);
    }

    private function commonData()
    {
        return [
            'tanggal' => $this->tanggal,
            'imam' => $this->imam,
            'muadzin' => $this->muadzin,
            'bilal' => $this->bilal,
            'judul_ceramah' => $this->judul_ceramah,
        ];
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->tanggal = date('Y-m-d');
        $this->khatib = '';
        $this->penceramah = '';
        $this->imam = '';
        $this->muadzin = '';
        $this->bilal = '';
        $this->judul_ceramah = '';
        $this->malam_ke = '';
        $this->ied_type = 'Idul Fitri';
    }
}
