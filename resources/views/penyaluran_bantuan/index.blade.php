@extends('layouts.main')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Daftar Penyaluran Bantuan</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Manajemen Bantuan</li>
                    <li class="breadcrumb-item text-dark">Penyaluran Bantuan</li>
                </ul>
            </div>
            <div class="d-flex align-items-center gap-2 gap-lg-3">
                <a href="{{ route('penyaluran-bantuan.create') }}" class="btn btn-sm fw-bold btn-primary">
                    <i class="fa-solid fa-plus"></i> Catat Penyaluran Baru
                </a>
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
                        <h4 class="mb-1 text-danger">Terjadi Kesalahan!</h4>
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
                            <input type="text" data-kt-penyaluran-bantuan-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Cari Penyaluran Bantuan" />
                        </div>
                        </div>
                    <div class="card-toolbar">
                        <div class="d-flex justify-content-end" data-kt-penyaluran-bantuan-table-toolbar="base">
                        </div>
                        </div>
                    </div>
                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_penyaluran_bantuan">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-50px">No</th>
                                    <th class="min-w-175px">Nama Kepala Keluarga (NIK)</th>
                                    <th class="min-w-125px">Jenis Bantuan</th>
                                    <th class="min-w-125px">Tanggal Penyaluran</th>
                                    <th class="min-w-100px">Petugas Penyalur</th>
                                    <th class="min-w-125px">Status Penyaluran</th>
                                    <th class="text-end min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @forelse ($penyaluranBantuans as $penyaluran)
                                    <tr>
                                        <td>{{ $loop->iteration + $penyaluranBantuans->firstItem() - 1 }}</td>
                                        <td>
                                            @if($penyaluran->penerimaBantuan && $penyaluran->penerimaBantuan->keluarga)
                                                {{ $penyaluran->penerimaBantuan->keluarga->nama_kepala_keluarga }}
                                                <br><small class="text-muted">(NIK: {{ $penyaluran->penerimaBantuan->keluarga->nik_kepala_keluarga }})</small>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $penyaluran->penerimaBantuan->jenisBantuan->nama_bantuan ?? 'N/A' }}</td>
                                        <td>{{ $penyaluran->tanggal_penyaluran->translatedFormat('d F Y H:i') }}</td>
                                        <td>{{ $penyaluran->petugasPenyalur->name ?? 'N/A' }}</td>
                                        <td>
                                            @php
                                                $badgeClass = '';
                                                switch($penyaluran->status_setelah_penyaluran) {
                                                    case 'berhasil_disalurkan':
                                                        $badgeClass = 'badge-light-success';
                                                        break;
                                                    case 'tidak_ditemukan':
                                                        $badgeClass = 'badge-light-danger';
                                                        break;
                                                    case 'menolak':
                                                        $badgeClass = 'badge-light-warning';
                                                        break;
                                                    default:
                                                        $badgeClass = 'badge-light-info';
                                                        break;
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ str_replace('_', ' ', Str::title($penyaluran->status_setelah_penyaluran)) }}</span>
                                        </td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                                Aksi
                                                <span class="svg-icon svg-icon-5 m-0">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z" fill="currentColor" />
                                                    </svg>
                                                </span>
                                            </a>
                                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('penyaluran-bantuan.show', $penyaluran->id) }}" class="menu-link px-3">Detail</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <a href="{{ route('penyaluran-bantuan.edit', $penyaluran->id) }}" class="menu-link px-3">Edit</a>
                                                </div>
                                                <div class="menu-item px-3">
                                                    <form action="{{ route('penyaluran-bantuan.destroy', $penyaluran->id) }}" method="POST" class="delete-form-penyaluran">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="menu-link px-3 text-danger" data-kt-penyaluran-bantuan-table-filter="delete_row">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data penyaluran bantuan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex flex-stack flex-wrap pt-10">
                        {{ $penyaluranBantuans->links() }}
                    </div>
                    </div>
                </div>
            </div>
    </div>
    </div>
@endsection