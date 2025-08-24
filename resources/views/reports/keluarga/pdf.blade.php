<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Keluarga</title> {{-- Judul di browser/PDF viewer --}}
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
            border: none; /* Hapus border pada sel tabel kop surat */
            padding: 0;
            vertical-align: middle;
        }
        .kop-table .logo-cell {
            width: 15%; /* Lebar kolom logo */
            text-align: left;
        }
        .kop-table .logo {
            width: 60px; /* Lebar logo */
            height: auto;
        }
        .kop-table .text-cell {
            width: 85%; /* Lebar kolom teks */
            text-align: center;
        }
        .kop-table .main-title {
            font-size: 18px; /* Lebih besar dari sebelumnya untuk "PEMERINTAH KABUPATEN KAPUAS" */
            font-weight: bold;
            margin-bottom: 2px;
        }
        .kop-table .subtitle {
            font-size: 14px; /* Lebih besar dari sebelumnya untuk "KECAMATAN BASARANG" */
            font-weight: bold;
            margin-bottom: 2px;
        }
        .kop-table .address {
            font-size: 9px;
            line-height: 1.3;
        }

        .line-separator {
            border: none;
            border-top: 1.5px solid #000; /* Garis hitam tebal */
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
            text-transform: uppercase; /* Agar konsisten dengan contoh surat */
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
        table.data-table { /* Menggunakan nama kelas yang berbeda agar tidak konflik dengan kop-table */
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
        /* Specific column widths for better layout */
        table.data-table th:nth-child(1), table.data-table td:nth-child(1) { width: 3%; } /* No */
        table.data-table th:nth-child(2), table.data-table td:nth-child(2) { width: 10%; } /* NIK KK */
        table.data-table th:nth-child(3), table.data-table td:nth-child(3) { width: 15%; } /* Nama KK */
        table.data-table th:nth-child(4), table.data-table td:nth-child(4) { width: 18%; } /* Alamat */
        table.data-table th:nth-child(5), table.data-table td:nth-child(5) { width: 5%; } /* RT/RW */
        table.data-table th:nth-child(6), table.data-table td:nth-child(6) { width: 8%; } /* Desa */
        table.data-table th:nth-child(7), table.data-table td:nth-child(7) { width: 8%; } /* No Telp */
        table.data-table th:nth-child(8), table.data-table td:nth-child(8) { width: 8%; } /* Status Ekonomi */
        table.data-table th:nth-child(9), table.data-table td:nth-child(9) { width: 8%; } /* Kondisi Rumah */
        table.data-table th:nth-child(10), table.data-table td:nth-child(10) { width: 8%; } /* Pendapatan */
        table.data-table th:nth-child(11), table.data-table td:nth-child(11) { width: 8%; } /* Pekerjaan */
        table.data-table th:nth-child(12), table.data-table td:nth-child(12) { width: 5%; } /* Disabilitas */
        table.data-table th:nth-child(13), table.data-table td:nth-child(13) { width: 5%; } /* Lansia */
        table.data-table th:nth-child(14), table.data-table td:nth-child(14) { width: 8%; } /* Tgl Terdaftar */

        /* Signature Block */
        .signature-block {
            width: 45%; /* Sesuaikan lebar */
            float: right; /* Posisikan ke kanan */
            text-align: center;
            margin-top: 30px;
        }
        .signature-block p {
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }
        .signature-line {
            margin-top: 50px; /* Cukup spasi untuk tanda tangan */
            border-bottom: 1px solid #000;
            display: inline-block; /* Agar border hanya di bawah teks */
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
                {{-- Pastikan jalur ini benar dan file gambar ada di public/assets/media/icons/ --}}
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

    <h1 class="report-title">LAPORAN DATA KELUARGA</h1>

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

    <table class="data-table"> {{-- Menggunakan kelas data-table --}}
        <thead>
            <tr>
                <th>No.</th>
                <th>NIK KK</th>
                <th>Nama KK</th>
                <th>Alamat</th>
                <th>RT/RW</th>
                <th>Desa</th>
                <th>No. Telp</th>
                <th>Status Ekonomi</th>
                <th>Kondisi Rumah</th>
                <th>Pendapatan</th>
                <th>Pekerjaan</th>
                <th>Disabilitas</th>
                <th>Lansia</th>
                <th>Tgl Terdaftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($keluargas as $index => $keluarga)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $keluarga->nik_kepala_keluarga }}</td>
                <td>{{ $keluarga->nama_kepala_keluarga }}</td>
                <td>{{ $keluarga->alamat_lengkap }}</td>
                <td class="text-center">{{ $keluarga->rt ?? '-' }}/{{ $keluarga->rw ?? '-' }}</td>
                <td>{{ $keluarga->desa->nama_desa ?? '-' }}</td>
                <td>{{ $keluarga->nomor_telepon ?? '-' }}</td>
                <td>{{ Str::title(str_replace('_', ' ', $keluarga->status_ekonomi)) }}</td>
                <td>{{ Str::title(str_replace('_', ' ', $keluarga->kondisi_rumah)) }}</td>
                <td class="text-right">Rp {{ number_format($keluarga->pendapatan_per_bulan ?? 0, 0, ',', '.') }}</td>
                <td>{{ $keluarga->pekerjaan ?? '-' }}</td>
                <td class="text-center">{{ $keluarga->is_disabilitas_berat ? 'Ya' : 'Tidak' }}</td>
                <td class="text-center">{{ $keluarga->is_lansia ? 'Ya' : 'Tidak' }}</td>
                <td>{{ $keluarga->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="14" class="text-center">Tidak ada data keluarga yang ditemukan untuk laporan ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-block">
        <p>Basarang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <p class="bold">CAMAT BASARANG,</p>
        <br><br><br> {{-- Cukup spasi untuk tanda tangan --}}
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
