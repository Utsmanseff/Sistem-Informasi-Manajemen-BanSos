@extends('layouts.main')

@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Catat Penyaluran Bantuan</h1>
                <ul class="breadcrumb breadcrumb-dot fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item text-muted">Manajemen Bantuan</li>
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('penyaluran-bantuan.index') }}" class="text-muted text-hover-primary">Penyaluran Bantuan</a>
                    </li>
                    <li class="breadcrumb-item text-dark">Catat Baru</li>
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
                        <h2>Form Catat Penyaluran Bantuan</h2>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ route('penyaluran-bantuan.store') }}" method="POST" class="form" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label for="penerima_bantuan_id" class="required form-label">Penerima Bantuan (Cari NIK/Nama)</label>
                                <select name="penerima_bantuan_id" id="penerima_bantuan_id" class="form-select @error('penerima_bantuan_id') is-invalid @enderror" data-control="select2" data-placeholder="Cari NIK/Nama Kepala Keluarga">
                                    <option value=""></option>
                                    @foreach($penerimaBantuans as $penerima)
                                        <option value="{{ $penerima->id }}"
                                            data-jenis-bantuan="{{ $penerima->jenisBantuan->nama_bantuan ?? 'N/A' }}"
                                            data-periode-bantuan="{{ $penerima->periode_bantuan->translatedFormat('F Y') }}"
                                            data-nominal-dihitung="{{ 'Rp ' . number_format($penerima->nominal_dihitung, 2, ',', '.') }}"
                                            {{ old('penerima_bantuan_id') == $penerima->id ? 'selected' : '' }}>
                                            {{ $penerima->keluarga->nik_kepala_keluarga ?? 'N/A' }} - {{ $penerima->keluarga->nama_kepala_keluarga ?? 'N/A' }} ({{ $penerima->jenisBantuan->nama_bantuan ?? 'N/A' }} - {{ $penerima->periode_bantuan->translatedFormat('F Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('penerima_bantuan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 fv-row">
                                <label for="tanggal_penyaluran" class="required form-label">Tanggal dan Waktu Penyaluran</label>
                                <input type="datetime-local" name="tanggal_penyaluran" id="tanggal_penyaluran" class="form-control @error('tanggal_penyaluran') is-invalid @enderror" value="{{ old('tanggal_penyaluran', date('Y-m-d\TH:i')) }}" />
                                @error('tanggal_penyaluran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">Tanggal dan waktu aktual bantuan disalurkan.</div>
                            </div>
                        </div>

                        <div class="row g-9 mb-8">
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Jenis Bantuan:</label>
                                <span class="form-control form-control-solid" id="detail_jenis_bantuan">-- Pilih Penerima Bantuan --</span>
                            </div>
                            <div class="col-md-6 fv-row">
                                <label class="form-label">Periode Bantuan:</label>
                                <span class="form-control form-control-solid" id="detail_periode_bantuan">-- Pilih Penerima Bantuan --</span>
                            </div>
                        </div>

                        <div class="fv-row mb-8">
                            <label class="form-label">Nominal Dihitung (Otomatis dari Penerima Bantuan):</label>
                            <span class="form-control form-control-solid" id="detail_nominal_dihitung">-- Pilih Penerima Bantuan --</span>
                        </div>

                        <div class="fv-row mb-8">
                            <label for="jalur_foto_penerima" class="form-label">Foto Bukti Penyaluran (Opsional)</label>
                            <input type="file" name="jalur_foto_penerima" id="jalur_foto_penerima" class="form-control @error('jalur_foto_penerima') is-invalid @enderror" accept=".jpg, .jpeg, .png, .gif" />
                            @error('jalur_foto_penerima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">Unggah foto bukti penerimaan bantuan. Maks. 2MB.</div>
                        </div>

                        <div class="fv-row mb-8">
                            <label for="status_setelah_penyaluran" class="required form-label">Status Setelah Penyaluran</label>
                            <select name="status_setelah_penyaluran" id="status_setelah_penyaluran" class="form-select @error('status_setelah_penyaluran') is-invalid @enderror" data-control="select2" data-placeholder="Pilih Status">
                                <option value=""></option>
                                <option value="berhasil_disalurkan" {{ old('status_setelah_penyaluran') == 'berhasil_disalurkan' ? 'selected' : '' }}>Berhasil Disalurkan</option>
                                <option value="tidak_ditemukan" {{ old('status_setelah_penyaluran') == 'tidak_ditemukan' ? 'selected' : '' }}>Tidak Ditemukan</option>
                                <option value="menolak" {{ old('status_setelah_penyaluran') == 'menolak' ? 'selected' : '' }}>Menolak</option>
                            </select>
                            @error('status_setelah_penyaluran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="fv-row mb-8">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3" placeholder="Tambahkan catatan terkait penyaluran">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-center">
                            <a href="{{ route('penyaluran-bantuan.index') }}" class="btn btn-light me-3">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <span class="indicator-label">Simpan Penyaluran</span>
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

            $('#penerima_bantuan_id').select2({
                placeholder: "Cari NIK, Nama Kepala Keluarga, atau Jenis Bantuan",
                allowClear: true,
                templateResult: function (data) {
                    if (!data.id) { return data.text; }
                    var $option = $(data.element);
                    var nik = $option.text().split(' - ')[0];
                    var nama = $option.text().split(' - ')[1].split(' (')[0];
                    var jenisBantuan = $option.data('jenis-bantuan');
                    var periodeBantuan = $option.data('periode-bantuan');
                    return $('<span><strong>NIK: ' + nik + '</strong> - ' + nama + '<br><small class="text-muted">' + jenisBantuan + ' - ' + periodeBantuan + '</small></span>');
                },
                templateSelection: function (data) {
                    if (!data.id) { return data.text; }
                    var $option = $(data.element);
                    var nama = $option.text().split(' - ')[1].split(' (')[0];
                    var jenisBantuan = $option.data('jenis-bantuan');
                    var periodeBantuan = $option.data('periode-bantuan');
                    return $('<span>' + nama + ' (' + jenisBantuan + ' - ' + periodeBantuan + ')</span>');
                }
            });

            $('#penerima_bantuan_id').on('select2:select', function (e) {
                var selectedOption = e.params.data.element;
                $('#detail_jenis_bantuan').text($(selectedOption).data('jenis-bantuan'));
                $('#detail_periode_bantuan').text($(selectedOption).data('periode-bantuan'));
                $('#detail_nominal_dihitung').text($(selectedOption).data('nominal-dihitung'));
            }).on('select2:unselect', function (e) {
                $('#detail_jenis_bantuan').text('-- Pilih Penerima Bantuan --');
                $('#detail_periode_bantuan').text('-- Pilih Penerima Bantuan --');
                $('#detail_nominal_dihitung').text('-- Pilih Penerima Bantuan --');
            });

            @if(old('penerima_bantuan_id'))
                var selectedElement = $('#penerima_bantuan_id option[value="{{ old('penerima_bantuan_id') }}"]');
                if (selectedElement.length) {
                    $('#detail_jenis_bantuan').text(selectedElement.data('jenis-bantuan'));
                    $('#detail_periode_bantuan').text(selectedElement.data('periode-bantuan'));
                    $('#detail_nominal_dihitung').text(selectedElement.data('nominal-dihitung'));
                }
            @endif
        });
    </script>
@endpush