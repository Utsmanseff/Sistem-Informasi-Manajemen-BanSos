@extends('layouts.main') 

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Tambah Desa Baru</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Master Data</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('desas.index') }}" class="text-muted text-hover-primary">Daftar Desa</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Tambah Baru</li>
                </ul>
            </div>
            </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Formulir Penambahan Desa</h2>
                    </div>
                    </div>
                <div class="card-body py-4">
                    <form action="{{ route('desas.store') }}" method="POST" class="form">
                        @csrf
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="nama_desa" class="required form-label">Nama Desa</label>
                                <input type="text" name="nama_desa" id="nama_desa" class="form-control @error('nama_desa') is-invalid @enderror" placeholder="Contoh: Desa Makmur" value="{{ old('nama_desa') }}" />
                                @error('nama_desa')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-start">
                            <a href="{{ route('desas.index') }}" class="btn btn-light me-3">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan Desa</span>
                                <span class="indicator-progress">Harap tunggu...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
    </div>
    </div>
@endsection