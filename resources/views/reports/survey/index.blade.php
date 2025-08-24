@extends('layouts.main') {{-- Menggunakan layout utama Metronic Anda --}}

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    {{-- Toolbar --}}
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Laporan Hasil Survei</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Laporan</li>
                    <li class="breadcrumb-item text-dark">Hasil Survei</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                {{-- Tombol Cetak PDF akan ditambahkan di sini nanti --}}
                <a href="{{ route('reports.survey.print', request()->query()) }}" target="_blank" class="btn btn-sm fw-bold btn-primary">
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
                            <h3>Filter Laporan</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body py-0 px-6">
                    <form action="{{ route('reports.survey.index') }}" method="GET" class="form">
                        <div class="row g-9 mb-6">
                            {{-- Filter Pencarian (NIK/Nama KK) --}}
                            <div class="col-md-4 fv-row">
                                <label for="search" class="form-label fs-6 fw-bold">Cari Keluarga/Petugas</label>
                                <input type="text" name="search" id="search" class="form-control form-control-solid" placeholder="NIK/Nama KK atau Nama Petugas" value="{{ $request->search }}">
                            </div>

                            {{-- Filter Status Verifikasi --}}
                            <div class="col-md-4 fv-row">
                                <label for="status_verifikasi" class="form-label fs-6 fw-bold">Status Verifikasi</label>
                                <select name="status_verifikasi" id="status_verifikasi" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Pilih Status" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($statusVerifikasiOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $request->status_verifikasi == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Petugas Survei --}}
                            <div class="col-md-4 fv-row">
                                <label for="petugas_survei_id" class="form-label fs-6 fw-bold">Petugas Survei</label>
                                <select name="petugas_survei_id" id="petugas_survei_id" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Pilih Petugas Survei" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($surveyors as $surveyor)
                                        <option value="{{ $surveyor->id }}" {{ $request->petugas_survei_id == $surveyor->id ? 'selected' : '' }}>
                                            {{ $surveyor->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
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

                            {{-- Filter Tanggal Mulai --}}
                            <div class="col-md-4 fv-row">
                                <label for="start_date" class="form-label fs-6 fw-bold">Tanggal Mulai Survei</label>
                                <input type="date" name="start_date" id="start_date" class="form-control form-control-solid" value="{{ $request->start_date }}">
                            </div>

                            {{-- Filter Tanggal Selesai --}}
                            <div class="col-md-4 fv-row">
                                <label for="end_date" class="form-label fs-6 fw-bold">Tanggal Selesai Survei</label>
                                <input type="date" name="end_date" id="end_date" class="form-control form-control-solid" value="{{ $request->end_date }}">
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('reports.survey.index') }}" class="btn btn-light-primary me-3">Reset Filter</a>
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
                        <h3>Daftar Hasil Survei</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-row-bordered gy-5 gs-7">
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800">
                                    <th>No.</th>
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
                                    <td>{{ $surveis->firstItem() + $index }}</td>
                                    <td>{{ $survei->keluarga->nik_kepala_keluarga ?? '-' }}</td>
                                    <td>{{ $survei->keluarga->nama_kepala_keluarga ?? '-' }}</td>
                                    <td>{{ $survei->keluarga->desa->nama_desa ?? '-' }}</td>
                                    <td>{{ $survei->keluarga->alamat_lengkap ?? '-' }}</td>
                                    <td>{{ $survei->tanggal_survei->format('d M Y H:i') }}</td>
                                    <td>{{ $survei->petugasSurvei->name ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-light-{{ 
                                            $survei->status_verifikasi == 'terverifikasi' ? 'success' : 
                                            ($survei->status_verifikasi == 'pending' ? 'warning' : 'danger') 
                                            }}">{{ Str::title(str_replace('_', ' ', $survei->status_verifikasi)) }}</span>
                                    </td>
                                    <td>{{ $survei->diverifikasiOleh->name ?? '-' }}</td>
                                    <td>{{ $survei->tanggal_verifikasi ? $survei->tanggal_verifikasi->format('d M Y H:i') : '-' }}</td>
                                    <td>{{ $survei->alasan_penolakan ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center">Tidak ada data survei yang ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-5">
                        {{ $surveis->links('pagination::bootstrap-5') }} {{-- Sesuaikan pagination view Anda --}}
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

        // Optional: Jika Anda ingin membatasi tanggal input
        // $('input[type="date"]').flatpickr({
        //     dateFormat: "Y-m-d",
        //     // maxDate: "today", // Jika ingin membatasi sampai hari ini
        // });
    });
</script>
@endpush
