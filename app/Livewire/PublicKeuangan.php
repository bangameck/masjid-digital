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
use App\Models\Keuangan;
use App\Models\Rekening;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Barryvdh\DomPDF\Facade\Pdf;

// Pastikan kamu menggunakan layout yang tidak ada sidebar-nya jika belum login
// Atau bisa tetap pakai 'app' asalkan di app.blade.php sidebar-nya di-hide jika !auth()->check()
#[Layout('components.layouts.guest')]
#[Title('Transparansi Keuangan Masjid')]
class PublicKeuangan extends Component
{
    use WithPagination;

    // --- FILTER & SEARCH ---
    public $bulan_filter;
    public $tahun_filter;
    public $search = '';

    public $sub_kategori_filter = '';
    public $sub_kategori_table_filter = '';

    // --- MODAL STATES ---
    public $showImageModal = false;
    public $selectedImageUrl;

    // --- DATA ---
    public $rekenings;

    public function mount()
    {
        $this->bulan_filter = (int)date('m');
        $this->tahun_filter = (int)date('Y');
    }

    public function render()
    {
        $bulan = (int) $this->bulan_filter;
        $tahun = (int) $this->tahun_filter;

        $availableSubKategoris = Keuangan::select('sub_kategori')
            ->whereNotNull('sub_kategori')
            ->where('sub_kategori', '!=', '')
            ->distinct()
            ->orderBy('sub_kategori', 'asc')
            ->pluck('sub_kategori');

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

        $chartData = $this->prepareChartData($tahun, $bulan, $this->sub_kategori_filter);
        $this->dispatch('update-chart', data: $chartData);

        $this->rekenings = Rekening::where('is_active', true)->latest()->get();

        return view('livewire.public-keuangan', [
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
        $labels = []; $incomeData = []; $expenseData = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateKey = Carbon::createFromDate($tahun, $bulan, $d)->format('Y-m-d');
            $labels[] = $d;
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
        $bulan = (int) $this->bulan_filter;
        $tahun = (int) $this->tahun_filter;

        $query = Keuangan::with('user')->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->orderBy('tanggal', 'asc');
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
            'data' => $data, 'bulan' => $bulan, 'tahun' => $tahun,
            'pemasukan' => $pemasukan, 'pengeluaran' => $pengeluaran,
            'saldo' => $saldo, 'sub_kategori_filter' => $this->sub_kategori_filter
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'Laporan-Keuangan-Publik-' . $bulan . '-' . $tahun . '.pdf');
    }
}
