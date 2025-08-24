@extends('layouts.main') 

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Detail Keluarga</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Data Penerima</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('keluargas.index') }}" class="text-muted text-hover-primary">Daftar Keluarga</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Detail</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('keluargas.edit', $keluarga->id) }}" class="btn btn-sm fw-bold btn-warning">
                    <i class="fa-solid fa-pencil-alt fs-2"></i> Edit Data Keluarga
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-sm fw-bold btn-light">
                    <i class="fa-solid fa-arrow-left fs-2"></i> Kembali
                </a>
            </div>
            </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card mb-5 mb-xl-10">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Informasi Detail Keluarga: {{ $keluarga->nama_kepala_keluarga }}</h2>
                    </div>
                    </div>
                <div class="card-body py-4">
                    <div class="row">
                        <div class="col-lg-6">
                            <h3 class="mb-5 text-primary">Data Kepala Keluarga</h3>
                            <div class="table-responsive">
                                <table class="table table-row-dashed gs-0 gy-4">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold text-muted min-w-125px">NIK Kepala Keluarga</td>
                                            <td class="text-gray-800">{{ $keluarga->nik_kepala_keluarga }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Nama Kepala Keluarga</td>
                                            <td class="text-gray-800">{{ $keluarga->nama_kepala_keluarga }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Nomor Telepon</td>
                                            <td class="text-gray-800">{{ $keluarga->nomor_telepon ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Pekerjaan</td>
                                            <td class="text-gray-800">{{ $keluarga->pekerjaan ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Pendapatan per Bulan</td>
                                            <td class="text-gray-800">Rp {{ number_format($keluarga->pendapatan_per_bulan, 2, ',', '.') ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Status Ekonomi</td>
                                            <td class="text-gray-800">
                                                @php
                                                    $badgeClass = '';
                                                    switch ($keluarga->status_ekonomi) {
                                                        case 'sangat_miskin': $badgeClass = 'badge-light-danger'; break;
                                                        case 'miskin': $badgeClass = 'badge-light-warning'; break;
                                                        case 'rentan': $badgeClass = 'badge-light-primary'; break;
                                                        case 'menengah': $badgeClass = 'badge-light-success'; break;
                                                        default: $badgeClass = 'badge-light'; break;
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $keluarga->status_ekonomi)) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Kondisi Rumah</td>
                                            <td class="text-gray-800">
                                                @php
                                                    $badgeClass = '';
                                                    switch ($keluarga->kondisi_rumah) {
                                                        case 'sangat_buruk': $badgeClass = 'badge-light-danger'; break;
                                                        case 'buruk': $badgeClass = 'badge-light-warning'; break;
                                                        case 'sedang': $badgeClass = 'badge-light-primary'; break;
                                                        case 'baik': $badgeClass = 'badge-light-success'; break;
                                                        default: $badgeClass = 'badge-light'; break;
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst(str_replace('_', ' ', $keluarga->kondisi_rumah)) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Desa</td>
                                            <td class="text-gray-800">{{ $keluarga->desa->nama_desa ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Alamat Lengkap</td>
                                            <td class="text-gray-800">{{ $keluarga->alamat_lengkap }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">RT / RW</td>
                                            <td class="text-gray-800">{{ $keluarga->rt ?? '-' }} / {{ $keluarga->rw ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Disabilitas Berat</td>
                                            <td class="text-gray-800">
                                                <span class="badge badge-light-{{ $keluarga->is_disabilitas_berat ? 'danger' : 'success' }}">
                                                    {{ $keluarga->is_disabilitas_berat ? 'Ya' : 'Tidak' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Lansia</td>
                                            <td class="text-gray-800">
                                                <span class="badge badge-light-{{ $keluarga->is_lansia ? 'danger' : 'success' }}">
                                                    {{ $keluarga->is_lansia ? 'Ya' : 'Tidak' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Slip Gaji</td>
                                            <td class="text-gray-800">
                                                @if($keluarga->jalur_slip_gaji)
                                                    <a href="{{ Storage::url($keluarga->jalur_slip_gaji) }}" target="_blank" class="btn btn-sm btn-light-primary">
                                                        <i class="fa-solid fa-file-alt me-2"></i> Lihat File
                                                    </a>
                                                @else
                                                    Tidak ada
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Foto KK</td>
                                            <td class="text-gray-800">
                                                @if($keluarga->jalur_foto_kk)
                                                    <a href="{{ Storage::url($keluarga->jalur_foto_kk) }}" target="_blank" class="btn btn-sm btn-light-primary">
                                                        <i class="fa-solid fa-id-card me-2"></i> Lihat File
                                                    </a>
                                                @else
                                                    Tidak ada
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Catatan</td>
                                            <td class="text-gray-800">{{ $keluarga->catatan ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <h3 class="mb-5 text-primary">Data Survei Terakhir</h3>
                            @if($keluarga->surveis)
                            <div class="table-responsive">
                                <table class="table table-row-dashed gs-0 gy-4">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold text-muted min-w-125px">Tanggal Survei</td>
                                            <td class="text-gray-800">{{ \Carbon\Carbon::parse($keluarga->surveis->tanggal_survei)->translatedFormat('d F Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Petugas Survei</td>
                                            <td class="text-gray-800">{{ $keluarga->surveis->petugasSurvei->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Lokasi Survei</td>
                                            <td class="text-gray-800">
                                                @if($keluarga->surveis->latitude && $keluarga->surveis->longitude)
                                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $keluarga->surveis->latitude }},{{ $keluarga->surveis->longitude }}" target="_blank" class="btn btn-sm btn-light-info">
                                                        <i class="fa-solid fa-map-marker-alt me-2"></i> Lihat di Peta
                                                    </a>
                                                    <br>
                                                    <small class="text-muted">{{ $keluarga->surveis->latitude }}, {{ $keluarga->surveis->longitude }}</small>
                                                @else
                                                    Tidak ada data lokasi
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Foto Lokasi Survei</td>
                                            <td class="text-gray-800">
                                                @if($keluarga->surveis->jalur_foto)
                                                    <a href="{{ Storage::url($keluarga->surveis->jalur_foto) }}" target="_blank" class="btn btn-sm btn-light-primary">
                                                        <i class="fa-solid fa-camera me-2"></i> Lihat Foto
                                                    </a>
                                                @else
                                                    Tidak ada
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Status Verifikasi</td>
                                            <td class="text-gray-800">
                                                @php
                                                    $badgeClass = '';
                                                    switch ($keluarga->surveis->status_verifikasi) {
                                                        case 'pending': $badgeClass = 'badge-light-warning'; break;
                                                        case 'terverifikasi': $badgeClass = 'badge-light-success'; break;
                                                        case 'ditolak': $badgeClass = 'badge-light-danger'; break;
                                                        default: $badgeClass = 'badge-light'; break;
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ ucfirst($keluarga->surveis->status_verifikasi) }}</span>
                                            </td>
                                        </tr>
                                        @if($keluarga->surveis->status_verifikasi == 'ditolak')
                                        <tr>
                                            <td class="fw-bold text-muted">Alasan Penolakan</td>
                                            <td class="text-gray-800">{{ $keluarga->surveis->alasan_penolakan ?? '-' }}</td>
                                        </tr>
                                        @endif
                                        @if($keluarga->surveis->diverifikasi_oleh)
                                        <tr>
                                            <td class="fw-bold text-muted">Diverifikasi Oleh</td>
                                            <td class="text-gray-800">{{ $keluarga->surveis->diverifikasiOleh->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold text-muted">Tanggal Verifikasi</td>
                                            <td class="text-gray-800">{{ \Carbon\Carbon::parse($keluarga->surveis->tanggal_verifikasi)->translatedFormat('d F Y H:i') }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <p class="text-muted">Tidak ada data survei yang terkait dengan keluarga ini.</p>
                            @endif
                        </div>
                    </div>

                    <div class="separator separator-dashed my-10"></div>

                    <h3 class="mb-5 text-primary">Anggota Keluarga</h3>
                    <div class="d-flex justify-content-start mb-5">
                        <a href="{{ route('keluargas.anggota-keluargas.create', $keluarga->id) }}" class="btn btn-sm fw-bold btn-primary">
                            <i class="fa-solid fa-plus-circle me-2"></i> Tambah Anggota Keluarga
                        </a>
                    </div>

                    @if($keluarga->anggotaKeluarga->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px">NIK</th>
                                        <th class="min-w-150px">Nama</th>
                                        <th class="min-w-100px">Tgl. Lahir</th>
                                        <th class="min-w-80px">Jenis Kelamin</th>
                                        <th class="min-w-120px">Hubungan dengan KK</th>
                                        <th class="min-w-100px">Pendidikan</th>
                                        <th class="min-w-100px">Disabilitas Berat</th>
                                        <th class="min-w-80px">Lansia</th>
                                        <th class="text-end min-w-100px">Aksi</th> {{-- Sekarang sudah tidak dikomentari --}}
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    @foreach ($keluarga->anggotaKeluarga as $anggota)
                                        <tr>
                                            <td>{{ $anggota->nik }}</td>
                                            <td>{{ $anggota->nama }}</td>
                                            <td>{{ \Carbon\Carbon::parse($anggota->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                                            <td>{{ ucfirst($anggota->jenis_kelamin) }}</td>
                                            <td>{{ ucfirst(str_replace('_', ' ', $anggota->hubungan_dengan_kk)) }}</td>
                                            <td>{{ $anggota->tingkat_pendidikan ?? '-' }}</td>
                                            <td>
                                                <span class="badge badge-light-{{ $anggota->is_disabilitas_berat ? 'danger' : 'success' }}">
                                                    {{ $anggota->is_disabilitas_berat ? 'Ya' : 'Tidak' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-{{ $anggota->is_lansia ? 'danger' : 'success' }}">
                                                    {{ $anggota->is_lansia ? 'Ya' : 'Tidak' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                {{-- Tombol Edit Anggota --}}
                                                <a href="{{ route('keluargas.anggota-keluargas.edit', ['keluarga' => $keluarga->id, 'anggota_keluarga' => $anggota->id]) }}" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1" title="Edit Anggota">
                                                    <i class="fa-solid fa-pencil-alt fs-2"></i>
                                                </a>
                                                {{-- Tombol Hapus Anggota (dengan SweetAlert2) --}}
                                                <button type="button" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm confirm-delete-anggota" data-anggota-id="{{ $anggota->id }}" data-anggota-nama="{{ $anggota->nama }}" data-bs-toggle="tooltip" title="Hapus Anggota">
                                                    <i class="fa-solid fa-trash-alt fs-2"></i>
                                                </button>
                                                <form id="delete-anggota-form-{{ $anggota->id }}" action="{{ route('keluargas.anggota-keluargas.destroy', ['keluarga' => $keluarga->id, 'anggota_keluarga' => $anggota->id]) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Tidak ada anggota keluarga yang terdaftar untuk keluarga ini.</p>
                    @endif
                </div>
                </div>
            </div>
    </div>
    </div>
@endsection
