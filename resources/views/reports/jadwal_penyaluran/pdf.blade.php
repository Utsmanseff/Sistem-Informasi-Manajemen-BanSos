<!DOCTYPE html>
<html>
<head>
    <title>Laporan Jadwal Penyaluran</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            margin: 20mm 20mm 20mm 20mm;
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

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data-table, table.data-table th, table.data-table td {
            border: 1px solid #000;
        }
        table.data-table th, table.data-table td {
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            white-space: nowrap;
        }
        table.data-table td.text-center { text-align: center; }
        table.data-table td.text-right { text-align: right; }
        
        /* Mengurangi lebar kolom tertentu jika terlalu lebar */
        table.data-table th:nth-child(1), /* No. */
        table.data-table td:nth-child(1) { width: 3%; }
        table.data-table th:nth-child(2), /* Periode Bantuan */
        table.data-table td:nth-child(2) { width: 10%; }
        table.data-table th:nth-child(3), /* Jenis Bantuan */
        table.data-table td:nth-child(3) { width: 12%; }
        table.data-table th:nth-child(4), /* NIK KK */
        table.data-table td:nth-child(4) { width: 12%; }
        table.data-table th:nth-child(6), /* Desa */
        table.data-table td:nth-child(6) { width: 10%; }
        table.data-table th:nth-child(7), /* Nominal */
        table.data-table td:nth-child(7) { width: 10%; }
        table.data-table th:nth-child(8), /* Status Verifikasi */
        table.data-table td:nth-child(8) { width: 12%; }
        table.data-table th:nth-child(9), /* Petugas */
        table.data-table td:nth-child(9) { width: 12%; }

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

    <h1 class="report-title">LAPORAN JADWAL PENYALURAN</h1>

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

    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center">No.</th>
                <th class="text-center">Periode Bantuan</th>
                <th class="text-center">Jenis Bantuan</th>
                <th class="text-center">NIK KK</th>
                <th class="text-center">Nama Kepala Keluarga</th>
                <th class="text-center">Desa</th>
                <th class="text-center">Petugas Ditugaskan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jadwalPenyalurans as $index => $jadwal)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($jadwal->periode_bantuan)->translatedFormat('F Y') }}</td>
                <td>{{ $jadwal->jenisBantuan->nama_bantuan ?? '-' }}</td>
                <td>{{ $jadwal->keluarga->nik_kepala_keluarga ?? '-' }}</td>
                <td>{{ $jadwal->keluarga->nama_kepala_keluarga ?? '-' }}</td>
                <td>{{ $jadwal->keluarga->desa->nama_desa ?? '-' }}</td>
                <td>{{ $jadwal->ditugaskanKepadaDistributor->name ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada jadwal penyaluran yang ditemukan untuk laporan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

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
            $x = ($pdf->get_width() - $width) - 20;
            $y = $pdf->get_height() - 20;
            $pdf->page_text($x, $y, $text, $font, $size, array(0,0,0));
        }
    </script>
</body>
</html>