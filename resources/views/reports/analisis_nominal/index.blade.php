@extends('layouts.main') {{-- Menggunakan layout utama Metronic Anda --}}

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    {{-- Toolbar --}}
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Analisis Nominal Bantuan Tersalurkan</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Laporan</li>
                    <li class="breadcrumb-item text-dark">Analisis Nominal Tersalurkan</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                {{-- Tombol Cetak PDF akan ditambahkan di sini nanti --}}
                <a href="{{ route('reports.analisis_nominal.print', request()->query()) }}" target="_blank" class="btn btn-sm fw-bold btn-primary">
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
                            <h3>Filter Analisis Nominal Bantuan</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body py-0 px-6">
                    <form action="{{ route('reports.analisis_nominal.index') }}" method="GET" class="form">
                        <div class="row g-9 mb-6">
                            {{-- Filter Periode Bantuan --}}
                            <div class="col-md-4 fv-row">
                                <label for="periode_bantuan" class="form-label fs-6 fw-bold">Periode Bantuan (Alokasi)</label>
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
                        </div>

                        <div class="row g-9 mb-8">
                            {{-- Filter Min Nominal --}}
                            <div class="col-md-4 fv-row">
                                <label for="min_nominal" class="form-label fs-6 fw-bold">Nominal Minimal (Rp)</label>
                                <input type="text" name="min_nominal" id="min_nominal" class="form-control form-control-solid nominal-input" placeholder="0" value="{{ $request->min_nominal }}">
                            </div>

                            {{-- Filter Max Nominal --}}
                            <div class="col-md-4 fv-row">
                                <label for="max_nominal" class="form-label fs-6 fw-bold">Nominal Maksimal (Rp)</label>
                                <input type="text" name="max_nominal" id="max_nominal" class="form-control form-control-solid nominal-input" placeholder="0" value="{{ $request->max_nominal }}">
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-12 text-end">
                                <a href="{{ route('reports.analisis_nominal.index') }}" class="btn btn-light-primary me-3">Reset Filter</a>
                                <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Card Filter --}}

            {{-- Statistik Keseluruhan --}}
            <div class="row g-5 g-xl-8 mb-xl-8">
                <div class="col-xl-4">
                    <div class="card card-xl-stretch mb-xl-8 bg-primary">
                        <div class="card-body p-0">
                            <div class="px-9 pt-7 card-rounded h-150px w-100 d-flex flex-column justify-content-between">
                                <div class="d-flex flex-column text-center text-white">
                                    <span class="fw-semibold fs-7 mb-2">Total Penerima Tersalurkan</span>
                                    <span class="fw-bold fs-2hx mb-1 mt-0">{{ number_format($totalPenerimaOverall, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card card-xl-stretch mb-xl-8 bg-success">
                        <div class="card-body p-0">
                            <div class="px-9 pt-7 card-rounded h-150px w-100 d-flex flex-column justify-content-between">
                                <div class="d-flex flex-column text-center text-white">
                                    <span class="fw-semibold fs-7 mb-2">Total Nominal Tersalurkan</span>
                                    <span class="fw-bold fs-2hx mb-1 mt-0">Rp. {{ number_format($totalNominalOverall, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card card-xl-stretch mb-xl-8 bg-info">
                        <div class="card-body p-0">
                            <div class="px-9 pt-7 card-rounded h-150px w-100 d-flex flex-column justify-content-between">
                                <div class="d-flex flex-column text-center text-white">
                                    <span class="fw-semibold fs-7 mb-2">Rata-rata Nominal per Penerima</span>
                                    <span class="fw-bold fs-2hx mb-1 mt-0">Rp. {{ number_format($avgNominalOverall, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End Statistik Keseluruhan --}}

            {{-- Card Tabel Agregasi per Jenis Bantuan --}}
            <div class="card mb-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3>Analisis per Jenis Bantuan</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-row-bordered gy-5 gs-7">
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800">
                                    <th>No.</th>
                                    <th>Jenis Bantuan</th>
                                    <th>Jumlah Penerima</th>
                                    <th>Total Nominal</th>
                                    <th>Rata-rata Nominal per Penerima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($analysisByJenisBantuan as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->nama_bantuan }}</td>
                                    <td class="text-center">{{ number_format($data->count, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp. {{ number_format($data->sum, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp. {{ number_format($data->count > 0 ? $data->sum / $data->count : 0, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data analisis per jenis bantuan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- End Card Tabel Agregasi per Jenis Bantuan --}}

            {{-- Card Tabel Agregasi per Desa --}}
            <div class="card mb-8">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h3>Analisis per Desa</h3>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-row-bordered gy-5 gs-7">
                            <thead>
                                <tr class="fw-semibold fs-6 text-gray-800">
                                    <th>No.</th>
                                    <th>Desa</th>
                                    <th>Jumlah Penerima</th>
                                    <th>Total Nominal</th>
                                    <th>Rata-rata Nominal per Penerima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($analysisByDesa as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->nama_desa }}</td>
                                    <td class="text-center">{{ number_format($data->count, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp. {{ number_format($data->sum, 0, ',', '.') }}</td>
                                    <td class="text-right">Rp. {{ number_format($data->count > 0 ? $data->sum / $data->count : 0, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data analisis per desa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- End Card Tabel Agregasi per Desa --}}

        </div>
    </div>
    {{-- End Content --}}
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk semua select dengan data-control="select2"
        $('.form-select[data-control="select2"]').select2();

        // Inisialisasi MaskMoney untuk input nominal
        $('.nominal-input').maskMoney({
            prefix: 'Rp. ',
            allowNegative: false,
            thousands: '.',
            decimal: ',',
            affixesStay: true
        });

        // Pastikan nilai terformat saat load halaman jika sudah ada di request
        @if($request->min_nominal)
            $('#min_nominal').maskMoney('mask', {{ (float)str_replace(['.', ','], ['', '.'], $request->min_nominal) }});
        @endif
        @if($request->max_nominal)
            $('#max_nominal').maskMoney('mask', {{ (float)str_replace(['.', ','], ['', '.'], $request->max_nominal) }});
        @endif
    });
</script>
@endpush