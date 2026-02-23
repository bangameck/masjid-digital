<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Masjid</title>

    <style>
        /* FONT SETUP */
        @font-face {
            font-family: 'Work Sans';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('assets/fonts/WorkSans-Regular.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'Work Sans';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('assets/fonts/WorkSans-Bold.ttf') }}") format('truetype');
        }

        body {
            font-family: 'Work Sans', sans-serif;
            color: #1e293b;
            font-size: 12px;
            line-height: 1.5;
        }

        /* KOP SURAT FORMAL */
        table.kop-surat {
            width: 100%;
            border-bottom: 3px double #0f172a;
            margin-bottom: 15px; /* Jarak KOP ke Judul Laporan */
            padding-bottom: 10px;
        }
        table.kop-surat td { vertical-align: middle; }
        .logo-cell { width: 15%; text-align: center; }
        .text-cell { width: 70%; text-align: center; }
        .empty-cell { width: 15%; }

        .logo-circle {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            border: 3px solid #10b981;
            padding: 2px;
            background-color: #ffffff;
            object-fit: contain;
        }

        /* Mengurangi jarak (nempel) antara Nama Masjid dan Alamat */
        .text-cell h1 { margin: 0; padding: 0; text-transform: uppercase; color: #0f172a; font-size: 22px; font-weight: bold; letter-spacing: 1.5px; line-height: 1; }
        .text-cell p { margin: 4px 0 0 0; padding: 0; font-size: 11px; color: #475569; line-height: 1.2; }

        /* JUDUL LAPORAN DI LUAR KOP */
        .report-title-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title-section h2 {
            margin: 0;
            font-size: 14px;
            text-decoration: underline;
            text-transform: uppercase;
            color: #0f172a;
            font-weight: bold;
        }
        .report-title-section p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #475569;
            font-weight: bold;
        }

        /* SUMMARY CARDS */
        table.summary-table { width: 100%; margin-bottom: 20px; border-collapse: separate; border-spacing: 5px 0; }
        .summary-box {
            padding: 12px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            text-align: center;
            border-radius: 8px; /* Agak rounded */
        }
        .box-in { border-top: 4px solid #10b981; }
        .box-out { border-top: 4px solid #f43f5e; }
        .box-saldo { border-top: 4px solid #3b82f6; }
        .box-all { border-top: 4px solid #0f172a; background-color: #f8fafc; }

        .summary-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: bold; margin-bottom: 6px; }
        .summary-val { font-size: 16px; font-weight: bold; color: #0f172a; }

        /* MAIN DATA TABLE (Kotak Rounded di DomPDF) */
        table.data {
            width: 100%;
            border-collapse: separate; /* Wajib separate agar border-radius jalan */
            border-spacing: 0;
            border: 1px solid #94a3b8;
            border-radius: 8px; /* Tabel agak rounded */
            font-size: 11px;
            margin-bottom: 20px;
        }
        table.data th, table.data td {
            padding: 8px;
            vertical-align: middle;
            border-bottom: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
        }
        /* Menghapus border kanan pada kolom terakhir agar tidak double */
        table.data th:last-child, table.data td:last-child { border-right: none; }
        /* Menghapus border bawah pada baris terakhir */
        table.data tr:last-child td { border-bottom: none; }

        table.data th {
            background-color: #e2e8f0;
            text-align: center;
            text-transform: uppercase;
            color: #1e293b;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* Mengatur radius sudut sel ujung agar warna background tidak bocor */
        table.data th:first-child { border-top-left-radius: 7px; }
        table.data th:last-child { border-top-right-radius: 7px; }
        table.data tr:last-child td:first-child { border-bottom-left-radius: 7px; }
        table.data tr:last-child td:last-child { border-bottom-right-radius: 7px; }

        table.data tbody tr:nth-child(even) td { background-color: #f8fafc; }

        .text-emerald { color: #059669; font-weight: bold; }
        .text-rose { color: #e11d48; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; font-size: 12px; }
        .kategori-label { font-size: 9px; text-transform: uppercase; font-weight: bold; padding: 2px 5px;}

        /* FOOTER & TTD */
        .footer { margin-top: 30px; font-size: 11px; page-break-inside: avoid; }
        table.ttd-table { width: 100%; text-align: center; }
        table.ttd-table td { width: 50%; vertical-align: top; padding: 10px; }
        .ttd-name { margin-top: 60px; font-weight: bold; text-decoration: underline; color: #0f172a; text-transform: uppercase; }
        .ttd-jabatan { font-weight: bold; color: #475569; }

        .auto-print { margin-top: 30px; padding-top: 10px; border-top: 1px dashed #cbd5e1; text-align: center; font-size: 9px; color: #64748b; font-style: italic; }
    </style>
</head>
<body>
    @php
        $setting = \App\Models\AppSetting::first();

        // 1. CONVERT LOGO KE BASE64
        $logoBase64 = null;
        if ($setting && $setting->logo_path) {
            $path = storage_path('app/public/' . $setting->logo_path);
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $imgData = file_get_contents($path);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($imgData);
            }
        }

        // 2. FILTER DATA TRANSAKSI
        $query = \App\Models\Keuangan::query();

        if (isset($filter_mode) && $filter_mode == 'rentang') {
            $query->whereBetween('tanggal', [$start_date, $end_date]);
            $periodeText = \Carbon\Carbon::parse($start_date)->translatedFormat('d F Y') . ' s/d ' . \Carbon\Carbon::parse($end_date)->translatedFormat('d F Y');
        } else {
            $query->whereYear('tanggal', $tahun ?? date('Y'))
                  ->whereMonth('tanggal', $bulan ?? date('m'));
            $bulanNama = \Carbon\Carbon::create()->month($bulan ?? date('m'))->translatedFormat('F');
            $periodeText = strtoupper($bulanNama) . ' ' . ($tahun ?? date('Y'));
        }

        $filterText = empty($sub_kategori_filter) ? 'Semua Kategori' : strtoupper($sub_kategori_filter);

        if (!empty($sub_kategori_filter)) {
            $query->where('sub_kategori', $sub_kategori_filter);
        }

        $data = $query->orderBy('tanggal', 'asc')->get();

        $pemasukan = $data->where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaran = $data->where('kategori', 'pengeluaran')->sum('nominal');
        $saldo = $pemasukan - $pengeluaran;

        // 3. TOTAL ALL TIME
        $allTimeQuery = \App\Models\Keuangan::query();
        if (!empty($sub_kategori_filter)) {
            $allTimeQuery->where('sub_kategori', $sub_kategori_filter);
        }
        $pemasukanAllTime = (clone $allTimeQuery)->where('kategori', 'pemasukan')->sum('nominal');
        $pengeluaranAllTime = (clone $allTimeQuery)->where('kategori', 'pengeluaran')->sum('nominal');
        $saldoAllTime = $pemasukanAllTime - $pengeluaranAllTime;

        // 4. DATA FOOTER & ZONA WAKTU
        $ketua = \App\Models\Pengurus::where('jabatan', 'Ketua')->first();
        $bendahara = \App\Models\Pengurus::where('jabatan', 'Bendahara')->first();
        $kota = $setting->kota_nama ?? 'Pekanbaru';

        $tz = $setting->zona_waktu ?? 'Asia/Jakarta';
        $tzCode = 'WIB';
        if ($tz == 'Asia/Makassar') $tzCode = 'WITA';
        elseif ($tz == 'Asia/Jayapura') $tzCode = 'WIT';

        $printTime = \Carbon\Carbon::now($tz)->translatedFormat('d F Y H:i:s');
        $appUrl = env('APP_URL', request()->getSchemeAndHttpHost());
    @endphp

    <table class="kop-surat">
        <tr>
            <td class="logo-cell">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" class="logo-circle" alt="Logo">
                @endif
            </td>
            <td class="text-cell">
                <h1>{{ $setting->nama_masjid ?? 'MASJID DIGITAL' }}</h1>
                <p>{{ $setting->alamat ?? 'Alamat Masjid Belum Diatur di Pengaturan' }}</p>
            </td>
            <td class="empty-cell"></td>
        </tr>
    </table>

    <div class="report-title-section">
        <h2>Laporan Arus Kas Keuangan</h2>
        <p>Periode: {{ $periodeText }} | Filter: {{ $filterText }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td class="summary-box box-in" style="width: 25%;">
                <div class="summary-label">Total Pemasukan</div>
                <div class="summary-val text-emerald">Rp {{ number_format($pemasukan, 0, ',', '.') }}</div>
            </td>
            <td class="summary-box box-out" style="width: 25%;">
                <div class="summary-label">Total Pengeluaran</div>
                <div class="summary-val text-rose">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</div>
            </td>
            <td class="summary-box box-saldo" style="width: 25%;">
                <div class="summary-label">Saldo Periode Ini</div>
                <div class="summary-val">Rp {{ number_format($saldo, 0, ',', '.') }}</div>
            </td>
            <td class="summary-box box-all" style="width: 25%;">
                <div class="summary-label">Total Saldo Kas</div>
                <div class="summary-val">Rp {{ number_format($saldoAllTime, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 15%">Tanggal</th>
                <th style="width: 35%">Uraian Transaksi</th>
                <th style="width: 15%">Sub Kategori</th>
                <th style="width: 18%">Jumlah (Rp)</th>
                <th style="width: 12%">Petugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    <b>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</b><br>
                    <span style="color: #64748b; font-size: 9px;">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</span>
                </td>
                <td>
                    <b>{{ $item->sumber_atau_tujuan }}</b><br>
                    <span style="color: #475569; font-size: 10px;">{{ $item->keterangan ?? '-' }}</span>
                </td>
                <td class="text-center">
                    <span class="kategori-label {{ $item->kategori == 'pemasukan' ? 'text-emerald' : 'text-rose' }}">
                        {{ $item->sub_kategori }}
                    </span>
                </td>
                <td class="text-right font-work {{ $item->kategori == 'pemasukan' ? 'text-emerald' : 'text-rose' }}">
                    {{ $item->kategori == 'pemasukan' ? '+' : '-' }} {{ number_format($item->nominal, 0, ',', '.') }}
                </td>
                <td class="text-center" style="font-size: 10px; color: #475569;">
                    {{ $item->user->name ?? 'System' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #64748b; font-weight: bold; letter-spacing: 1px;">
                    -- TIDAK ADA DATA TRANSAKSI PADA PERIODE INI --
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p style="text-align: right; color: #1e293b; margin-bottom: 5px;">{{ ucfirst($kota) }}, {{ \Carbon\Carbon::now($tz)->translatedFormat('d F Y') }}</p>

        <table class="ttd-table">
            <tr>
                <td>
                    <div class="ttd-jabatan">Mengetahui,<br>Ketua Pengurus</div>
                    <div class="ttd-name">
                        {{ $ketua ? $ketua->nama : '_______________________' }}
                    </div>
                </td>
                <td>
                    <div class="ttd-jabatan">Dibuat oleh,<br>Bendahara</div>
                    <div class="ttd-name">
                        {{ $bendahara ? $bendahara->nama : '_______________________' }}
                    </div>
                </td>
            </tr>
        </table>

        <div class="auto-print">
            Dicetak secara otomatis melalui <strong>{{ $appUrl }}</strong> pada {{ $printTime }} {{ $tzCode }}
        </div>
    </div>
</body>
</html>
