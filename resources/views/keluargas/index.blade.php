@extends('layouts.main') 

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Manajemen Keluarga</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/dashboard') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Data Penerima</li>
                    <li class="breadcrumb-item text-dark">Daftar Keluarga</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('keluargas.create') }}" class="btn btn-sm fw-bold btn-primary">
                    <i class="fa-solid fa-plus fs-2"></i> Tambah Keluarga Baru
                </a>
            </div>
            </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            @if (session('success'))
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

            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                    <i class="fa-solid fa-exclamation-circle fs-2hx text-danger me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger">Gagal!</h4>
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
                            <input type="text" data-kt-keluarga-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Keluarga" />
                        </div>
                        </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-keluarga-table-toolbar="base">
                        </div>
                        </div>
                    
                    </div>
                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_keluarga">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px">#</th>
                                    <th class="text-start min-w-150px">Aksi</th>
                                    <th class="min-w-150px">NIK Kepala Keluarga</th>
                                    <th class="min-w-200px">Nama Kepala Keluarga</th>
                                    <th class="min-w-200px">Jenis Kelamin</th>
                                    <th class="min-w-150px">Alamat Lengkap</th>
                                    <th class="min-w-80px">RT/RW</th>
                                    <th class="min-w-100px">Desa</th>
                                    <th class="min-w-100px">Status Ekonomi</th>
                                    <th class="min-w-100px">Kondisi Rumah</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @forelse ($keluargas as $keluarga)
                                    <tr>
                                        <td>{{ $loop->iteration + $keluargas->firstItem() - 1 }}</td>
                                        <td class="text-start">
                                            <a href="{{ route('keluargas.show', $keluarga->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" title="Lihat Detail">
                                                <i class="fa-solid fa-eye fs-2"></i>
                                            </a>
                                            <a href="{{ route('keluargas.edit', $keluarga->id) }}" class="btn btn-icon btn-bg-light btn-active-color-warning btn-sm me-1" title="Edit">
                                                <i class="fa-solid fa-pencil-alt fs-2"></i>
                                            </a>
                                            <form action="{{ route('keluargas.destroy', $keluarga->id) }}" method="POST" class="delete-form d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" title="Hapus" data-kt-keluarga-table-filter="delete_row">
                                                    <i class="fa-solid fa-trash-alt fs-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>{{ $keluarga->nik_kepala_keluarga }}</td>
                                        <td>{{ $keluarga->nama_kepala_keluarga }}</td>
                                        <td>{{ $keluarga->jk ?? '-' }}</td>
                                        <td>{{ $keluarga->alamat_lengkap }}</td>
                                        <td>{{ $keluarga->rt }}/{{ $keluarga->rw }}</td>
                                        <td>{{ $keluarga->desa->nama_desa ?? 'N/A' }}</td> 
                                        <td>
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
                                        <td>
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
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data keluarga yang ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
    </div>
    </div>

    {{ $keluargas->links() }}
@endsection