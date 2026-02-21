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
use App\Models\Rekening;
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
    public $filter_mode = 'bulan'; // 'bulan' atau 'rentang'
    public $bulan_filter;
    public $tahun_filter;
    public $start_date;
    public $end_date;
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

    public $sortColumn = 'tanggal';
    public $sortDirection = 'desc';
    public $canEdit = false;

    public function mount()
    {
        $this->canEdit = in_array(auth()->user()->role, ['superadmin', 'operator', 'bendahara']);
        $this->bulan_filter = (int)date('m');
        $this->tahun_filter = (int)date('Y');
        $this->start_date = now()->subDays(30)->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
        $this->tanggal = date('Y-m-d');
    }

    public function updatedStartDate()
    {
        $this->validateDateRange();
    }

    public function updatedEndDate()
    {
        $this->validateDateRange();
    }

    public function sortBy($columnName)
    {
        if ($this->sortColumn === $columnName) {
            // Jika kolom yang sama diklik, balik arah urutannya (ASC/DESC)
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Jika kolom beda diklik, default urutkan dari atas (ASC)
            $this->sortDirection = 'asc';
            $this->sortColumn = $columnName;
        }
    }

    private function validateDateRange()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);

        if ($end->isBefore($start)) {
            $this->end_date = $start->format('Y-m-d');
            $end = $start->copy();
        }

        if ($start->diffInDays($end) > 31) {
            // Jika lebih dari 31 hari, sesuaikan start date nya otomatis
            $this->start_date = $end->copy()->subDays(31)->format('Y-m-d');
        }
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

        // 1. Query Data Tabel Keuangan & Statistik
        $query = Keuangan::query()->where(function($q) {
            $q->where('sumber_atau_tujuan', 'like', '%'.$this->search.'%')
              ->orWhere('keterangan', 'like', '%'.$this->search.'%');
        });

        $statsQuery = Keuangan::query();

        if ($this->filter_mode == 'rentang') {
            $query->whereBetween('tanggal', [$this->start_date, $this->end_date]);
            $statsQuery->whereBetween('tanggal', [$this->start_date, $this->end_date]);
            $endDate = Carbon::parse($this->end_date)->endOfDay();
        } else {
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $statsQuery->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        }

        if (!empty($this->sub_kategori_table_filter)) {
            $query->where('sub_kategori', $this->sub_kategori_table_filter);
        }

        $transaksi = $query->orderBy($this->sortColumn, $this->sortDirection)->paginate(10);

        // Ringkasan Statistik
        $statsSaldoQuery = Keuangan::query();
        if (!empty($this->sub_kategori_filter)) {
            $statsQuery->where('sub_kategori', $this->sub_kategori_filter);
            $statsSaldoQuery->where('sub_kategori', $this->sub_kategori_filter);
        }

        $totalPemasukanAll = (clone $statsSaldoQuery)->where('kategori', 'pemasukan')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $totalPengeluaranAll = (clone $statsSaldoQuery)->where('kategori', 'pengeluaran')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $saldoAkhir = $totalPemasukanAll - $totalPengeluaranAll;

        $pemasukanAllTime = Keuangan::where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaranAllTime = Keuangan::where('kategori', 'pengeluaran')->sum('nominal');
        $saldoAllTime = $pemasukanAllTime - $pengeluaranAllTime;

        $pemasukanPeriodeIni = (clone $statsQuery)->where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaranPeriodeIni = (clone $statsQuery)->where('kategori', 'pengeluaran')->sum('nominal');

        // 3. Siapkan Data Grafik
        $chartData = $this->prepareChartData($tahun, $bulan, $this->sub_kategori_filter);
        $this->dispatch('update-chart', data: $chartData);

        // 4. Ambil Data Rekening
        $this->rekenings = Rekening::latest()->get();

        return view('livewire.keuangan-masjid', [
            'transaksi' => $transaksi,
            'saldoAkhir' => $saldoAkhir,
            'saldoAllTime' => $saldoAllTime,
            'pemasukanPeriodeIni' => $pemasukanPeriodeIni,
            'pengeluaranPeriodeIni' => $pengeluaranPeriodeIni,
            'availableSubKategoris' => $availableSubKategoris
        ]);
    }

    public function prepareChartData($tahun, $bulan, $subKategoriFilter = null)
    {
        $incomeQ = Keuangan::where('kategori', 'pemasukan');
        $expenseQ = Keuangan::where('kategori', 'pengeluaran');

        if ($this->filter_mode == 'rentang') {
            $periodStart = Carbon::parse($this->start_date);
            $periodEnd = Carbon::parse($this->end_date);
            $incomeQ->whereBetween('tanggal', [$this->start_date, $this->end_date]);
            $expenseQ->whereBetween('tanggal', [$this->start_date, $this->end_date]);
        } else {
            $periodStart = Carbon::createFromDate($tahun, $bulan, 1);
            $periodEnd = $periodStart->copy()->endOfMonth();
            $incomeQ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $expenseQ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        if (!empty($subKategoriFilter)) {
            $incomeQ->where('sub_kategori', $subKategoriFilter);
            $expenseQ->where('sub_kategori', $subKategoriFilter);
        }

        $incomeDaily = $incomeQ->groupBy('tanggal')->selectRaw('DATE(tanggal) as date, sum(nominal) as total')->pluck('total', 'date')->toArray();
        $expenseDaily = $expenseQ->groupBy('tanggal')->selectRaw('DATE(tanggal) as date, sum(nominal) as total')->pluck('total', 'date')->toArray();

        $labels = [];
        $incomeData = [];
        $expenseData = [];

        for ($date = $periodStart->copy(); $date->lte($periodEnd); $date->addDay()) {
            $dateKey = $date->format('Y-m-d');
            $labels[] = ($this->filter_mode == 'rentang') ? $date->format('d M') : $date->format('d');
            $incomeData[] = $incomeDaily[$dateKey] ?? 0;
            $expenseData[] = $expenseDaily[$dateKey] ?? 0;
        }

        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData
        ];
    }

    // ... [BAGIAN CRUD REKENING & TRANSAKSI SAMA PERSIS DENGAN SEBELUMNYA] ...

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
        $query = Keuangan::with('user')->orderBy('tanggal', 'asc');
        $statsQuery = Keuangan::query();

        if ($this->filter_mode == 'rentang') {
            $query->whereBetween('tanggal', [$this->start_date, $this->end_date]);
            $endDate = Carbon::parse($this->end_date)->endOfDay();
        } else {
            $bulan = (int) $this->bulan_filter;
            $tahun = (int) $this->tahun_filter;
            $query->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $endDate = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
        }

        if (!empty($this->sub_kategori_filter)) {
            $query->where('sub_kategori', $this->sub_kategori_filter);
            $statsQuery->where('sub_kategori', $this->sub_kategori_filter);
        }

        $data = $query->get();

        $pemasukan = $data->where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaran = $data->where('kategori', 'pengeluaran')->sum('nominal');

        $totalMasukAll = (clone $statsQuery)->where('kategori', 'pemasukan')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $totalKeluarAll = (clone $statsQuery)->where('kategori', 'pengeluaran')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $saldo = $totalMasukAll - $totalKeluarAll;

        $pdf = Pdf::loadView('pdf.keuangan', [
            'data' => $data,
            'filter_mode' => $this->filter_mode,
            'bulan' => $this->bulan_filter,
            'tahun' => $this->tahun_filter,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo' => $saldo,
            'sub_kategori_filter' => $this->sub_kategori_filter
        ]);

        $fileName = 'Laporan-Keuangan-' . ($this->filter_mode == 'rentang' ? $this->start_date.'-sampai-'.$this->end_date : $this->bulan_filter.'-'.$this->tahun_filter) . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }
}
