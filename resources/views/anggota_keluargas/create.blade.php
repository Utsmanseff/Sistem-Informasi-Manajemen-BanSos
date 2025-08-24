@extends('layouts.main') 

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Tambah Anggota Keluarga</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Data Penerima</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('keluargas.index') }}" class="text-muted text-hover-primary">Daftar Keluarga</a>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('keluargas.show', $keluarga->id) }}" class="text-muted text-hover-primary">Detail Keluarga {{ $keluarga->nama_kepala_keluarga }}</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Tambah Anggota</li>
                </ul>
            </div>
            </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                    <i class="fa-solid fa-exclamation-triangle fs-2hx text-danger me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger">Ada Kesalahan!</h4>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
                        <i class="fa-solid fa-times fs-2 text-danger"></i>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <h2>Formulir Penambahan Anggota Keluarga untuk: **{{ $keluarga->nama_kepala_keluarga }}**</h2>
                    </div>
                    </div>
                <div class="card-body py-4">
                    <form action="{{ route('keluargas.anggota-keluargas.store', $keluarga->id) }}" method="POST" class="form">
                        @csrf
                        
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="nik" class="required form-label">NIK Anggota Keluarga</label>
                                <input type="text" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror" placeholder="Cth: 3578xxxxxxxxxxxx" value="{{ old('nik') }}" maxlength="16" />
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="nama" class="required form-label">Nama Anggota Keluarga</label>
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Cth: Siti Aminah" value="{{ old('nama') }}" />
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="tanggal_lahir" class="required form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}" />
                                @error('tanggal_lahir')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="jenis_kelamin" class="required form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" data-control="select2" data-hide-search="true" data-placeholder="Pilih Jenis Kelamin">
                                    <option value=""></option>
                                    <option value="laki-laki" {{ old('jenis_kelamin') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="perempuan" {{ old('jenis_kelamin') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="hubungan_dengan_kk" class="required form-label">Hubungan dengan Kepala Keluarga</label>
                                <select name="hubungan_dengan_kk" id="hubungan_dengan_kk" class="form-select @error('hubungan_dengan_kk') is-invalid @enderror" data-control="select2" data-hide-search="false" data-placeholder="Pilih Hubungan">
                                    <option value=""></option>
                                    <option value="anak" {{ old('hubungan_dengan_kk') == 'anak' ? 'selected' : '' }}>Anak</option>
                                    <option value="istri" {{ old('hubungan_dengan_kk') == 'istri' ? 'selected' : '' }}>Istri</option>
                                    <option value="suami" {{ old('hubungan_dengan_kk') == 'suami' ? 'selected' : '' }}>Suami</option>
                                    <option value="orang_tua" {{ old('hubungan_dengan_kk') == 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                                    <option value="lainnya" {{ old('hubungan_dengan_kk') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('hubungan_dengan_kk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="tingkat_pendidikan" class="form-label">Tingkat Pendidikan (Opsional)</label>
                                <input type="text" name="tingkat_pendidikan" id="tingkat_pendidikan" class="form-control @error('tingkat_pendidikan') is-invalid @enderror" placeholder="Cth: SMA, S1 Informatika" value="{{ old('tingkat_pendidikan') }}" maxlength="50" />
                                @error('tingkat_pendidikan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="form-check form-check-custom form-check-solid mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_disabilitas_berat" name="is_disabilitas_berat" {{ old('is_disabilitas_berat') ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="is_disabilitas_berat">
                                        Memiliki Disabilitas Berat
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-custom form-check-solid mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_lansia" name="is_lansia" {{ old('is_lansia') ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="is_lansia">
                                        Termasuk Lansia
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-start">
                            <a href="{{ route('keluargas.show', $keluarga->id) }}" class="btn btn-light me-3">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan Anggota Keluarga</span>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.form-select[data-control="select2"]').select2();
        });
    </script>
@endpush