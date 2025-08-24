@extends('layouts.main')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Detail Penyaluran Bantuan</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Manajemen Bantuan</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('penyaluran-bantuan.index') }}" class="text-muted text-hover-primary">Penyaluran Bantuan</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Detail</li>
                </ul>
            </div>
            </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Informasi Penyaluran Bantuan</h2>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('penyaluran-bantuan.edit', $penyaluranBantuan->id) }}" class="btn btn-sm btn-primary me-2">
                            <i class="fa-solid fa-pencil"></i> Edit
                        </a>
                        <form action="{{ route('penyaluran-bantuan.destroy', $penyaluranBantuan->id) }}" method="POST" class="d-inline delete-form-penyaluran">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="mb-5">
                        <label class="form-label fw-bold">ID Penyaluran:</label>
                        <span class="form-control form-control-solid">{{ $penyaluranBantuan->id }}</span>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Penerima Bantuan:</label>
                        <span class="form-control form-control-solid">
                            {{ $penyaluranBantuan->penerimaBantuan->keluarga->nik_kepala_keluarga ?? 'N/A' }} -
                            {{ $penyaluranBantuan->penerimaBantuan->keluarga->nama_kepala_keluarga ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Jenis Bantuan:</label>
                        <span class="form-control form-control-solid">{{ $penyaluranBantuan->penerimaBantuan->jenisBantuan->nama_bantuan ?? 'N/A' }}</span>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Periode Bantuan:</label>
                        <span class="form-control form-control-solid">{{ $penyaluranBantuan->penerimaBantuan->periode_bantuan->translatedFormat('F Y') }}</span>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Nominal Dihitung:</label>
                        <span class="form-control form-control-solid">{{ 'Rp ' . number_format($penyaluranBantuan->penerimaBantuan->nominal_dihitung, 2, ',', '.') }}</span>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Tanggal Penyaluran:</label>
                        <span class="form-control form-control-solid">
                            {{ $penyaluranBantuan->tanggal_penyaluran ? $penyaluranBantuan->tanggal_penyaluran->translatedFormat('d F Y H:i') . ' WITA' : 'Belum Disalurkan' }}
                        </span>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Status Setelah Penyaluran:</label>
                        <span class="form-control form-control-solid">
                            @if($penyaluranBantuan->status_setelah_penyaluran == 'berhasil_disalurkan')
                                <span class="badge badge-light-success">Berhasil Disalurkan</span>
                            @elseif($penyaluranBantuan->status_setelah_penyaluran == 'tidak_ditemukan')
                                <span class="badge badge-light-danger">Tidak Ditemukan</span>
                            @elseif($penyaluranBantuan->status_setelah_penyaluran == 'menolak')
                                <span class="badge badge-light-warning">Menolak</span>
                            @else
                                <span class="badge badge-light-secondary">Belum Tercatat</span>
                            @endif
                        </span>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Catatan:</label>
                        <span class="form-control form-control-solid">
                            {{ $penyaluranBantuan->catatan ?: '-' }}
                        </span>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Dibuat Pada:</label>
                        <span class="form-control form-control-solid">{{ $penyaluranBantuan->created_at->translatedFormat('d F Y H:i') }} WITA</span>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Diperbarui Pada:</label>
                        <span class="form-control form-control-solid">{{ $penyaluranBantuan->updated_at->translatedFormat('d F Y H:i') }} WITA</span>
                    </div>

                    <div class="mb-5">
                        <label class="form-label fw-bold">Foto Bukti Penyaluran:</label>
                        @if ($penyaluranBantuan->jalur_foto_penerima)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $penyaluranBantuan->jalur_foto_penerima) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $penyaluranBantuan->jalur_foto_penerima) }}" alt="Foto Bukti Penyaluran" class="img-thumbnail" style="max-width: 400px; height: auto;">
                                </a>
                            </div>
                        @else
                            <span class="form-control form-control-solid">Tidak ada foto bukti penyaluran.</span>
                        @endif
                    </div>

                    <div class="text-center pt-8">
                        <a href="{{ route('penyaluran-bantuan.index') }}" class="btn btn-light me-3">Kembali ke Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection