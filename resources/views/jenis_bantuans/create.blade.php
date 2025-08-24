@extends('layouts.main') 

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Tambah Jenis Bantuan Baru</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Master Data</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('jenis_bantuans.index') }}" class="text-muted text-hover-primary">Daftar Jenis Bantuan</a>
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
                        <h2>Formulir Penambahan Jenis Bantuan</h2>
                    </div>
                    </div>
                <div class="card-body py-4">
                    <form action="{{ route('jenis_bantuans.store') }}" method="POST" class="form">
                        @csrf
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="nama_bantuan" class="required form-label">Nama Bantuan</label>
                                <input type="text" name="nama_bantuan" id="nama_bantuan" class="form-control @error('nama_bantuan') is-invalid @enderror" placeholder="Contoh: BLT, PKH" value="{{ old('nama_bantuan') }}" />
                                @error('nama_bantuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="nominal_dasar" class="form-label">Nominal Dasar (Rp)</label>
                                <input type="number" name="nominal_dasar" id="nominal_dasar" class="form-control @error('nominal_dasar') is-invalid @enderror" placeholder="Contoh: 300000" value="{{ old('nominal_dasar') }}" min="0" />
                                @error('nominal_dasar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="nominal_tambahan_anak" class="form-label">Nominal Tambahan per Anak (Rp)</label>
                                <input type="number" name="nominal_tambahan_anak" id="nominal_tambahan_anak" class="form-control @error('nominal_tambahan_anak') is-invalid @enderror" placeholder="Contoh: 50000" value="{{ old('nominal_tambahan_anak') }}" min="0" />
                                @error('nominal_tambahan_anak')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="maksimal_anak_tambahan" class="form-label">Maksimal Anak Tambahan</label>
                                <input type="number" name="maksimal_anak_tambahan" id="maksimal_anak_tambahan" class="form-control @error('maksimal_anak_tambahan') is-invalid @enderror" placeholder="Contoh: 3 (untuk BLT)" value="{{ old('maksimal_anak_tambahan') }}" min="0" />
                                @error('maksimal_anak_tambahan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-start">
                            <a href="{{ route('jenis_bantuans.index') }}" class="btn btn-light me-3">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan Jenis Bantuan</span>
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