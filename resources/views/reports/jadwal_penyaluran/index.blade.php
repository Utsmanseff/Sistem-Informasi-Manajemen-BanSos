@extends('layouts.main') {{-- Menggunakan layout utama Metronic Anda --}}

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    {{-- Toolbar --}}
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Laporan Jadwal Penyaluran</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Laporan</li>
                    <li class="breadcrumb-item text-dark">Jadwal Penyaluran</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('reports.jadwal_penyaluran.print', request()->query()) }}" target="_blank" class="btn btn-sm fw-bold btn-primary">
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
                            <h3>Filter Laporan Jadwal Penyaluran</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body py-0 px-6">
                    <form action="{{ route('reports.jadwal_penyaluran.index') }}" method="GET" class="form">
                        <div class="row g-9 mb-6">
                            {{-- Filter Periode Bantuan --}}
                            <div class="col-md-4 fv-row">
                                <label for="periode_bantuan" class="form-label fs-6 fw-bold">Periode Bantuan</label>
                                <select name="periode_bantuan" id="periode_bantuan" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Pilih Periode" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($periodeBantuanOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $request->periode_bantuan == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Jenis Bantuan --}}
                            <div class="col-md-4 fv-row">
                                <label for="jenis_bantuan_id" class="form-label fs-6 fw-bold">Jenis Bantuan</label>
                                <select name="jenis_bantuan_id" id="jenis_bantuan_id" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Pilih Jenis Bantuan" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($jenisBantuans as $jenisBantuan)
                                        <option value="{{ $jenisBantuan->id }}" {{ $request->jenis_bantuan_id == $jenisBantuan->id ? 'selected' : '' }}>
                                            {{ $jenisBantuan->nama_bantuan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

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

                            {{-- Filter Petugas Ditugaskan --}}
                            <div class="col-md-4 fv-row">
                                <label for="ditugaskan_kepada_distributor_id" class="form-label fs-6 fw-bold">Petugas Ditugaskan</label>
                                <select name="ditugaskan_kepada_distributor_id" id="ditugaskan_kepada_distributor_id" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Pilih Petugas" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $request->ditugaskan_kepada_distributor_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            {{-- Search NIK / Nama KK --}}
                            <div class="col-md-6 fv-row">
                                <label for="search" class="form-label fs-6 fw-bold">Cari NIK / Nama Kepala Keluarga</label>
                                <input type="text" name="search" id="search" class="form-control form-control-solid" placeholder="NIK atau Nama KK" value="{{ $request->search }}">
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('reports.jadwal_penyaluran.index') }}" class="btn btn-light-primary me-3">Reset Filter</a>
                                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card Filter --}}

            {{-- Card Tabel Data --}}
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3>Daftar Jadwal Penyaluran Bantuan</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-row-bordered gy-5 gs-7">
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800">
                                    <th>No.</th>
                                    <th>Periode Bantuan</th>
                                    <th>Jenis Bantuan</th>
                                    <th>NIK KK</th>
                                    <th>Nama Kepala Keluarga</th>
                                    <th>Desa</th>
                                    <th>Petugas Ditugaskan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jadwalPenyalurans as $index => $jadwal)
                                <tr>
                                    <td>{{ $jadwalPenyalurans->firstItem() + $index }}</td>
                                    <td>{{ \Carbon\Carbon::parse($jadwal->periode_bantuan)->translatedFormat('F Y') }}</td>
                                    <td>{{ $jadwal->jenisBantuan->nama_bantuan ?? '-' }}</td>
                                    <td>{{ $jadwal->keluarga->nik_kepala_keluarga ?? '-' }}</td>
                                    <td>{{ $jadwal->keluarga->nama_kepala_keluarga ?? '-' }}</td>
                                    <td>{{ $jadwal->keluarga->desa->nama_desa ?? '-' }}</td>
                                    <td>{{ $jadwal->ditugaskanKepadaDistributor->name ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada jadwal penyaluran yang ditemukan untuk filter ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-5">
                        {{ $jadwalPenyalurans->links('pagination::bootstrap-5') }} {{-- Sesuaikan pagination view Anda --}}
                    </div>
                </div>
            </div>
            {{-- End Card Tabel Data --}}
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