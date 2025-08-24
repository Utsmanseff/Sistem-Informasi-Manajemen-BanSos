@extends('layouts.main') {{-- Menggunakan layout utama Metronic Anda --}}

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    {{-- Toolbar --}}
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Detail Data Keluarga</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Laporan</li>
                    <li class="breadcrumb-item text-dark">Detail Data Keluarga</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('reports.detail_data_keluarga.print', request()->query()) }}" target="_blank" class="btn btn-sm fw-bold btn-primary">
                    Cetak Laporan PDF
                </a>
            </div>
        </div>
    </div>
    {{-- End Toolbar --}}

    {{-- Content --}}
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            {{-- Pesan Error/Sukses --}}
            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                    <i class="fa-solid fa-exclamation-triangle fs-2hx text-danger me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger">Terjadi Kesalahan!</h4>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <i class="fa-solid fa-times fs-2 text-danger"></i>
                    </button>
                </div>
            @endif
            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                    <i class="fa-solid fa-check-circle fs-2hx text-success me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-success">Berhasil!</h4>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <i class="fa-solid fa-times fs-2 text-success"></i>
                    </button>
                </div>
            @endif

            {{-- Card Filter --}}
            <div class="card mb-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <h3>Filter Data Keluarga</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body py-0 px-6">
                    <form action="{{ route('reports.detail_data_keluarga.index') }}" method="GET" class="form">
                        <div class="row g-9 mb-6">
                            {{-- Filter Desa --}}
                            <div class="col-md-4 fv-row">
                                <label for="desa_id" class="form-label fs-6 fw-bold">Desa</label>
                                <select name="desa_id" id="desa_id" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Pilih Desa" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ $request->desa_id == $desa->id ? 'selected' : '' }}>
                                            {{ $desa->nama_desa }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter NIK Kepala Keluarga --}}
                            <div class="col-md-4 fv-row">
                                <label for="nik_kepala_keluarga" class="form-label fs-6 fw-bold">NIK Kepala Keluarga</label>
                                <input type="text" name="nik_kepala_keluarga" id="nik_kepala_keluarga" class="form-control form-control-solid" placeholder="Cari NIK..." value="{{ $request->nik_kepala_keluarga }}">
                            </div>

                            {{-- Filter Nama Kepala Keluarga --}}
                            <div class="col-md-4 fv-row">
                                <label for="nama_kepala_keluarga" class="form-label fs-6 fw-bold">Nama Kepala Keluarga</label>
                                <input type="text" name="nama_kepala_keluarga" id="nama_kepala_keluarga" class="form-control form-control-solid" placeholder="Cari Nama..." value="{{ $request->nama_kepala_keluarga }}">
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            {{-- Filter Status Ekonomi --}}
                            <div class="col-md-4 fv-row">
                                <label for="status_ekonomi" class="form-label fs-6 fw-bold">Status Ekonomi</label>
                                <select name="status_ekonomi" id="status_ekonomi" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Pilih Status Ekonomi" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($statusEkonomiOptions as $option)
                                        <option value="{{ $option['value'] }}" {{ $request->status_ekonomi == $option['value'] ? 'selected' : '' }}>
                                            {{ $option['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Kondisi Rumah --}}
                            <div class="col-md-4 fv-row">
                                <label for="kondisi_rumah" class="form-label fs-6 fw-bold">Kondisi Rumah</label>
                                <select name="kondisi_rumah" id="kondisi_rumah" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Pilih Kondisi Rumah" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($kondisiRumahOptions as $option)
                                        <option value="{{ $option['value'] }}" {{ $request->kondisi_rumah == $option['value'] ? 'selected' : '' }}>
                                            {{ $option['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                             {{-- Filter Pekerjaan --}}
                             <div class="col-md-4 fv-row">
                                <label for="pekerjaan" class="form-label fs-6 fw-bold">Pekerjaan</label>
                                <input type="text" name="pekerjaan" id="pekerjaan" class="form-control form-control-solid" placeholder="Cari Pekerjaan..." value="{{ $request->pekerjaan }}">
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('reports.detail_data_keluarga.index') }}" class="btn btn-light-primary me-3">Reset Filter</a>
                                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card Filter --}}

            {{-- Card Tabel Data Keluarga --}}
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3>Daftar Data Keluarga</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-row-bordered gy-5 gs-7">
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800">
                                    <th>No.</th>
                                    <th>NIK Kepala Keluarga</th>
                                    <th>Nama Kepala Keluarga</th>
                                    <th>Alamat Lengkap</th>
                                    <th>Desa</th>
                                    <th>RT/RW</th>
                                    <th>Status Ekonomi</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Pendapatan/Bulan</th>
                                    <th>Pekerjaan</th>
                                    <th>Anggota Keluarga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($keluargas as $index => $keluarga)
                                <tr>
                                    <td>{{ $keluargas->firstItem() + $index }}</td>
                                    <td>{{ $keluarga->nik_kepala_keluarga }}</td>
                                    <td>{{ $keluarga->nama_kepala_keluarga }}</td>
                                    <td>{{ $keluarga->alamat_lengkap }}</td>
                                    <td>{{ $keluarga->desa->nama_desa ?? 'N/A' }}</td>
                                    <td>{{ $keluarga->rt ?? 'N/A' }}/{{ $keluarga->rw ?? 'N/A' }}</td>
                                    <td>{{ Str::title(str_replace('_', ' ', $keluarga->status_ekonomi)) }}</td>
                                    <td>{{ Str::title(str_replace('_', ' ', $keluarga->kondisi_rumah)) }}</td>
                                    <td>Rp. {{ number_format($keluarga->pendapatan_per_bulan, 0, ',', '.') }}</td>
                                    <td>{{ $keluarga->pekerjaan ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $keluarga->anggotaKeluarga->count() }} Orang</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">Tidak ada data keluarga yang ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-end">
                        {{ $keluargas->links() }}
                    </div>
                </div>
            </div>
            {{-- End Card Tabel Data Keluarga --}}

        </div>
    </div>
    {{-- End Content --}}
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk semua select dengan data-control="select2"
        $('.form-select[data-control="select2"]').select2();
    });
</script>
@endpush