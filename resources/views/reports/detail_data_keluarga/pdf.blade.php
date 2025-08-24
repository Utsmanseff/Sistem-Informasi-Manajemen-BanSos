<!DOCTYPE html>
<html>
<head>
    <title>Laporan Detail Data Keluarga</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            margin: 20mm 20mm 20mm 20mm; /* Margin standar untuk A4 portrait */
            position: relative;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .italic { font-style: italic; }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .kop-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .kop-table .logo-cell {
            width: 15%;
            text-align: left;
        }
        .kop-table .logo {
            width: 60px;
            height: auto;
        }
        .kop-table .text-cell {
            width: 85%;
            text-align: center;
        }
        .kop-table .main-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .kop-table .subtitle {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .kop-table .address {
            font-size: 9px;
            line-height: 1.3;
        }

        .line-separator {
            border: none;
            border-top: 1.5px solid #000;
            margin: 10px 0 15px 0;
        }

        .report-meta {
            text-align: right;
            margin-bottom: 20px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .filters {
            margin-bottom: 15px;
            font-style: italic;
            color: #555;
            border: 1px dashed #ccc;
            padding: 8px;
            background-color: #f9f9f9;
        }
        .filters ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .filters li {
            margin-bottom: 3px;
        }

        .keluarga-card {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
            page-break-inside: avoid; /* Hindari pemotongan di tengah kartu keluarga */
        }
        .keluarga-header {
            font-size: 11px;
            margin-bottom: 5px;
        }
        .keluarga-header span {
            font-weight: bold;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px; /* Margin atas untuk tabel anggota */
            margin-bottom: 10px;
        }
        table.data-table, table.data-table th, table.data-table td {
            border: 1px solid #000;
        }
        table.data-table th, table.data-table td {
            padding: 3px 5px; /* Padding lebih kecil untuk tabel detail */
            text-align: left;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            white-space: nowrap;
            font-size: 9px; /* Ukuran font lebih kecil untuk header tabel detail */
        }
        table.data-table td {
            font-size: 9px; /* Ukuran font lebih kecil untuk konten tabel detail */
        }
        table.data-table td.text-center { text-align: center; }
        table.data-table td.text-right { text-align: right; }

        .signature-block {
            width: 45%;
            float: right;
            text-align: center;
            margin-top: 30px;
        }
        .signature-block p {
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .signature-line {
            margin-top: 50px;
            border-bottom: 1px solid #000;
            display: inline-block;
            padding-bottom: 2px;
            font-weight: bold;
        }
        .signature-details {
            margin-top: 5px;
            font-size: 10px;
        }

        /* CSS untuk page break setelah setiap keluarga, kecuali yang terakhir */
        .keluarga-card {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
            page-break-inside: avoid; /* Ini yang utama: Hindari pemotongan di tengah kartu keluarga */
            /* page-break-after: auto; <-- Ini adalah defaultnya, tidak perlu ditulis */
        }
    </style>
</head>
<body>
    <table class="kop-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('assets/media/icons/Kabupaten Kapuas.png') }}" class="logo">
            </td>
            <td class="text-cell">
                <div class="main-title">PEMERINTAH KABUPATEN KAPUAS</div>
                <div class="subtitle">KECAMATAN BASARANG</div>
                <div class="address">
                    Jl. Trans Kalimantan Km. 8 Email: Kbasarang@gmail.com Kode Pos 73564
                </div>
            </td>
        </tr>
    </table>

    <hr class="line-separator">

    <div class="report-meta">
        Basarang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <h1 class="report-title">LAPORAN DETAIL DATA KELUARGA</h1>

    @if (!empty($filtersApplied))
    <div class="filters">
        <strong>Filter Diterapkan:</strong>
        <ul>
            @foreach ($filtersApplied as $filter)
                <li>{{ $filter }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @forelse($keluargas as $index => $keluarga)
        <div class="keluarga-card">
            <p class="keluarga-header">
                NIK KK: <span>{{ $keluarga->nik_kepala_keluarga }}</span> &nbsp;&nbsp;|&nbsp;&nbsp;
                Nama KK: <span>{{ $keluarga->nama_kepala_keluarga }}</span>
            </p>
            <p style="margin: 0;">Alamat: {{ $keluarga->alamat_lengkap }}, RT {{ $keluarga->rt ?? 'N/A' }}/RW {{ $keluarga->rw ?? 'N/A' }}, Desa {{ $keluarga->desa->nama_desa ?? 'N/A' }}</p>
            <p style="margin: 0;">Status Ekonomi: {{ Str::title(str_replace('_', ' ', $keluarga->status_ekonomi)) }} &nbsp;&nbsp;|&nbsp;&nbsp; Kondisi Rumah: {{ Str::title(str_replace('_', ' ', $keluarga->kondisi_rumah)) }}</p>
            <p style="margin: 0;">Pendapatan/Bulan: Rp. {{ number_format($keluarga->pendapatan_per_bulan, 0, ',', '.') }} &nbsp;&nbsp;|&nbsp;&nbsp; Pekerjaan: {{ $keluarga->pekerjaan ?? 'N/A' }}</p>
            <br>
            <p class="bold" style="font-size: 10px; margin-bottom: 5px;">Daftar Anggota Keluarga:</p>
            @if ($keluarga->anggotaKeluarga->isNotEmpty())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NIK</th>
                            <th>Nama Anggota</th>
                            <th>Tgl. Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Hubungan</th>
                            <th>Pendidikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($keluarga->anggotaKeluarga as $idx_anggota => $anggota)
                        <tr>
                            <td class="text-center">{{ $idx_anggota + 1 }}</td>
                            <td>{{ $anggota->nik }}</td>
                            <td>{{ $anggota->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->translatedFormat('d M Y') }}</td>
                            <td>{{ Str::title($anggota->jenis_kelamin) }}</td>
                            <td>{{ Str::title(str_replace('_', ' ', $anggota->hubungan_dengan_kk)) }}</td>
                            <td>{{ $anggota->tingkat_pendidikan ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p style="font-style: italic; margin-top: 5px;">Tidak ada data anggota keluarga.</p>
            @endif
        </div>
    @empty
        <p class="text-center" style="margin-top: 50px;">Tidak ada data keluarga yang ditemukan berdasarkan filter yang diterapkan.</p>
    @endforelse


    <div class="signature-block">
        <p>Basarang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p class="bold">CAMAT BASARANG,</p>
        <br><br><br>
        <p class="signature-line">EKO DARMA PUTRA, S.STP</p>
        <p class="signature-details">NIP. 19890131 201010 1 001</p>
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $size = 8;
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 20;
            $pdf->page_text($x, $y, $text, $font, $size, array(0,0,0));
        }
    </script>
</body>
</html>