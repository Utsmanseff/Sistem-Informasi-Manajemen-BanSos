@extends('layouts.main')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Edit Penyaluran Bantuan</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Manajemen Bantuan</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('penyaluran-bantuan.index') }}" class="text-muted text-hover-primary">Penyaluran Bantuan</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Edit</li>
                </ul>
            </div>
            </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
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
                        <h2>Form Edit Penyaluran Bantuan</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('penyaluran-bantuan.update', $penyaluranBantuan->id) }}" method="POST" class="form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') 

                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label for="penerima_bantuan_id" class="required form-label">Penerima Bantuan</label>
                                <input type="hidden" name="penerima_bantuan_id" value="{{ $penyaluranBantuan->penerima_bantuan_id }}">
                                <span class="form-control form-control-solid">{{ $penyaluranBantuan->penerimaBantuan->keluarga->nik_kepala_keluarga ?? 'N/A' }} - {{ $penyaluranBantuan->penerimaBantuan->keluarga->nama_kepala_keluarga ?? 'N/A' }}</span>
                                <div class="form-text text-muted">Penerima bantuan tidak dapat diubah setelah penyaluran tercatat.</div>
                            </div>

                            <div class="col-md-6 fv-row">
                                <label for="tanggal_penyaluran" class="required form-label">Tanggal dan Waktu Penyaluran</label>
                                <input type="datetime-local" name="tanggal_penyaluran" id="tanggal_penyaluran" class="form-control @error('tanggal_penyaluran') is-invalid @enderror" value="{{ old('tanggal_penyaluran', $penyaluranBantuan->tanggal_penyaluran ? $penyaluranBantuan->tanggal_penyaluran->format('Y-m-d\TH:i') : '') }}" />
                                @error('tanggal_penyaluran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">Tanggal dan waktu aktual bantuan disalurkan.</div>
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Jenis Bantuan:</label>
                                <span class="form-control form-control-solid">{{ $penyaluranBantuan->penerimaBantuan->jenisBantuan->nama_bantuan ?? 'N/A' }}</span>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Periode Bantuan:</label>
                                <span class="form-control form-control-solid">{{ $penyaluranBantuan->penerimaBantuan->periode_bantuan->translatedFormat('F Y') }}</span>
                            </div>
                        </div>

                        <div class="fv-row mb-8">
                            <label class="form-label">Nominal Dihitung:</label>
                            <span class="form-control form-control-solid">{{ 'Rp ' . number_format($penyaluranBantuan->penerimaBantuan->nominal_dihitung, 2, ',', '.') }}</span>
                        </div>

                        <div class="fv-row mb-8">
                            <label for="jalur_foto_penerima" class="form-label">Foto Bukti Penyaluran (Opsional)</label>
                            @if ($penyaluranBantuan->jalur_foto_penerima)
                                <div class="mb-3">
                                    <span class="text-muted d-block mb-1">Foto saat ini:</span>
                                    <a href="{{ asset('storage/' . $penyaluranBantuan->jalur_foto_penerima) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $penyaluranBantuan->jalur_foto_penerima) }}" alt="Foto Bukti Penyaluran" class="img-thumbnail" style="max-width: 200px;">
                                    </a>
                                </div>
                            @else
                                <span class="text-muted d-block mb-2">Belum ada foto bukti penyaluran.</span>
                            @endif
                            <input type="file" name="jalur_foto_penerima" id="jalur_foto_penerima" class="form-control @error('jalur_foto_penerima') is-invalid @enderror" accept=".jpg, .jpeg, .png, .gif" />
                            @error('jalur_foto_penerima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">Unggah foto baru untuk mengganti foto lama atau tambahkan jika belum ada. Maks. 2MB.</div>
                        </div>

                        <div class="fv-row mb-8">
                            <label for="status_setelah_penyaluran" class="required form-label">Status Setelah Penyaluran</label>
                            <select name="status_setelah_penyaluran" id="status_setelah_penyaluran" class="form-select @error('status_setelah_penyaluran') is-invalid @enderror" data-control="select2" data-placeholder="Pilih Status">
                                <option value=""></option>
                                <option value="berhasil_disalurkan" {{ (old('status_setelah_penyaluran', $penyaluranBantuan->status_setelah_penyaluran) == 'berhasil_disalurkan') ? 'selected' : '' }}>Berhasil Disalurkan</option>
                                <option value="tidak_ditemukan" {{ (old('status_setelah_penyaluran', $penyaluranBantuan->status_setelah_penyaluran) == 'tidak_ditemukan') ? 'selected' : '' }}>Tidak Ditemukan</option>
                                <option value="menolak" {{ (old('status_setelah_penyaluran', $penyaluranBantuan->status_setelah_penyaluran) == 'menolak') ? 'selected' : '' }}>Menolak</option>
                            </select>
                            @error('status_setelah_penyaluran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="fv-row mb-8">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3" placeholder="Tambahkan catatan terkait penyaluran">{{ old('catatan', $penyaluranBantuan->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <a href="{{ route('penyaluran-bantuan.index') }}" class="btn btn-light me-3">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Update Penyaluran</span>
                                <span class="indicator-progress">Mohon Tunggu...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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