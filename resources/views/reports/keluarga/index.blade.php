@extends('layouts.main') {{-- Asumsi layout utama Anda --}}

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Laporan Data Keluarga</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Laporan</li>
                    <li class="breadcrumb-item text-dark">Data Keluarga</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                {{-- Tombol Cetak PDF akan ditambahkan di sini nanti --}}
                <a href="{{ route('reports.keluarga.print', request()->query()) }}" class="btn btn-sm fw-bold btn-primary" target="_blank">Cetak Laporan</a>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <h3>Filter Laporan</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body py-0 px-6">
                    <form action="{{ route('reports.keluarga.index') }}" method="GET" class="form">
                        <div class="row g-9 mb-6">
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
                            <div class="col-md-4 fv-row">
                                <label for="status_ekonomi" class="form-label fs-6 fw-bold">Status Ekonomi</label>
                                <select name="status_ekonomi" id="status_ekonomi" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Pilih Status Ekonomi" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($statusEkonomiOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $request->status_ekonomi == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 fv-row">
                                <label for="kondisi_rumah" class="form-label fs-6 fw-bold">Kondisi Rumah</label>
                                <select name="kondisi_rumah" id="kondisi_rumah" class="form-select form-select-solid fw-bold" data-control="select2" data-placeholder="Pilih Kondisi Rumah" data-allow-clear="true">
                                    <option value=""></option>
                                    @foreach($kondisiRumahOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $request->kondisi_rumah == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-9 mb-8">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('reports.keluarga.index') }}" class="btn btn-light-primary me-3">Reset Filter</a>
                                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3>Daftar Keluarga</h3>
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
                                    <th>RT/RW</th>
                                    <th>Desa</th>
                                    <th>Nomor Telepon</th>
                                    <th>Status Ekonomi</th>
                                    <th>Kondisi Rumah</th>
                                    <th>Pendapatan per Bulan</th>
                                    <th>Pekerjaan</th>
                                    <th>Disabilitas Berat</th>
                                    <th>Lansia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($keluargas as $index => $keluarga)
                                <tr>
                                    <td>{{ $keluargas->firstItem() + $index }}</td>
                                    <td>{{ $keluarga->nik_kepala_keluarga }}</td>
                                    <td>{{ $keluarga->nama_kepala_keluarga }}</td>
                                    <td>{{ $keluarga->alamat_lengkap }}</td>
                                    <td>{{ $keluarga->rt ?? '-' }}/{{ $keluarga->rw ?? '-' }}</td>
                                    <td>{{ $keluarga->desa->nama_desa ?? '-' }}</td>
                                    <td>{{ $keluarga->nomor_telepon ?? '-' }}</td>
                                    <td><span class="badge badge-light-{{ 
                                        $keluarga->status_ekonomi == 'sangat_miskin' ? 'danger' : 
                                        ($keluarga->status_ekonomi == 'miskin' ? 'warning' : 
                                        ($keluarga->status_ekonomi == 'rentan' ? 'info' : 'primary')) 
                                        }}">{{ Str::title(str_replace('_', ' ', $keluarga->status_ekonomi)) }}</span></td>
                                    <td><span class="badge badge-light-{{ 
                                        $keluarga->kondisi_rumah == 'sangat_buruk' ? 'danger' : 
                                        ($keluarga->kondisi_rumah == 'buruk' ? 'warning' : 
                                        ($keluarga->kondisi_rumah == 'sedang' ? 'info' : 'success')) 
                                        }}">{{ Str::title(str_replace('_', ' ', $keluarga->kondisi_rumah)) }}</span></td>
                                    <td>Rp {{ number_format($keluarga->pendapatan_per_bulan ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $keluarga->pekerjaan ?? '-' }}</td>
                                    <td>
                                        @if($keluarga->is_disabilitas_berat)
                                            <span class="badge badge-light-danger">Ya</span>
                                        @else
                                            <span class="badge badge-light-secondary">Tidak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($keluarga->is_lansia)
                                            <span class="badge badge-light-warning">Ya</span>
                                        @else
                                            <span class="badge badge-light-secondary">Tidak</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="14" class="text-center">Tidak ada data keluarga yang ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-center mt-5">
                        {{ $keluargas->links('pagination::bootstrap-5') }} {{-- Sesuaikan pagination view Anda --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
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