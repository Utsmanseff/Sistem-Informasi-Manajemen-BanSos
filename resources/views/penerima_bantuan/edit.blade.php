@extends('layouts.main')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Edit Penerima Bantuan</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Manajemen Bantuan</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('penerima-bantuan.index') }}" class="text-muted text-hover-primary">Penerima Bantuan</a>
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
                        <h2>Form Edit Penerima Bantuan</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('penerima-bantuan.update', $penerimaBantuan->id) }}" method="POST" class="form">
                        @csrf
                        @method('PUT')

                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label for="keluarga_id" class="required form-label">Kepala Keluarga (Cari NIK/Nama)</label>
                                <select name="keluarga_id" id="keluarga_id" class="form-select @error('keluarga_id') is-invalid @enderror" data-control="select2" data-placeholder="Cari NIK atau Nama Kepala Keluarga">
                                    <option value=""></option>
                                    @foreach($keluargas as $keluarga)
                                        <option value="{{ $keluarga->id }}" {{ old('keluarga_id', $penerimaBantuan->keluarga_id) == $keluarga->id ? 'selected' : '' }}>
                                            {{ $keluarga->nik_kepala_keluarga }} - {{ $keluarga->nama_kepala_keluarga }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('keluarga_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 fv-row">
                                <label for="jenis_bantuan_id" class="required form-label">Jenis Bantuan</label>
                                <select name="jenis_bantuan_id" id="jenis_bantuan_id" class="form-select @error('jenis_bantuan_id') is-invalid @enderror" data-control="select2" data-placeholder="Pilih Jenis Bantuan">
                                    <option value=""></option>
                                    @foreach($jenisBantuans as $jenisBantuan)
                                        <option value="{{ $jenisBantuan->id }}" data-nama="{{ $jenisBantuan->nama_bantuan }}" {{ old('jenis_bantuan_id', $penerimaBantuan->jenis_bantuan_id) == $jenisBantuan->id ? 'selected' : '' }}>
                                            {{ $jenisBantuan->nama_bantuan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_bantuan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label for="periode_bantuan" class="required form-label">Periode Bantuan</label>
                                <select name="periode_bantuan" id="periode_bantuan" class="form-select @error('periode_bantuan') is-invalid @enderror" data-control="select2" data-placeholder="Pilih Periode">
                                    <option value=""></option>
                                    @foreach($periods as $value => $label)
                                        <option value="{{ $value }}" {{ old('periode_bantuan', $penerimaBantuan->periode_bantuan->toDateString()) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('periode_bantuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">Pilih bulan dan tahun periode bantuan.</div>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label for="status_penerima" class="required form-label">Status Penerima</label>
                                <select name="status_penerima" id="status_penerima" class="form-select @error('status_penerima') is-invalid @enderror" data-control="select2" data-placeholder="Pilih Status">
                                    <option value="belum_tersalurkan" {{ old('status_penerima', $penerimaBantuan->status_penerima) == 'belum_tersalurkan' ? 'selected' : '' }}>Belum Tersalurkan</option>
                                    <option value="tersalurkan" {{ old('status_penerima', $penerimaBantuan->status_penerima) == 'tersalurkan' ? 'selected' : '' }}>Tersalurkan</option>
                                </select>
                                @error('status_penerima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                            <div class="col-md-6 mb-8 fv-row">
                                <label for="ditugaskan_kepada_distributor_id" class="form-label">Ditugaskan Kepada Distributor (Opsional)</label>
                                <select name="ditugaskan_kepada_distributor_id" id="ditugaskan_kepada_distributor_id" class="form-select @error('ditugaskan_kepada_distributor_id') is-invalid @enderror" data-control="select2" data-placeholder="Pilih Distributor">
                                    <option value=""></option>
                                    @foreach($distributors as $distributor)
                                        <option value="{{ $distributor->id }}" {{ old('ditugaskan_kepada_distributor_id', $penerimaBantuan->ditugaskan_kepada_distributor_id) == $distributor->id ? 'selected' : '' }}>
                                            {{ $distributor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ditugaskan_kepada_distributor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                         <div class="row g-9 mb-8" id="nominal_manual_section" style="display: none;"> 
                            <div class="col-md-6 fv-row">
                                <label for="nominal_manual" class="required form-label">Nominal Bantuan (Isi Manual)</label>
                                <input type="number" name="nominal_manual" id="nominal_manual" class="form-control @error('nominal_manual') is-invalid @enderror" value="{{ old('nominal_dihitung', $penerimaBantuan->nominal_dihitung) }}" step="0.01" min="0">
                                <small class="form-text text-muted">Isi nominal bantuan jika jenisnya bukan BLT (contoh: 500000).</small>
                                @error('nominal_manual')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>    

                        <div class="fv-row mb-8">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3" placeholder="Masukkan catatan tambahan">{{ old('catatan', $penerimaBantuan->catatan) }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <a href="{{ route('penerima-bantuan.index') }}" class="btn btn-light me-3">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan Perubahan</span>
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

            $('#keluarga_id').select2({
                placeholder: "Cari NIK atau Nama Kepala Keluarga",
                allowClear: true,
                templateResult: function (data) {
                    if (!data.id) { return data.text; }
                    var text = data.text;
                    var parts = text.split(' - ');
                    if (parts.length === 2) {
                        var nik = parts[0];
                        var nama = parts[1];
                        return $('<span><strong>NIK: ' + nik + '</strong> - ' + nama + '</span>');
                    }
                    return data.text;
                },
                templateSelection: function (data) {
                    if (!data.id) { return data.text; }
                    var text = data.text;
                    var parts = text.split(' - ');
                    if (parts.length === 2) {
                        return $('<span>' + parts[1] + '</span>');
                    }
                    return data.text;
                }
            });

            const jenisBantuanSelect = $('#jenis_bantuan_id');
            const nominalManualSection = $('#nominal_manual_section');
            const nominalManualInput = $('#nominal_manual');

            function toggleNominalManualField() {
                const selectedNamaBantuan = jenisBantuanSelect.find('option:selected').data('nama');
                
                console.log('Selected Jenis Bantuan Name:', selectedNamaBantuan);

                if (selectedNamaBantuan !== 'BLT' && selectedNamaBantuan !== undefined && selectedNamaBantuan !== '') {
                    nominalManualSection.css('display', 'flex'); 
                    nominalManualInput.prop('required', true); 
                    nominalManualInput.attr('min', '0'); 
                } else {
                    nominalManualSection.css('display', 'none');
                    nominalManualInput.prop('required', false);
                    nominalManualInput.removeAttr('min'); 
                    nominalManualInput.val(''); 
                }
            }

            toggleNominalManualField(); 

            jenisBantuanSelect.on('change', toggleNominalManualField);
        });
    </script>
@endpush