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
use App\Models\AppSetting;
use App\Models\Keuangan;
use App\Models\Rekening;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.guest')]
#[Title('Transparansi Keuangan Masjid')]
class PublicKeuangan extends Component
{
    use WithPagination;

    // --- FILTER & SEARCH ---
    public $filter_mode = 'bulan'; // 'bulan' atau 'rentang'
    public $bulan_filter;
    public $tahun_filter;
    public $start_date;
    public $end_date;
    public $search = '';

    public $sub_kategori_filter = '';
    public $sub_kategori_table_filter = '';

    // --- MODAL STATES ---
    public $showImageModal = false;
    public $selectedImageUrl;

    // --- DATA ---
    public $rekenings;

    public $sortColumn = 'tanggal';
    public $sortDirection = 'desc';

    public function mount()
    {
        $this->bulan_filter = (int)date('m');
        $this->tahun_filter = (int)date('Y');
        $this->start_date = now()->subDays(30)->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
    }

    public function updatedStartDate()
    {
        $this->validateDateRange();
    }

    public function updatedEndDate()
    {
        $this->validateDateRange();
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
            // End Date yang otomatis menyesuaikan Start Date (maksimal +31 hari)
            $this->end_date = $start->copy()->addDays(31)->format('Y-m-d');
        }
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

    public function render()
    {
        $setting = AppSetting::first();
        $bulan = (int) $this->bulan_filter;
        $tahun = (int) $this->tahun_filter;

        $availableSubKategoris = Keuangan::select('sub_kategori')
            ->whereNotNull('sub_kategori')
            ->where('sub_kategori', '!=', '')
            ->distinct()
            ->orderBy('sub_kategori', 'asc')
            ->pluck('sub_kategori');

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

        // Saldo Keseluruhan (All Time)
        $pemasukanAllTime = (clone $statsSaldoQuery)->where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaranAllTime = (clone $statsSaldoQuery)->where('kategori', 'pengeluaran')->sum('nominal');
        $saldoAllTime = $pemasukanAllTime - $pengeluaranAllTime;

        // Saldo Berjalan (Hingga Akhir Periode)
        $totalPemasukanBerjalan = (clone $statsSaldoQuery)->where('kategori', 'pemasukan')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $totalPengeluaranBerjalan = (clone $statsSaldoQuery)->where('kategori', 'pengeluaran')->whereDate('tanggal', '<=', $endDate)->sum('nominal');
        $saldoAkhir = $totalPemasukanBerjalan - $totalPengeluaranBerjalan;

        // Pemasukan & Pengeluaran pada Periode yang Difilter
        $pemasukanPeriodeIni = (clone $statsQuery)->where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaranPeriodeIni = (clone $statsQuery)->where('kategori', 'pengeluaran')->sum('nominal');

        $chartData = $this->prepareChartData($tahun, $bulan, $this->sub_kategori_filter);
        $this->dispatch('update-chart', data: $chartData);

        $this->rekenings = Rekening::where('is_active', true)->latest()->get();

        return view('livewire.public-keuangan', [
            'setting' => $setting,
            'transaksi' => $transaksi,
            'saldoAllTime' => $saldoAllTime,
            'saldoAkhir' => $saldoAkhir,
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
            // Rentang waktu: Fix paksa kalender X-Axis jadi 31 Hari (Tanggal Awal + 30 Hari)
            $periodStart = Carbon::parse($this->start_date);
            $periodEnd = $periodStart->copy()->addDays(30); // 31 hari inklusif

            $incomeQ->whereBetween('tanggal', [$periodStart->format('Y-m-d'), $periodEnd->format('Y-m-d')]);
            $expenseQ->whereBetween('tanggal', [$periodStart->format('Y-m-d'), $periodEnd->format('Y-m-d')]);
        } else {
            // Per Bulan: Dinamis mengikuti jumlah hari di bulan tersebut (28, 29, 30, atau 31)
            $periodStart = Carbon::createFromDate($tahun, $bulan, 1);
            $periodEnd = $periodStart->copy()->endOfMonth();

            $incomeQ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            $expenseQ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
        }

        if (!empty($subKategoriFilter)) {
            $incomeQ->where('sub_kategori', $subKategoriFilter);
            $expenseQ->where('sub_kategori', $subKategoriFilter);
        }

        // Eksekusi Query dan Grouping per Hari
        $incomeDaily = $incomeQ->groupBy('tanggal')->selectRaw('DATE(tanggal) as date, sum(nominal) as total')->pluck('total', 'date')->toArray();
        $expenseDaily = $expenseQ->groupBy('tanggal')->selectRaw('DATE(tanggal) as date, sum(nominal) as total')->pluck('total', 'date')->toArray();

        $labels = [];
        $incomeData = [];
        $expenseData = [];

        // Looping untuk menyusun data titik-titik (dots) ke dalam Chart
        for ($date = $periodStart->copy(); $date->lte($periodEnd); $date->addDay()) {
            $dateKey = $date->format('Y-m-d');

            // Format Label X-Axis: 'd M' (01 Feb) untuk rentang, 'd' (01) untuk bulan
            $labels[] = ($this->filter_mode == 'rentang') ? $date->format('d M') : $date->format('d');

            $incomeData[] = $incomeDaily[$dateKey] ?? 0;
            $expenseData[] = $expenseDaily[$dateKey] ?? 0;
        }

        return ['labels' => $labels, 'income' => $incomeData, 'expense' => $expenseData];
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

        $fileName = 'Laporan-Keuangan-Publik-' . ($this->filter_mode == 'rentang' ? $this->start_date.'-sampai-'.$this->end_date : $this->bulan_filter.'-'.$this->tahun_filter) . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }
}
