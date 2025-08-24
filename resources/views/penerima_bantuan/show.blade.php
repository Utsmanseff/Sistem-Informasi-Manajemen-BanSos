@extends('layouts.main')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Detail Penerima Bantuan</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Manajemen Bantuan</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('penerima-bantuan.index') }}" class="text-muted text-hover-primary">Penerima Bantuan</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Detail</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('penerima-bantuan.edit', $penerimaBantuan->id) }}" class="btn btn-sm fw-bold btn-primary">
                    <i class="fa-solid fa-edit"></i> Edit Penerima Bantuan
                </a>
                <a href="{{ route('penerima-bantuan.index') }}" class="btn btn-sm fw-bold btn-light-primary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
            </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Informasi Detail Penerima Bantuan</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Nama Kepala Keluarga:</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $penerimaBantuan->keluarga->nama_kepala_keluarga ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">NIK Kepala Keluarga:</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $penerimaBantuan->keluarga->nik_kepala_keluarga ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">No. Kartu Keluarga:</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $penerimaBantuan->keluarga->no_kk ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-5"></div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Jenis Bantuan:</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $penerimaBantuan->jenisBantuan->nama_bantuan ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Periode Bantuan:</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $penerimaBantuan->periode_bantuan->translatedFormat('F Y') }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Nominal Dihitung:</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ 'Rp ' . number_format($penerimaBantuan->nominal_dihitung, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Status Penerima:</label>
                        <div class="col-lg-8">
                            @php
                                $badgeClass = '';
                                switch($penerimaBantuan->status_penerima) {
                                    case 'belum_tersalurkan':
                                        $badgeClass = 'badge-light-warning';
                                        break;
                                    case 'tersalurkan':
                                        $badgeClass = 'badge-light-success';
                                        break;
                                    default:
                                        $badgeClass = 'badge-light-info';
                                        break;
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }} fs-6">{{ str_replace('_', ' ', Str::title($penerimaBantuan->status_penerima)) }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Catatan:</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $penerimaBantuan->catatan ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Dibuat Pada:</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $penerimaBantuan->created_at->translatedFormat('d F Y H:i:s') }}</span>
                        </div>
                    </div>
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Terakhir Diperbarui:</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">{{ $penerimaBantuan->updated_at->translatedFormat('d F Y H:i:s') }}</span>
                        </div>
                    </div>

                    @if($penerimaBantuan->penyaluran)
                        <div class="separator separator-dashed my-10"></div>
                        <h3 class="mb-5">Detail Penyaluran Bantuan</h3>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Tanggal Penyaluran:</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $penerimaBantuan->penyaluran->tanggal_penyaluran->translatedFormat('d F Y') ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Nominal Disalurkan:</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ 'Rp ' . number_format($penerimaBantuan->penyaluran->nominal_disalurkan, 2, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Keterangan Penyaluran:</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $penerimaBantuan->penyaluran->keterangan ?? '-' }}</span>
                            </div>
                        </div>
                    @else
                        <div class="separator separator-dashed my-10"></div>
                        <h3 class="mb-5">Detail Penyaluran Bantuan</h3>
                        <div class="row mb-7">
                            <div class="col-lg-12">
                                <span class="fs-6 text-gray-700">Belum ada data penyaluran untuk penerima bantuan ini.</span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    </div>
@endsection