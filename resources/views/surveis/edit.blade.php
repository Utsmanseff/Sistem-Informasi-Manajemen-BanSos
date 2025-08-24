@extends('layouts.main') 

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Edit Data Survei</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Data Penerima</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('surveis.index') }}" class="text-muted text-hover-primary">Daftar Survei</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Edit Survei</li>
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
                        <h2>Formulir Edit Survei ID: **{{ $survei->id }}** untuk Keluarga **{{ $survei->keluarga->nama_kepala_keluarga ?? 'N/A' }}**</h2>
                    </div>
                    </div>
                <div class="card-body py-4">
                    <form action="{{ route('surveis.update', $survei->id) }}" method="POST" class="form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') 

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="keluarga_id" class="required form-label">Keluarga</label>
                                <select name="keluarga_id" id="keluarga_id" class="form-select @error('keluarga_id') is-invalid @enderror" data-control="select2" data-placeholder="Pilih Keluarga">
                                    <option value=""></option>
                                    @foreach($keluargas as $keluarga)
                                        <option value="{{ $keluarga->id }}" {{ old('keluarga_id', $survei->keluarga_id) == $keluarga->id ? 'selected' : '' }}>
                                            {{ $keluarga->nama_kepala_keluarga }} (NIK: {{ $keluarga->nik_kepala_keluarga }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('keluarga_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="petugas_survei_id" class="required form-label">Petugas Survei</label>
                                <select name="petugas_survei_id" id="petugas_survei_id" class="form-select @error('petugas_survei_id') is-invalid @enderror" data-control="select2" data-placeholder="Pilih Petugas Survei">
                                    <option value=""></option>
                                    @foreach($petugasSurveiList as $petugas)
                                        <option value="{{ $petugas->id }}" {{ old('petugas_survei_id', $survei->petugas_survei_id) == $petugas->id ? 'selected' : '' }}>
                                            {{ $petugas->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('petugas_survei_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="tanggal_survei" class="required form-label">Tanggal dan Waktu Survei</label>
                                <input type="datetime-local" name="tanggal_survei" id="tanggal_survei" class="form-control @error('tanggal_survei') is-invalid @enderror" value="{{ old('tanggal_survei', $survei->tanggal_survei->format('Y-m-d\TH:i')) }}" />
                                @error('tanggal_survei')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="jalur_foto" class="form-label">Foto Survei</label>
                                <input type="file" name="jalur_foto" id="jalur_foto" class="form-control @error('jalur_foto') is-invalid @enderror" />
                                <div class="form-text">Maksimal ukuran file: 2MB. Format: JPG, PNG, GIF.</div>
                                @error('jalur_foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if ($survei->jalur_foto)
                                    <div class="mt-3">
                                        <label class="form-label">Foto Saat Ini:</label><br>
                                        <img src="{{ Storage::url($survei->jalur_foto) }}" alt="Foto Survei" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" name="latitude" id="latitude" class="form-control @error('latitude') is-invalid @enderror" placeholder="Cth: -7.7956" value="{{ old('latitude', $survei->latitude) }}" />
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" name="longitude" id="longitude" class="form-control @error('longitude') is-invalid @enderror" placeholder="Cth: 110.3695" value="{{ old('longitude', $survei->longitude) }}" />
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="status_verifikasi" class="required form-label">Status Verifikasi</label>
                                <select name="status_verifikasi" id="status_verifikasi" class="form-select @error('status_verifikasi') is-invalid @enderror" data-control="select2" data-hide-search="true" data-placeholder="Pilih Status Verifikasi">
                                    <option value=""></option>
                                    <option value="pending" {{ old('status_verifikasi', $survei->status_verifikasi) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="terverifikasi" {{ old('status_verifikasi', $survei->status_verifikasi) == 'terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                                    <option value="ditolak" {{ old('status_verifikasi', $survei->status_verifikasi) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('status_verifikasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row" id="alasan_penolakan_container" style="{{ old('status_verifikasi', $survei->status_verifikasi) == 'ditolak' ? '' : 'display: none;' }}">
                                <label for="alasan_penolakan" class="form-label">Alasan Penolakan</label>
                                <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control @error('alasan_penolakan') is-invalid @enderror" rows="3" placeholder="Sebutkan alasan penolakan jika status ditolak">{{ old('alasan_penolakan', $survei->alasan_penolakan) }}</textarea>
                                @error('alasan_penolakan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="diverifikasi_oleh_display" class="form-label">Diverifikasi Oleh</label>
                                <input type="text" id="diverifikasi_oleh_display" class="form-control" value="{{ $survei->diverifikasiOleh->name ?? '-' }}" readonly />
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="tanggal_verifikasi_display" class="form-label">Tanggal Verifikasi</label>
                                <input type="text" id="tanggal_verifikasi_display" class="form-control" value="{{ $survei->tanggal_verifikasi ? \Carbon\Carbon::parse($survei->tanggal_verifikasi)->translatedFormat('d F Y, H:i') : '-' }}" readonly />
                            </div>
                        </div>

                        <div class="d-flex justify-content-start">
                            <a href="{{ route('surveis.index') }}" class="btn btn-light me-3">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Perbarui Survei</span>
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