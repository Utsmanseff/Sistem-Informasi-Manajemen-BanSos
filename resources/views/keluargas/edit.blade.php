@extends('layouts.main') 

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Edit Data Keluarga</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Data Penerima</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('keluargas.index') }}" class="text-muted text-hover-primary">Daftar Keluarga</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Edit</li>
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
                        <h2>Formulir Edit Data Keluarga: {{ $keluarga->nama_kepala_keluarga }}</h2>
                    </div>
                    </div>
                <div class="card-body py-4">
                    <form action="{{ route('keluargas.update', $keluarga->id) }}" method="POST" class="form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') 
                        
                        <h3 class="mb-5 text-primary">Data Kepala Keluarga</h3>
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="nik_kepala_keluarga" class="required form-label">NIK Kepala Keluarga</label>
                                <input type="text" name="nik_kepala_keluarga" id="nik_kepala_keluarga" class="form-control @error('nik_kepala_keluarga') is-invalid @enderror" placeholder="Cth: 3578xxxxxxxxxxxx" value="{{ old('nik_kepala_keluarga', $keluarga->nik_kepala_keluarga) }}" maxlength="16" />
                                @error('nik_kepala_keluarga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="nama_kepala_keluarga" class="required form-label">Nama Kepala Keluarga</label>
                                <input type="text" name="nama_kepala_keluarga" id="nama_kepala_keluarga" class="form-control @error('nama_kepala_keluarga') is-invalid @enderror" placeholder="Cth: Budi Santoso" value="{{ old('nama_kepala_keluarga', $keluarga->nama_kepala_keluarga) }}" />
                                @error('nama_kepala_keluarga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                <input type="text" name="nomor_telepon" id="nomor_telepon" class="form-control @error('nomor_telepon') is-invalid @enderror" placeholder="Cth: 0812xxxxxx" value="{{ old('nomor_telepon', $keluarga->nomor_telepon) }}" maxlength="20" />
                                @error('nomor_telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="pekerjaan" class="form-label">Pekerjaan</label>
                                <input type="text" name="pekerjaan" id="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" placeholder="Cth: Petani" value="{{ old('pekerjaan', $keluarga->pekerjaan) }}" maxlength="100" />
                                @error('pekerjaan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="pendapatan_per_bulan" class="form-label">Pendapatan per Bulan (Rp)</label>
                                <input type="number" name="pendapatan_per_bulan" id="pendapatan_per_bulan" class="form-control @error('pendapatan_per_bulan') is-invalid @enderror" placeholder="Cth: 2000000" value="{{ old('pendapatan_per_bulan', $keluarga->pendapatan_per_bulan) }}" min="0" step="0.01" />
                                @error('pendapatan_per_bulan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="status_ekonomi" class="required form-label">Status Ekonomi</label>
                                <select name="status_ekonomi" id="status_ekonomi" class="form-select @error('status_ekonomi') is-invalid @enderror" data-control="select2" data-hide-search="true" data-placeholder="Pilih Status Ekonomi">
                                    <option value=""></option>
                                    <option value="sangat_miskin" {{ old('status_ekonomi', $keluarga->status_ekonomi) == 'sangat_miskin' ? 'selected' : '' }}>Sangat Miskin</option>
                                    <option value="miskin" {{ old('status_ekonomi', $keluarga->status_ekonomi) == 'miskin' ? 'selected' : '' }}>Miskin</option>
                                    <option value="rentan" {{ old('status_ekonomi', $keluarga->status_ekonomi) == 'rentan' ? 'selected' : '' }}>Rentan</option>
                                    <option value="menengah" {{ old('status_ekonomi', $keluarga->status_ekonomi) == 'menengah' ? 'selected' : '' }}>Menengah</option>
                                </select>
                                @error('status_ekonomi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="kondisi_rumah" class="required form-label">Kondisi Rumah</label>
                                <select name="kondisi_rumah" id="kondisi_rumah" class="form-select @error('kondisi_rumah') is-invalid @enderror" data-control="select2" data-hide-search="true" data-placeholder="Pilih Kondisi Rumah">
                                    <option value=""></option>
                                    <option value="sangat_buruk" {{ old('kondisi_rumah', $keluarga->kondisi_rumah) == 'sangat_buruk' ? 'selected' : '' }}>Sangat Buruk</option>
                                    <option value="buruk" {{ old('kondisi_rumah', $keluarga->kondisi_rumah) == 'buruk' ? 'selected' : '' }}>Buruk</option>
                                    <option value="sedang" {{ old('kondisi_rumah', $keluarga->kondisi_rumah) == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                    <option value="baik" {{ old('kondisi_rumah', $keluarga->kondisi_rumah) == 'baik' ? 'selected' : '' }}>Baik</option>
                                </select>
                                @error('kondisi_rumah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="desa_id" class="required form-label">Desa</label>
                                <select name="desa_id" id="desa_id" class="form-select @error('desa_id') is-invalid @enderror" data-control="select2" data-placeholder="Pilih Desa">
                                    <option value=""></option>
                                    @foreach($desas as $desa)
                                        <option value="{{ $desa->id }}" {{ old('desa_id', $keluarga->desa_id) == $desa->id ? 'selected' : '' }}>{{ $desa->nama_desa }}</option>
                                    @endforeach
                                </select>
                                @error('desa_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="alamat_lengkap" class="required form-label">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" id="alamat_lengkap" class="form-control @error('alamat_lengkap') is-invalid @enderror" rows="3" placeholder="Cth: Jl. Merdeka No. 123">{{ old('alamat_lengkap', $keluarga->alamat_lengkap) }}</textarea>
                                @error('alamat_lengkap')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 fv-row">
                                <label for="rt" class="form-label">RT</label>
                                <input type="text" name="rt" id="rt" class="form-control @error('rt') is-invalid @enderror" placeholder="Cth: 001" value="{{ old('rt', $keluarga->rt) }}" maxlength="10" />
                                @error('rt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 fv-row">
                                <label for="rw" class="form-label">RW</label>
                                <input type="text" name="rw" id="rw" class="form-control @error('rw') is-invalid @enderror" placeholder="Cth: 002" value="{{ old('rw', $keluarga->rw) }}" maxlength="10" />
                                @error('rw')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-10">
                            <div class="col-md-6">
                                <div class="form-check form-check-custom form-check-solid mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_disabilitas_berat" name="is_disabilitas_berat" {{ old('is_disabilitas_berat', $keluarga->is_disabilitas_berat) ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="is_disabilitas_berat">
                                        Memiliki Disabilitas Berat
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-custom form-check-solid mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_lansia" name="is_lansia" {{ old('is_lansia', $keluarga->is_lansia) ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="is_lansia">
                                        Memiliki Anggota Lansia
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-6 fv-row">
                                <label for="jalur_slip_gaji" class="form-label">Upload Slip Gaji (Opsional)</label>
                                @if($keluarga->jalur_slip_gaji)
                                    <div class="alert alert-light-primary alert-dismissible fade show p-3 mb-2" role="alert">
                                        <i class="fa-solid fa-file-alt text-primary me-2"></i>
                                        File saat ini: <a href="{{ Storage::url($keluarga->jalur_slip_gaji) }}" target="_blank" class="fw-bold">{{ basename($keluarga->jalur_slip_gaji) }}</a>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" value="1" id="remove_jalur_slip_gaji" name="remove_jalur_slip_gaji">
                                            <label class="form-check-label" for="remove_jalur_slip_gaji">
                                                Hapus file ini
                                            </label>
                                        </div>
                                    </div>
                                    <input type="file" name="jalur_slip_gaji" id="jalur_slip_gaji" class="form-control @error('jalur_slip_gaji') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf" />
                                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah. Max 2MB. Format: JPG, JPEG, PNG, PDF.</div>
                                @else
                                    <input type="file" name="jalur_slip_gaji" id="jalur_slip_gaji" class="form-control @error('jalur_slip_gaji') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf" />
                                    <div class="form-text">Max 2MB. Format: JPG, JPEG, PNG, PDF.</div>
                                @endif
                                @error('jalur_slip_gaji')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="jalur_foto_kk" class="form-label">Upload Foto Kartu Keluarga (Opsional)</label>
                                @if($keluarga->jalur_foto_kk)
                                    <div class="alert alert-light-primary alert-dismissible fade show p-3 mb-2" role="alert">
                                        <i class="fa-solid fa-file-alt text-primary me-2"></i>
                                        File saat ini: <a href="{{ Storage::url($keluarga->jalur_foto_kk) }}" target="_blank" class="fw-bold">{{ basename($keluarga->jalur_foto_kk) }}</a>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" value="1" id="remove_jalur_foto_kk" name="remove_jalur_foto_kk">
                                            <label class="form-check-label" for="remove_jalur_foto_kk">
                                                Hapus file ini
                                            </label>
                                        </div>
                                    </div>
                                    <input type="file" name="jalur_foto_kk" id="jalur_foto_kk" class="form-control @error('jalur_foto_kk') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf" />
                                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah. Max 2MB. Format: JPG, JPEG, PNG, PDF.</div>
                                @else
                                    <input type="file" name="jalur_foto_kk" id="jalur_foto_kk" class="form-control @error('jalur_foto_kk') is-invalid @enderror" accept=".jpg,.jpeg,.png,.pdf" />
                                    <div class="form-text">Max 2MB. Format: JPG, JPEG, PNG, PDF.</div>
                                @endif
                                @error('jalur_foto_kk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-10">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="5" placeholder="Tambahkan catatan jika ada">{{ old('catatan', $keluarga->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-start">
                            <a href="{{ route('keluargas.index') }}" class="btn btn-light me-3">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Perbarui Data Keluarga</span>
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