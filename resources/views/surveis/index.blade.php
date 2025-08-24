@extends('layouts.main') 

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Daftar Survei</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Data Penerima</li>
                    <li class="breadcrumb-item text-dark">Daftar Survei</li>
                </ul>
            </div>
            </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
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

            @if(session('error'))
                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                    <i class="fa-solid fa-exclamation-triangle fs-2hx text-danger me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger">Ada Kesalahan!</h4>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <i class="fa-solid fa-times fs-2 text-danger"></i>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="fa-solid fa-search fs-3 position-absolute ms-5"></i>
                            <input type="text" data-kt-survei-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Data hasil survei" />
                        </div>
                        </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-survei-table-toolbar="base">
                        </div>
                        </div>
                    </div>
                <div class="card-body py-4">
                    @if($surveis->count() > 0)
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_survei">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th class="min-w-50px">#</th>
                                        <th class="min-w-150px">Kepala Keluarga</th>
                                        <th class="min-w-100px">Petugas Survei</th>
                                        <th class="min-w-120px">Tanggal Survei</th>
                                        <th class="min-w-100px">Lokasi (Lat, Long)</th>
                                        <th class="min-w-100px">Status Verifikasi</th>
                                        <th class="min-w-100px">Diverifikasi Oleh</th>
                                        <th class="min-w-120px">Tgl. Verifikasi</th>
                                        <th class="text-end min-w-100px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold">
                                    @foreach ($surveis as $survei)
                                        <tr>
                                            <td>{{ $loop->iteration + $surveis->firstItem() - 1 }}</td>
                                            <td>@if($survei->keluarga)
                                                    <a href="{{ route('keluargas.show', $survei->keluarga->id) }}" class="text-primary text-hover-primary-dark text-underline">
                                                        {{ $survei->keluarga->nama_kepala_keluarga }}
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $survei->petugasSurvei->name ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($survei->tanggal_survei)->translatedFormat('d F Y, H:i') }}</td>
                                            <td>
                                                @if($survei->latitude && $survei->longitude)
                                                    {{ $survei->latitude }}, {{ $survei->longitude }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $badgeClass = '';
                                                    switch($survei->status_verifikasi) {
                                                        case 'terverifikasi': $badgeClass = 'badge-light-success'; break;
                                                        case 'ditolak': $badgeClass = 'badge-light-danger'; break;
                                                        default: $badgeClass = 'badge-light-warning'; break; 
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">
                                                    {{ ucfirst($survei->status_verifikasi) }}
                                                </span>
                                            </td>
                                            <td>{{ $survei->diverifikasiOleh->name ?? '-' }}</td>
                                            <td>{{ $survei->tanggal_verifikasi ? \Carbon\Carbon::parse($survei->tanggal_verifikasi)->translatedFormat('d F Y, H:i') : '-' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('surveis.edit', $survei->id) }}" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1" title="Edit Survei">
                                                    <i class="fa-solid fa-pencil-alt fs-2"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <span class="fs-4">Tidak ada data survei yang ditemukan.</span>
                        </div>
                    @endif
                </div>
                </div>
            </div>
    </div>
    </div>
    <div class="mt-3">
        {{ $surveis->links() }}
    </div>
@endsection