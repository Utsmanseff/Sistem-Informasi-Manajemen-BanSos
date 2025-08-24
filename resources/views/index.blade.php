@extends('layouts.main') 

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid mt-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">Dashboard Sistem Informasi Bantuan Sosial <br>
                                <small class="text-muted">Selamat datang, {{ Auth::user()->name }} ({{ $roleName }})!</small>
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="kpi-section">
                            <h2>Ringkasan Statistik</h2>
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-primary card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-primary mb-3">
                                                <i class="flaticon-statistics icon-xl text-primary"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">{{ number_format($kpis['total_survei'] ?? 0) }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Total Survei</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-warning card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-warning mb-3">
                                                <i class="flaticon-warning icon-xl text-warning"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">{{ number_format($kpis['survei_pending'] ?? 0) }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Survei Pending</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-success card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-success mb-3">
                                                <i class="flaticon-valid icon-xl text-success"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">{{ number_format($kpis['survei_terverifikasi'] ?? 0) }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Survei Terverifikasi</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-danger card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-danger mb-3">
                                                <i class="flaticon-cancel icon-xl text-danger"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">{{ number_format($kpis['survei_ditolak'] ?? 0) }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Survei Ditolak</a>
                                        </div>
                                    </div>
                                </div>

                                @if($roleName === 'Surveyor')
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-info card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-info mb-3">
                                                <i class="flaticon-clipboard icon-xl text-info"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">{{ number_format($kpis['total_survei_saya'] ?? 0) }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Total Survei (Saya)</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-warning card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-warning mb-3">
                                                <i class="flaticon-hourglass icon-xl text-warning"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">{{ number_format($kpis['survei_pending_saya'] ?? 0) }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Survei Pending (Saya)</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-success card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-success mb-3">
                                                <i class="flaticon-checked icon-xl text-success"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">{{ number_format($kpis['survei_terverifikasi_saya'] ?? 0) }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Survei Terverifikasi (Saya)</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-danger card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-danger mb-3">
                                                <i class="flaticon-close icon-xl text-danger"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">{{ number_format($kpis['survei_ditolak_saya'] ?? 0) }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Survei Ditolak (Saya)</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                @if($roleName === 'Distributor')
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-primary card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-primary mb-3">
                                                <i class="flaticon-truck icon-xl text-primary"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">{{ number_format($kpis['bantuan_saya_tersalurkan_count'] ?? 0) }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Bantuan Tersalurkan (Saya)</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-warning card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-warning mb-3">
                                                <i class="flaticon-clipboard icon-xl text-warning"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">{{ number_format($kpis['bantuan_saya_belum_tersalurkan'] ?? 0) }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Bantuan Belum Tersalurkan (Saya)</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12 mb-5">
                                    <div class="card card-custom bg-light-success card-stretch gutter-b">
                                        <div class="card-body">
                                            <span class="svg-icon svg-icon-3x svg-icon-success mb-3">
                                                <i class="flaticon-price-tag icon-xl text-success"></i>
                                            </span>
                                            <div class="text-dark font-weight-bolder font-size-h2 mb-2 mt-5">Rp {{ number_format($kpis['total_nominal_tersalurkan_saya'] ?? 0, 0, ',', '.') }}</div>
                                            <a href="#" class="text-muted font-weight-bold font-size-lg">Total Nominal Tersalurkan (Saya)</a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <hr class="mb-8 mt-8"/>

                        <div class="charts-section">
                        <h2>Tren & Distribusi Data</h2>
                            <div class="row">
                                @if($roleName === 'Admin' || $roleName === 'Kepala')
                                <div class="col-lg-6 mb-5">
                                    <div class="card card-custom gutter-b card-stretch" style="height: 400px;">
                                        <div class="card-body h-300px">
                                            <h5 class="text-dark font-weight-bolder mb-5">Status Verifikasi Survei (Global)</h5>
                                            <canvas id="surveiStatusChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                @elseif($roleName === 'Surveyor')
                                <div class="col-lg-6 mb-5">
                                    <div class="card card-custom gutter-b card-stretch" style="height: 400px;">
                                        <div class="card-body h-300px">
                                            <h5 class="text-dark font-weight-bolder mb-5">Status Verifikasi Survei (Saya)</h5>
                                            <canvas id="surveiStatusChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="col-lg-6 mb-5">
                                    <div class="card card-custom gutter-b card-stretch" style="height: 400px;">
                                        <div class="card-body h-300px">
                                            <h5 class="text-dark font-weight-bolder mb-5">Penyaluran Bantuan per Periode (Penerima)</h5>
                                            <canvas id="penyaluranPenerimaChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-5">
                                    <div class="card card-custom gutter-b card-stretch" style="height: 400px;">
                                        <div class="card-body h-300px">
                                            <h5 class="text-dark font-weight-bolder mb-5">Penyaluran Bantuan per Periode (Nominal)</h5>
                                            <canvas id="penyaluranNominalChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-5">
                                    <div class="card card-custom gutter-b card-stretch" style="height: 400px;">
                                        <div class="card-body h-300px">
                                            <h5 class="text-dark font-weight-bolder mb-5">Distribusi Jenis Bantuan</h5>
                                            <canvas id="jenisBantuanChart"></canvas>
                                       </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="mt-8"/>

                        <div class="aktivitas-section">
                            <div class="row">
                                <div class="col-lg-6 mb-5">
                                    <div class="card card-custom gutter-b card-stretch" style="height: 500px;">
                                        <div class="card-header border-0 py-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label font-weight-bolder text-dark">Jadwal Penyaluran Terdekat</span>
                                                <span class="text-muted mt-3 font-weight-bold font-size-sm">Data 50 jadwal terdekat</span>
                                            </h3>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="table-responsive">
                                                <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_1">
                                                    <thead>
                                                        <tr class="text-left">
                                                            <th style="min-width: 150px">Periode</th>
                                                            <th style="min-width: 150px">Jenis Bantuan</th>
                                                            <th style="min-width: 150px">Penerima</th>
                                                            <th style="min-width: 150px">Desa</th>
                                                            @if($roleName === 'Admin' || $roleName === 'Distributor')
                                                            <th style="min-width: 100px">Lokasi Survei</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($jadwalPenyaluran as $item)
                                                        <tr>
                                                            <td class="pl-0">
                                                                <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ \Carbon\Carbon::parse($item->periode_bantuan)->format('M Y') }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $item->nama_bantuan }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $item->nama_kepala_keluarga }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="text-dark-75 font-weight-bolder d-block font-size-lg">{{ $item->nama_desa }}</span>
                                                            </td>
                                                            @if($roleName === 'Admin' || $roleName === 'Distributor')
                                                            <td>
                                                                @if($item->survey_latitude && $item->survey_longitude)
                                                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $item->survey_latitude }},{{ $item->survey_longitude }}" target="_blank" class="btn btn-sm btn-icon btn-light-primary"><i class="flaticon2-map text-primary"></i></a>
                                                                @else
                                                                    <span class="text-muted">N/A</span>
                                                                @endif
                                                            </td>
                                                            @endif
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="{{ ($roleName === 'Admin' || $roleName === 'Distributor') ? '5' : '4' }}" class="text-center">Tidak ada jadwal penyaluran terdekat.</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-5">
                                    <div class="card card-custom gutter-b card-stretch" style="height: 500px;">
                                        <div class="card-header border-0 py-5">
                                            <h3 class="card-title align-items-start flex-column">
                                                <span class="card-label font-weight-bolder text-dark">Aktivitas Terbaru</span>
                                                <span class="text-muted mt-3 font-weight-bold font-size-sm">Survei & Penyaluran Terbaru</span>
                                            </h3>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="timeline timeline-3">
                                                @if($roleName === 'Admin' || $roleName === 'Kepala' || $roleName === 'Surveyor')
                                                <div class="timeline-items">
                                                    <div class="timeline-content font-weight-bolder text-muted">Survei Terbaru</div>
                                                </div>
                                                @forelse($latestActivities['survei'] as $activity)
                                                <div class="timeline-item">
                                                    <div class="timeline-media">
                                                        <i class="flaticon-search text-primary"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                                            <div class="mr-2">
                                                                <a href="#" class="text-dark-75 text-hover-primary font-weight-bold">Survei untuk {{ $activity->keluarga->nama_kepala_keluarga ?? 'N/A' }}</a>
                                                                <span class="label label-light-{{ $activity->status_verifikasi === 'terverifikasi' ? 'success' : ($activity->status_verifikasi === 'pending' ? 'warning' : 'danger') }} font-weight-bolder label-inline ml-2">
                                                                    {{ ucfirst(str_replace('_', ' ', $activity->status_verifikasi)) }}
                                                                </span>
                                                            </div>
                                                            <span class="text-muted font-weight-bold">{{ $activity->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <div class="font-weight-bold text-muted">
                                                            Petugas: {{ $activity->petugasSurvei->name ?? 'N/A' }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @empty
                                                <div class="timeline-item">
                                                    <div class="timeline-content">
                                                        <p class="text-muted">Tidak ada aktivitas survei terbaru.</p>
                                                    </div>
                                                </div>
                                                @endforelse
                                                @endif

                                                @if($roleName === 'Admin' || $roleName === 'Kepala' || $roleName === 'Distributor')
                                                <div class="timeline-items mt-5">
                                                    <div class="timeline-content font-weight-bolder text-muted">Penyaluran Bantuan Terbaru</div>
                                                </div>
                                                @forelse($latestActivities['penerima_bantuan'] as $activity)
                                                <div class="timeline-item">
                                                    <div class="timeline-media">
                                                        <i class="flaticon-truck text-success"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                                            <div class="mr-2">
                                                                <a href="#" class="text-dark-75 text-hover-primary font-weight-bold">{{ $activity->jenisBantuan->nama_bantuan ?? 'N/A' }} untuk {{ $activity->keluarga->nama_kepala_keluarga ?? 'N/A' }}</a>
                                                                <span class="label label-light-{{ $activity->status_penerima === 'tersalurkan' ? 'success' : 'warning' }} font-weight-bolder label-inline ml-2">
                                                                    {{ ucfirst(str_replace('_', ' ', $activity->status_penerima)) }}
                                                                </span>
                                                            </div>
                                                            <span class="text-muted font-weight-bold">{{ $activity->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <div class="font-weight-bold text-muted">
                                                            Nominal: Rp {{ number_format($activity->nominal_dihitung ?? 0, 0, ',', '.') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                @empty
                                                <div class="timeline-item">
                                                    <div class="timeline-content">
                                                        <p class="text-muted">Tidak ada aktivitas penyaluran bantuan terbaru.</p>
                                                    </div>
                                                </div>
                                                @endforelse
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12"> 
                                <div class="card card-custom gutter-b">
                                    <div class="card-header border-0 py-5">
                                        <h3 class="card-title align-items-start flex-column">
                                            <span class="card-label font-weight-bolder text-dark">Akses Cepat</span>
                                            <span class="text-muted mt-3 font-weight-bold font-size-sm">Menu navigasi cepat untuk tugas penting</span>
                                        </h3>
                                    </div>
                                    <div class="card-body"> 
                                        <div class="d-flex flex-wrap gap-2">
                                            @canany(['admin', 'surveyor'])
                                            <a href="{{ route('keluargas.create') }}" class="btn btn-primary font-weight-bolder mr-2 mb-2">
                                                <i class="flaticon2-add-outline"></i> Input Survei Baru
                                            </a>
                                            @endcanany
                                            <a href="{{ route('keluargas.index') }}" class="btn btn-info font-weight-bolder mr-2 mb-2">
                                                <i class="flaticon2-group"></i> Lihat Daftar Keluarga
                                            </a>
                                            @canany(['admin', 'surveyor'])
                                            <a href="{{ route('surveis.index', ['status_verifikasi' => 'pending']) }}" class="btn btn-warning font-weight-bolder mr-2 mb-2">
                                                <i class="flaticon2-edit"></i> Verifikasi Survei
                                            </a>
                                            @endcanany
                                            @canany(['admin', 'distributor'])
                                            <a href="{{ route('penerima-bantuan.index', ['status_penerima' => 'belum_tersalurkan']) }}" class="btn btn-danger font-weight-bolder mr-2 mb-2">
                                                <i class="flaticon2-delivery-truck"></i> Salurkan Bantuan
                                            </a>
                                            @endcanany
                                            @can('admin')
                                                <a href="{{ route('users.index') }}" class="btn btn-dark font-weight-bolder mr-2 mb-2">
                                                    <i class="flaticon-users"></i> Kelola Pengguna
                                                </a>
                                                <a href="{{ route('jenis_bantuans.index') }}" class="btn btn-dark font-weight-bolder mr-2 mb-2">
                                                    <i class="flaticon-interface-9"></i> Kelola Jenis Bantuan
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
            Chart.register(ChartDataLabels);
        }

        const roleName = "{{ $roleName }}";

        const ctxSurveiStatus = document.getElementById('surveiStatusChart');
        if (ctxSurveiStatus) {
            let labels, data;
            @if($roleName === 'Surveyor')
                labels = @json($chartsData['surveiStatusLabelsSaya'] ?? []);
                data = @json($chartsData['surveiStatusDataSaya'] ?? []);
            @else
                labels = @json($chartsData['surveiStatusLabels'] ?? []);
                data = @json($chartsData['surveiStatusData'] ?? []);
            @endif

            new Chart(ctxSurveiStatus, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#FFA800',
                            '#1BC5BD',
                            '#F64E60'
                        ],
                        borderColor: [
                            '#FFA800',
                            '#1BC5BD',
                            '#F64E60'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        datalabels: {
                            color: '#fff',
                            formatter: (value, ctx) => {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.map(data => {
                                    sum += data;
                                });
                                let percentage = (value*100 / sum).toFixed(2)+"%";
                                return percentage;
                            }
                        }
                    }
                }
            });
        }

        const ctxPenerima = document.getElementById('penyaluranPenerimaChart');
        if (ctxPenerima) {
            new Chart(ctxPenerima, {
                type: 'bar',
                data: {
                    labels: @json($chartsData['periodeBantuanLabels'] ?? []),
                    datasets: [{
                        label: 'Jumlah Penerima',
                        data: @json($chartsData['jumlahPenerimaData'] ?? []),
                        backgroundColor: 'rgba(84, 110, 204, 0.7)',
                        borderColor: 'rgba(84, 110, 204, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }

        const ctxNominal = document.getElementById('penyaluranNominalChart');
        if (ctxNominal) {
            new Chart(ctxNominal, {
                type: 'line',
                data: {
                    labels: @json($chartsData['periodeBantuanLabels'] ?? []),
                    datasets: [{
                        label: 'Total Nominal (Rp)',
                        data: @json($chartsData['totalNominalData'] ?? []),
                        backgroundColor: 'rgba(0, 185, 230, 0.7)',
                        borderColor: 'rgba(0, 185, 230, 1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }

        const ctxJenisBantuan = document.getElementById('jenisBantuanChart');
        if (ctxJenisBantuan) {
            new Chart(ctxJenisBantuan, {
                type: 'doughnut',
                data: {
                    labels: @json($chartsData['jenisBantuanLabels'] ?? []),
                    datasets: [{
                        data: @json($chartsData['jenisBantuanData'] ?? []),
                        backgroundColor: [
                            '#F64E60',
                            '#36A2EB',
                            '#FFA800'
                        ],
                        borderColor: [
                            '#F64E60',
                            '#36A2EB',
                            '#FFA800'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        datalabels: {
                            color: '#fff',
                            formatter: (value, ctx) => {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.map(data => {
                                    sum += data;
                                });
                                let percentage = (value*100 / sum).toFixed(2)+"%";
                                return percentage;
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush