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
use Livewire\WithFileUploads;
use App\Models\Keuangan;
use App\Models\Rekening; // <-- MODEL REKENING DITAMBAHKAN
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.app')]
#[Title('Keuangan Masjid')]
class KeuanganMasjid extends Component
{
    use WithPagination, WithFileUploads;

    // --- FILTER & SEARCH ---
    public $bulan_filter;
    public $tahun_filter;
    public $search = '';

    // Filter Sub Kategori
    public $sub_kategori_filter = '';
    public $sub_kategori_table_filter = '';

    // --- MODAL STATES ---
    public $isModalOpen = false;
    public $isDeleteModalOpen = false;
    public $isEditMode = false;
    public $showImageModal = false;
    public $selectedImageUrl;
    public $selectedId;

    // --- FORM INPUTS (KEUANGAN) ---
    public $tanggal;
    public $kategori = 'pemasukan';
    public $sub_kategori = '';
    public $sumber_atau_tujuan;
    public $nominal;
    public $keterangan;

    // --- FORM INPUTS (REKENING) ---
    public $rekenings;
    public $rek_id, $nama_bank, $nama_akun, $nomor_rekening;
    public $isEditRekening = false;

    // --- FILE UPLOAD ---
    public $bukti;
    public $bukti_path;
    public $originalSize = 0;
    public $compressedSize = 0;
    public $canEdit = false;

    public function mount()
    {
        $this->canEdit = in_array(auth()->user()->role, ['superadmin', 'operator', 'bendahara']);
        $this->bulan_filter = (int)date('m');
        $this->tahun_filter = (int)date('Y');
        $this->tanggal = date('Y-m-d');
    }

    public function render()
    {
        $bulan = (int) $this->bulan_filter;
        $tahun = (int) $this->tahun_filter;

        // Ambil Data Distinct Sub Kategori untuk Dropdown
        $availableSubKategoris = Keuangan::select('sub_kategori')
            ->whereNotNull('sub_kategori')
            ->where('sub_kategori', '!=', '')
            ->distinct()
            ->orderBy('sub_kategori', 'asc')
            ->pluck('sub_kategori');

        // 1. Query Data Tabel Keuangan
        $query = Keuangan::query()
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where(function($q) {
                $q->where('sumber_atau_tujuan', 'like', '%'.$this->search.'%')
                  ->orWhere('keterangan', 'like', '%'.$this->search.'%');
            });

        if (!empty($this->sub_kategori_table_filter)) {
            $query->where('sub_kategori', $this->sub_kategori_table_filter);
        }

        $transaksi = $query->latest('tanggal')->paginate(10);

        // 2. Query Ringkasan Statistik
        $statsQuery = Keuangan::query();
        if (!empty($this->sub_kategori_filter)) {
            $statsQuery->where('sub_kategori', $this->sub_kategori_filter);
        }

        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        $totalPemasukan = (clone $statsQuery)->where('kategori', 'pemasukan')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $totalPengeluaran = (clone $statsQuery)->where('kategori', 'pengeluaran')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        $pemasukanBulanIni = (clone $statsQuery)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaranBulanIni = (clone $statsQuery)->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('kategori', 'pengeluaran')->sum('nominal');

        // 3. Siapkan Data Grafik
        $chartData = $this->prepareChartData($tahun, $bulan, $this->sub_kategori_filter);
        $this->dispatch('update-chart', data: $chartData);

        // 4. Ambil Data Rekening
        $this->rekenings = Rekening::latest()->get();

        return view('livewire.keuangan-masjid', [
            'transaksi' => $transaksi,
            'saldoAkhir' => $saldoAkhir,
            'pemasukanBulanIni' => $pemasukanBulanIni,
            'pengeluaranBulanIni' => $pengeluaranBulanIni,
            'availableSubKategoris' => $availableSubKategoris
        ]);
    }

    public function prepareChartData($tahun, $bulan, $subKategoriFilter = null)
    {
        $incomeQ = Keuangan::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('kategori', 'pemasukan');
        $expenseQ = Keuangan::whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->where('kategori', 'pengeluaran');

        if (!empty($subKategoriFilter)) {
            $incomeQ->where('sub_kategori', $subKategoriFilter);
            $expenseQ->where('sub_kategori', $subKategoriFilter);
        }

        $incomeDaily = $incomeQ->groupBy('tanggal')->selectRaw('DATE(tanggal) as date, sum(nominal) as total')->pluck('total', 'date')->toArray();
        $expenseDaily = $expenseQ->groupBy('tanggal')->selectRaw('DATE(tanggal) as date, sum(nominal) as total')->pluck('total', 'date')->toArray();

        $daysInMonth = Carbon::createFromDate($tahun, $bulan, 1)->daysInMonth;
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateKey = Carbon::createFromDate($tahun, $bulan, $d)->format('Y-m-d');
            $labels[] = $d;
            $incomeData[] = $incomeDaily[$dateKey] ?? 0;
            $expenseData[] = $expenseDaily[$dateKey] ?? 0;
        }

        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData
        ];
    }

    // ==========================================
    // CRUD REKENING LOGIC
    // ==========================================

    public function saveRekening()
    {
       if (!$this->canEdit) return;
        $this->validate([
            'nama_bank' => 'required|string|max:255',
            'nama_akun' => 'required|string|max:255',
            'nomor_rekening' => 'required|string|max:255',
        ]);

        Rekening::updateOrCreate(
            ['id' => $this->rek_id],
            [
                'nama_bank' => $this->nama_bank,
                'nama_akun' => $this->nama_akun,
                'nomor_rekening' => $this->nomor_rekening,
                'is_active' => 1
            ]
        );

        $this->resetRekeningForm();
        session()->flash('rekening_message', 'Data Rekening berhasil disimpan!');
    }

    public function editRekening($id)
    {
        if (!$this->canEdit) return;
        $rek = Rekening::find($id);
        $this->rek_id = $rek->id;
        $this->nama_bank = $rek->nama_bank;
        $this->nama_akun = $rek->nama_akun;
        $this->nomor_rekening = $rek->nomor_rekening;
        $this->isEditRekening = true;
    }

    public function deleteRekening($id)
    {
        if (!$this->canEdit) return;
        Rekening::find($id)->delete();
        session()->flash('rekening_message', 'Data Rekening berhasil dihapus!');
    }

    public function resetRekeningForm()
    {
        if (!$this->canEdit) return;
        $this->reset(['rek_id', 'nama_bank', 'nama_akun', 'nomor_rekening', 'isEditRekening']);
    }

    // ==========================================
    // CRUD KEUANGAN LOGIC
    // ==========================================

    public function showImage($url)
    {
        $this->selectedImageUrl = $url;
        $this->showImageModal = true;
    }

    public function closeImageModal()
    {
        $this->showImageModal = false;
        $this->selectedImageUrl = null;
    }

    public function create()
    {
        if (!$this->canEdit) return;
        $this->resetInput();
        $this->isEditMode = false;
        $this->isModalOpen = true;
    }

    public function store()
    {
        if (!$this->canEdit) return;
        $this->nominal = (int) str_replace('.', '', $this->nominal);

        $this->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|in:pemasukan,pengeluaran',
            'sub_kategori' => 'required|string|max:255',
            'sumber_atau_tujuan' => 'required|string|max:255',
            'nominal' => 'required|numeric|min:1',
            'bukti' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($this->bukti) {
            $path = $this->bukti->store('bukti_keuangan', 'public');
        }

        Keuangan::create([
            'tanggal' => $this->tanggal,
            'kategori' => $this->kategori,
            'sub_kategori' => $this->sub_kategori,
            'sumber_atau_tujuan' => $this->sumber_atau_tujuan,
            'nominal' => $this->nominal,
            'keterangan' => $this->keterangan,
            'bukti_path' => $path,
            'user_id' => Auth::id(),
        ]);

        $this->closeModal();
        session()->flash('message', 'Transaksi berhasil dicatat!');
    }

    public function edit($id)
    {
        if (!$this->canEdit) return;
        $k = Keuangan::find($id);
        $this->selectedId = $id;
        $this->tanggal = $k->tanggal->format('Y-m-d');
        $this->kategori = $k->kategori;
        $this->sub_kategori = $k->sub_kategori;
        $this->sumber_atau_tujuan = $k->sumber_atau_tujuan;
        $this->nominal = (int) $k->nominal;
        $this->keterangan = $k->keterangan;
        $this->bukti_path = $k->bukti_path;

        $this->isEditMode = true;
        $this->isModalOpen = true;

        $this->dispatch('set-tomselect', value: $this->sub_kategori);
    }

    public function update()
    {
       if (!$this->canEdit) return;
        $this->nominal = (int) str_replace('.', '', $this->nominal);

        $this->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required',
            'sub_kategori' => 'required|string|max:255',
            'sumber_atau_tujuan' => 'required',
            'nominal' => 'required|numeric',
        ]);

        $k = Keuangan::find($this->selectedId);

        $path = $k->bukti_path;
        if ($this->bukti) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $this->bukti->store('bukti_keuangan', 'public');
        }

        $k->update([
            'tanggal' => $this->tanggal,
            'kategori' => $this->kategori,
            'sub_kategori' => $this->sub_kategori,
            'sumber_atau_tujuan' => $this->sumber_atau_tujuan,
            'nominal' => $this->nominal,
            'keterangan' => $this->keterangan,
            'bukti_path' => $path,
        ]);

        $this->closeModal();
        session()->flash('message', 'Transaksi berhasil diperbarui!');
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
        $k = Keuangan::find($this->selectedId);
        if ($k->bukti_path && Storage::disk('public')->exists($k->bukti_path)) {
            Storage::disk('public')->delete($k->bukti_path);
        }
        $k->delete();

        $this->isDeleteModalOpen = false;
        session()->flash('message', 'Transaksi dihapus!');
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->isDeleteModalOpen = false;
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->tanggal = date('Y-m-d');
        $this->kategori = 'pemasukan';
        $this->sub_kategori = '';
        $this->sumber_atau_tujuan = '';
        $this->nominal = '';
        $this->keterangan = '';
        $this->bukti = null;
        $this->bukti_path = null;
        $this->originalSize = 0;
        $this->compressedSize = 0;

        $this->dispatch('clear-tomselect');
    }

    public function exportPdf()
    {
        $bulan = (int) $this->bulan_filter;
        $tahun = (int) $this->tahun_filter;

        $query = Keuangan::with('user')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc');

        $statsQuery = Keuangan::query();

        if (!empty($this->sub_kategori_filter)) {
            $query->where('sub_kategori', $this->sub_kategori_filter);
            $statsQuery->where('sub_kategori', $this->sub_kategori_filter);
        }

        $data = $query->get();

        $pemasukan = $data->where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaran = $data->where('kategori', 'pengeluaran')->sum('nominal');

        $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        $totalMasukAll = (clone $statsQuery)->where('kategori', 'pemasukan')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $totalKeluarAll = (clone $statsQuery)->where('kategori', 'pengeluaran')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $saldo = $totalMasukAll - $totalKeluarAll;

        $pdf = Pdf::loadView('pdf.keuangan', [
            'data' => $data,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo' => $saldo,
            'sub_kategori_filter' => $this->sub_kategori_filter
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan-Keuangan-' . $bulan . '-' . $tahun . '.pdf');
    }
}
