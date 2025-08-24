<!DOCTYPE html>
<html>
<head>
    <title>Laporan Hasil Survei</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            margin: 20mm 20mm 20mm 20mm; /* Top, Right, Bottom, Left */
            position: relative; /* For footer */
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .italic { font-style: italic; }

        /* Kop Surat (Menggunakan Tabel untuk Presisi) */
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
            width: 60px; /* Lebar logo */
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

        /* Report Title & Date */
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

        /* Filter Section */
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

        /* Table Styles (for report data) */
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
            white-space: nowrap; /* Mencegah header pecah baris */
        }
        /* Specific column widths for better layout */
        table.data-table th:nth-child(1), table.data-table td:nth-child(1) { width: 3%; } /* No */
        table.data-table th:nth-child(2), table.data-table td:nth-child(2) { width: 5%; } /* ID Survei */
        table.data-table th:nth-child(3), table.data-table td:nth-child(3) { width: 10%; } /* NIK KK */
        table.data-table th:nth-child(4), table.data-table td:nth-child(4) { width: 14%; } /* Nama KK */
        table.data-table th:nth-child(5), table.data-table td:nth-child(5) { width: 8%; } /* Desa */
        table.data-table th:nth-child(6), table.data-table td:nth-child(6) { width: 16%; } /* Alamat Lengkap */
        table.data-table th:nth-child(7), table.data-table td:nth-child(7) { width: 9%; } /* Tgl Survei */
        table.data-table th:nth-child(8), table.data-table td:nth-child(8) { width: 10%; } /* Petugas Survei */
        table.data-table th:nth-child(9), table.data-table td:nth-child(9) { width: 8%; } /* Status Verifikasi */
        table.data-table th:nth-child(10), table.data-table td:nth-child(10) { width: 9%; } /* Diverifikasi Oleh */
        table.data-table th:nth-child(11), table.data-table td:nth-child(11) { width: 9%; } /* Tgl Verifikasi */
        table.data-table th:nth-child(12), table.data-table td:nth-child(12) { width: 10%; } /* Alasan Penolakan */


        /* Signature Block */
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
    {{-- Kop Surat Menggunakan Tabel --}}
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

    <h1 class="report-title">LAPORAN HASIL SURVEI</h1>

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
                <th>No.</th>
                <th>ID Survei</th>
                <th>NIK KK</th>
                <th>Nama Kepala Keluarga</th>
                <th>Desa</th>
                <th>Alamat Lengkap</th>
                <th>Tanggal Survei</th>
                <th>Petugas Survei</th>
                <th>Status Verifikasi</th>
                <th>Diverifikasi Oleh</th>
                <th>Tanggal Verifikasi</th>
                <th>Alasan Penolakan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($surveis as $index => $survei)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $survei->id }}</td>
                <td>{{ $survei->keluarga->nik_kepala_keluarga ?? '-' }}</td>
                <td>{{ $survei->keluarga->nama_kepala_keluarga ?? '-' }}</td>
                <td>{{ $survei->keluarga->desa->nama_desa ?? '-' }}</td>
                <td>{{ $survei->keluarga->alamat_lengkap ?? '-' }}</td>
                <td>{{ $survei->tanggal_survei->format('d M Y H:i') }}</td>
                <td>{{ $survei->petugasSurvei->name ?? '-' }}</td>
                <td>{{ Str::title(str_replace('_', ' ', $survei->status_verifikasi)) }}</td>
                <td>{{ $survei->diverifikasiOleh->name ?? '-' }}</td>
                <td>{{ $survei->tanggal_verifikasi ? $survei->tanggal_verifikasi->format('d M Y H:i') : '-' }}</td>
                <td>{{ $survei->alasan_penolakan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center">Tidak ada data survei yang ditemukan untuk laporan ini.</td>
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

    {{-- DomPDF Page Numbering --}}
    <script type="text/php">
        if (isset($pdf)) {
            $text = "Halaman {PAGE_NUM} dari {PAGE_COUNT}";
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $size = 8;
            $width = $fontMetrics->get_text_width($text, $font, $size);
            $x = ($pdf->get_width() - $width) - 20; // 20mm dari kanan margin
            $y = $pdf->get_height() - 20; // 20mm dari bawah margin
            $pdf->page_text($x, $y, $text, $font, $size, array(0,0,0));
        }
    </script>
</body>
</html>