<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Desa;
use App\Models\User;
use App\Models\Survei;
use App\Models\Keluarga;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Str;
use App\Models\JenisBantuan;
use Illuminate\Http\Request;
use App\Models\PenerimaBantuan;
use App\Models\PenyaluranBantuan;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportController extends Controller
{
    public function showKeluargaReport(Request $request)
    {
        $query = Keluarga::query();

        $query->with('desa');

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $query->where('desa_id', $request->desa_id);
        }

        if ($request->has('status_ekonomi') && $request->status_ekonomi !== null) {
            $query->where('status_ekonomi', $request->status_ekonomi);
        }

        if ($request->has('kondisi_rumah') && $request->kondisi_rumah !== null) {
            $query->where('kondisi_rumah', $request->kondisi_rumah);
        }

        $query->orderBy('nama_kepala_keluarga', 'asc');

        $keluargas = $query->paginate(15);

        $desas = Desa::orderBy('nama_desa')->get();

        $statusEkonomiOptions = [
            'sangat_miskin' => 'Sangat Miskin',
            'miskin'        => 'Miskin',
            'rentan'        => 'Rentan',
            'menengah'      => 'Menengah',
        ];

        $kondisiRumahOptions = [
            'sangat_buruk' => 'Sangat Buruk',
            'buruk'        => 'Buruk',
            'sedang'       => 'Sedang',
            'baik'         => 'Baik',
        ];

        return view('reports.keluarga.index', compact(
            'keluargas',
            'desas',
            'statusEkonomiOptions',
            'kondisiRumahOptions',
            'request' 
        ));
    }

    public function printKeluargaReport(Request $request)
    {
        $query = Keluarga::query();
        $query->with('desa');

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $query->where('desa_id', $request->desa_id);
        }
        if ($request->has('status_ekonomi') && $request->status_ekonomi !== null) {
            $query->where('status_ekonomi', $request->status_ekonomi);
        }
        if ($request->has('kondisi_rumah') && $request->kondisi_rumah !== null) {
            $query->where('kondisi_rumah', $request->kondisi_rumah);
        }

        $keluargas = $query->orderBy('nama_kepala_keluarga', 'asc')->get();

        $statusEkonomiOptions = [
            'sangat_miskin' => 'Sangat Miskin',
            'miskin'        => 'Miskin',
            'rentan'        => 'Rentan',
            'menengah'      => 'Menengah',
        ];

        $kondisiRumahOptions = [
            'sangat_buruk' => 'Sangat Buruk',
            'buruk'        => 'Buruk',
            'sedang'       => 'Sedang',
            'baik'         => 'Baik',
        ];

        $filtersApplied = [];
        if ($request->desa_id) {
            $desa = Desa::find($request->desa_id);
            if ($desa) {
                $filtersApplied[] = 'Desa: ' . $desa->nama_desa;
            }
        }
        if ($request->status_ekonomi) {
            $filtersApplied[] = 'Status Ekonomi: ' . ($statusEkonomiOptions[$request->status_ekonomi] ?? Str::title(str_replace('_', ' ', $request->status_ekonomi)));
        }
        if ($request->kondisi_rumah) {
            $filtersApplied[] = 'Kondisi Rumah: ' . ($kondisiRumahOptions[$request->kondisi_rumah] ?? Str::title(str_replace('_', ' ', $request->kondisi_rumah)));
        }

        $data = [
            'title'          => 'Laporan Data Keluarga',
            'date'           => Carbon::now()->translatedFormat('d F Y H:i:s'),
            'keluargas'      => $keluargas,
            'filtersApplied' => $filtersApplied,
        ];

        $pdf = app('dompdf.wrapper')->loadView('reports.keluarga.pdf', $data);

        $pdf->setPaper('A4', 'landscape'); 

        return $pdf->download('laporan-data-keluarga_' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    public function showSurveyReport(Request $request)
    {
        $query = Survei::query();

        $query->with(['keluarga.desa', 'petugasSurvei', 'diverifikasiOleh']);

        if ($request->has('status_verifikasi') && $request->status_verifikasi !== null) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        if ($request->has('petugas_survei_id') && $request->petugas_survei_id !== null) {
            $query->where('petugas_survei_id', $request->petugas_survei_id);
        }

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $query->whereHas('keluarga', function ($q) use ($request) {
                $q->where('desa_id', $request->desa_id);
            });
        }

        if ($request->has('start_date') && $request->start_date !== null) {
            $query->whereDate('tanggal_survei', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== null) {
            $query->whereDate('tanggal_survei', '<=', $request->end_date);
        }

        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->whereHas('keluarga', function ($q) use ($searchTerm) {
                $q->where('nik_kepala_keluarga', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nama_kepala_keluarga', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy('tanggal_survei', 'desc')
              ->orderBy('created_at', 'desc'); 

        $surveis = $query->paginate(15);

        $statusVerifikasiOptions = [
            'pending'        => 'Pending',
            'terverifikasi'  => 'Terverifikasi',
            'ditolak'        => 'Ditolak',
        ];

        $surveyors = User::whereHas('role', function ($q) {
                        $q->where('name', 'Surveyor'); 
                    })
                    ->orderBy('name')
                    ->get();

        $desas = Desa::orderBy('nama_desa')->get();

        return view('reports.survey.index', compact(
            'surveis',
            'statusVerifikasiOptions',
            'surveyors',
            'desas',
            'request' 
        ));
    }

    public function printSurveyReport(Request $request)
    {
        $query = Survei::query();

        $query->with(['keluarga.desa', 'petugasSurvei', 'diverifikasiOleh']);

        if ($request->has('status_verifikasi') && $request->status_verifikasi !== null) {
            $query->where('status_verifikasi', $request->status_verifikasi);
        }

        if ($request->has('petugas_survei_id') && $request->petugas_survei_id !== null) {
            $query->where('petugas_survei_id', $request->petugas_survei_id);
        }

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $query->whereHas('keluarga', function ($q) use ($request) {
                $q->where('desa_id', $request->desa_id);
            });
        }

        if ($request->has('start_date') && $request->start_date !== null) {
            $query->whereDate('tanggal_survei', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== null) {
            $query->whereDate('tanggal_survei', '<=', $request->end_date);
        }

        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->whereHas('keluarga', function ($q) use ($searchTerm) {
                $q->where('nik_kepala_keluarga', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nama_kepala_keluarga', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy('tanggal_survei', 'desc')
              ->orderBy('created_at', 'desc');

        $surveis = $query->get();

        $filtersApplied = [];

        $statusVerifikasiOptions = [
            'pending'        => 'Pending',
            'terverifikasi'  => 'Terverifikasi',
            'ditolak'        => 'Ditolak',
        ];
        if ($request->status_verifikasi && isset($statusVerifikasiOptions[$request->status_verifikasi])) {
            $filtersApplied[] = 'Status Verifikasi: ' . $statusVerifikasiOptions[$request->status_verifikasi];
        }

        if ($request->petugas_survei_id) {
            $petugas = User::find($request->petugas_survei_id);
            if ($petugas) {
                $filtersApplied[] = 'Petugas Survei: ' . $petugas->name;
            }
        }

        if ($request->desa_id) {
            $desa = Desa::find($request->desa_id);
            if ($desa) {
                $filtersApplied[] = 'Desa: ' . $desa->nama_desa;
            }
        }

        if ($request->start_date && $request->end_date) {
            $filtersApplied[] = 'Periode Survei: ' . Carbon::parse($request->start_date)->format('d M Y') . ' s/d ' . Carbon::parse($request->end_date)->format('d M Y');
        } elseif ($request->start_date) {
            $filtersApplied[] = 'Dari Tanggal Survei: ' . Carbon::parse($request->start_date)->format('d M Y');
        } elseif ($request->end_date) {
            $filtersApplied[] = 'Sampai Tanggal Survei: ' . Carbon::parse($request->end_date)->format('d M Y');
        }
        
        if ($request->search) {
             $filtersApplied[] = 'Pencarian: "' . $request->search . '"';
        }

        $data = [
            'title'          => 'Laporan Hasil Survei',
            'keluargas'      => $surveis, 
            'surveis'        => $surveis, 
            'filtersApplied' => $filtersApplied,
        ];

        $pdf = app('dompdf.wrapper')->loadView('reports.survey.pdf', $data);

        $pdf->setPaper('A4', 'landscape'); 

        return $pdf->download('laporan-hasil-survei_' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    public function showPenerimaBantuanReport(Request $request)
    {
        $query = PenerimaBantuan::query();

        $query->with(['keluarga.desa', 'jenisBantuan', 'ditugaskanKepadaDistributor']);

        if ($request->has('jenis_bantuan_id') && $request->jenis_bantuan_id !== null) {
            $query->where('jenis_bantuan_id', $request->jenis_bantuan_id);
        }

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $query->whereHas('keluarga', function ($q) use ($request) {
                $q->where('desa_id', $request->desa_id);
            });
        }

        if ($request->has('periode_bantuan') && $request->periode_bantuan !== null) {
            $query->where('periode_bantuan', $request->periode_bantuan);
        }

        if ($request->has('status_penerima') && $request->status_penerima !== null) {
            $query->where('status_penerima', $request->status_penerima);
        }

        if ($request->has('ditugaskan_kepada_distributor_id') && $request->ditugaskan_kepada_distributor_id !== null) {
            $query->where('ditugaskan_kepada_distributor_id', $request->ditugaskan_kepada_distributor_id);
        }
        
        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->whereHas('keluarga', function ($q) use ($searchTerm) {
                $q->where('nik_kepala_keluarga', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nama_kepala_keluarga', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy('periode_bantuan', 'desc')
              ->orderBy('created_at', 'desc'); 

        $penerimaBantuans = $query->paginate(15);

        $jenisBantuans = JenisBantuan::orderBy('nama_bantuan')->get();

        $desas = Desa::orderBy('nama_desa')->get();

        $periods = [];
        for ($i = -12; $i <= 12; $i++) {
            $date = Carbon::now()->addMonths($i)->startOfMonth();
            $periods[$date->toDateString()] = $date->translatedFormat('F Y'); 
        }

        $statusPenerimaOptions = [
            'belum_tersalurkan' => 'Belum Tersalurkan',
            'tersalurkan'       => 'Tersalurkan',
        ];

        $distributors = User::whereHas('role', function ($q) {
                            $q->where('name', 'Distributor'); 
                        })
                        ->orderBy('name')
                        ->get();

        return view('reports.penerima_bantuan.index', compact(
            'penerimaBantuans',
            'jenisBantuans',
            'desas',
            'periods',
            'statusPenerimaOptions',
            'distributors',
            'request' 
        ));
    }

    public function printPenerimaBantuanReport(Request $request)
    {
        $query = PenerimaBantuan::query();

        $query->with(['keluarga.desa', 'jenisBantuan', 'ditugaskanKepadaDistributor']);

        if ($request->has('jenis_bantuan_id') && $request->jenis_bantuan_id !== null) {
            $query->where('jenis_bantuan_id', $request->jenis_bantuan_id);
        }

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $query->whereHas('keluarga', function ($q) use ($request) {
                $q->where('desa_id', $request->desa_id);
            });
        }

        if ($request->has('periode_bantuan') && $request->periode_bantuan !== null) {
            $query->where('periode_bantuan', $request->periode_bantuan);
        }

        if ($request->has('status_penerima') && $request->status_penerima !== null) {
            $query->where('status_penerima', $request->status_penerima);
        }

        if ($request->has('ditugaskan_kepada_distributor_id') && $request->ditugaskan_kepada_distributor_id !== null) {
            $query->where('ditugaskan_kepada_distributor_id', $request->ditugaskan_kepada_distributor_id);
        }
        
        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->whereHas('keluarga', function ($q) use ($searchTerm) {
                $q->where('nik_kepala_keluarga', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nama_kepala_keluarga', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy('periode_bantuan', 'desc')
              ->orderBy('created_at', 'desc');

        $penerimaBantuans = $query->get();

        $filtersApplied = [];

        if ($request->jenis_bantuan_id) {
            $jenisBantuan = JenisBantuan::find($request->jenis_bantuan_id);
            if ($jenisBantuan) {
                $filtersApplied[] = 'Jenis Bantuan: ' . $jenisBantuan->nama_bantuan;
            }
        }

        if ($request->desa_id) {
            $desa = Desa::find($request->desa_id);
            if ($desa) {
                $filtersApplied[] = 'Desa: ' . $desa->nama_desa;
            }
        }

        if ($request->periode_bantuan) {
            $filtersApplied[] = 'Periode Bantuan: ' . Carbon::parse($request->periode_bantuan)->translatedFormat('F Y');
        }

        $statusPenerimaOptions = [
            'belum_tersalurkan' => 'Belum Tersalurkan',
            'tersalurkan'       => 'Tersalurkan',
        ];
        if ($request->status_penerima && isset($statusPenerimaOptions[$request->status_penerima])) {
            $filtersApplied[] = 'Status Penerima: ' . $statusPenerimaOptions[$request->status_penerima];
        }

        if ($request->ditugaskan_kepada_distributor_id) {
            $distributor = User::find($request->ditugaskan_kepada_distributor_id);
            if ($distributor) {
                $filtersApplied[] = 'Petugas Ditugaskan: ' . $distributor->name;
            }
        }

        if ($request->search) {
            $filtersApplied[] = 'Pencarian: "' . $request->search . '"';
        }

        $data = [
            'title'          => 'Laporan Penerima Bantuan',
            'penerimaBantuans' => $penerimaBantuans,
            'filtersApplied' => $filtersApplied,
        ];

        $pdf = app('dompdf.wrapper')->loadView('reports.penerima_bantuan.pdf', $data);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('laporan-penerima-bantuan_' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    public function showPenyaluranBantuanReport(Request $request)
    {
        $query = PenyaluranBantuan::query();

        $query->with([
            'penerimaBantuan.keluarga.desa',
            'penerimaBantuan.jenisBantuan',
            'petugasPenyalur'
        ]);

        if ($request->has('start_date') && $request->start_date !== null) {
            $query->whereDate('tanggal_penyaluran', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== null) {
            $query->whereDate('tanggal_penyaluran', '<=', $request->end_date);
        }

        if ($request->has('jenis_bantuan_id') && $request->jenis_bantuan_id !== null) {
            $query->whereHas('penerimaBantuan', function ($q) use ($request) {
                $q->where('jenis_bantuan_id', $request->jenis_bantuan_id);
            });
        }

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $query->whereHas('penerimaBantuan.keluarga', function ($q) use ($request) {
                $q->where('desa_id', $request->desa_id);
            });
        }

        if ($request->has('periode_bantuan') && $request->periode_bantuan !== null) {
            $query->whereHas('penerimaBantuan', function ($q) use ($request) {
                $q->where('periode_bantuan', $request->periode_bantuan);
            });
        }

        if ($request->has('status_setelah_penyaluran') && $request->status_setelah_penyaluran !== null) {
            $query->where('status_setelah_penyaluran', $request->status_setelah_penyaluran);
        }

        if ($request->has('petugas_penyalur_id') && $request->petugas_penyalur_id !== null) {
            $query->where('petugas_penyalur_id', $request->petugas_penyalur_id);
        }

        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->whereHas('penerimaBantuan.keluarga', function ($q) use ($searchTerm) {
                $q->where('nik_kepala_keluarga', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nama_kepala_keluarga', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy('tanggal_penyaluran', 'desc')
              ->orderBy('created_at', 'desc'); 

        $penyaluranBantuans = $query->paginate(15);

        $jenisBantuans = JenisBantuan::orderBy('nama_bantuan')->get();

        $desas = Desa::orderBy('nama_desa')->get();

        $periodeBantuanOptions = \App\Models\PenerimaBantuan::selectRaw('DISTINCT periode_bantuan')
                                ->orderBy('periode_bantuan', 'desc')
                                ->get()
                                ->mapWithKeys(function ($item) {
                                    return [$item->periode_bantuan->format('Y-m-d') => $item->periode_bantuan->translatedFormat('F Y')];
                                })
                                ->toArray();

        $statusPenyaluranOptions = [
            'berhasil_disalurkan' => 'Berhasil Disalurkan',
            'tidak_ditemukan'     => 'Tidak Ditemukan',
            'menolak'             => 'Menolak',
        ];

        $petugasPenyalur = User::whereHas('role', function ($q) {
                                $q->whereIn('name', ['Distributor']); 
                            })
                            ->orderBy('name')
                            ->get();

        return view('reports.penyaluran_bantuan.index', compact(
            'penyaluranBantuans',
            'jenisBantuans',
            'desas',
            'periodeBantuanOptions',
            'statusPenyaluranOptions',
            'petugasPenyalur',
            'request' 
        ));
    }

    public function printPenyaluranBantuanReport(Request $request)
    {
        $query = PenyaluranBantuan::query();

        $query->with([
            'penerimaBantuan.keluarga.desa',
            'penerimaBantuan.jenisBantuan',
            'petugasPenyalur'
        ]);

        if ($request->has('start_date') && $request->start_date !== null) {
            $query->whereDate('tanggal_penyaluran', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== null) {
            $query->whereDate('tanggal_penyaluran', '<=', $request->end_date);
        }

        if ($request->has('jenis_bantuan_id') && $request->jenis_bantuan_id !== null) {
            $query->whereHas('penerimaBantuan', function ($q) use ($request) {
                $q->where('jenis_bantuan_id', $request->jenis_bantuan_id);
            });
        }

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $query->whereHas('penerimaBantuan.keluarga', function ($q) use ($request) {
                $q->where('desa_id', $request->desa_id);
            });
        }

        if ($request->has('periode_bantuan') && $request->periode_bantuan !== null) {
            $query->whereHas('penerimaBantuan', function ($q) use ($request) {
                $q->where('periode_bantuan', $request->periode_bantuan);
            });
        }

        if ($request->has('status_setelah_penyaluran') && $request->status_setelah_penyaluran !== null) {
            $query->where('status_setelah_penyaluran', $request->status_setelah_penyaluran);
        }

        if ($request->has('petugas_penyalur_id') && $request->petugas_penyalur_id !== null) {
            $query->where('petugas_penyalur_id', $request->petugas_penyalur_id);
        }

        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->whereHas('penerimaBantuan.keluarga', function ($q) use ($searchTerm) {
                $q->where('nik_kepala_keluarga', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nama_kepala_keluarga', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy('tanggal_penyaluran', 'desc')
              ->orderBy('created_at', 'desc');

        $penyaluranBantuans = $query->get();

        $filtersApplied = [];

        if ($request->start_date && $request->end_date) {
            $filtersApplied[] = 'Periode Penyaluran: ' . Carbon::parse($request->start_date)->format('d M Y') . ' s/d ' . Carbon::parse($request->end_date)->format('d M Y');
        } elseif ($request->start_date) {
            $filtersApplied[] = 'Dari Tanggal Penyaluran: ' . Carbon::parse($request->start_date)->format('d M Y');
        } elseif ($request->end_date) {
            $filtersApplied[] = 'Sampai Tanggal Penyaluran: ' . Carbon::parse($request->end_date)->format('d M Y');
        }

        if ($request->jenis_bantuan_id) {
            $jenisBantuan = JenisBantuan::find($request->jenis_bantuan_id);
            if ($jenisBantuan) {
                $filtersApplied[] = 'Jenis Bantuan: ' . $jenisBantuan->nama_bantuan;
            }
        }

        if ($request->desa_id) {
            $desa = Desa::find($request->desa_id);
            if ($desa) {
                $filtersApplied[] = 'Desa: ' . $desa->nama_desa;
            }
        }

        if ($request->periode_bantuan) {
            $filtersApplied[] = 'Periode Bantuan: ' . Carbon::parse($request->periode_bantuan)->translatedFormat('F Y');
        }

        $statusPenyaluranOptions = [
            'berhasil_disalurkan' => 'Berhasil Disalurkan',
            'tidak_ditemukan'     => 'Tidak Ditemukan',
            'menolak'             => 'Menolak',
        ];
        if ($request->status_setelah_penyaluran && isset($statusPenyaluranOptions[$request->status_setelah_penyaluran])) {
            $filtersApplied[] = 'Status Penyaluran: ' . $statusPenyaluranOptions[$request->status_setelah_penyaluran];
        }

        if ($request->petugas_penyalur_id) {
            $petugas = User::find($request->petugas_penyalur_id);
            if ($petugas) {
                $filtersApplied[] = 'Petugas Penyalur: ' . $petugas->name;
            }
        }

        if ($request->search) {
            $filtersApplied[] = 'Pencarian: "' . $request->search . '"';
        }

        $data = [
            'title'              => 'Laporan Penyaluran Bantuan',
            'penyaluranBantuans' => $penyaluranBantuans,
            'filtersApplied'     => $filtersApplied,
        ];

        $pdf = app('dompdf.wrapper')->loadView('reports.penyaluran_bantuan.pdf', $data);

        $pdf->setPaper('A4', 'landscape'); 

        return $pdf->download('laporan-penyaluran-bantuan_' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    public function showTotalPenyaluranPerDesaReport(Request $request)
    {
        $baseQuery = PenerimaBantuan::query()
            ->where('status_penerima', 'tersalurkan')
            ->with([
                'keluarga.desa',
                'jenisBantuan'
            ]);

        if ($request->has('jenis_bantuan_id') && $request->jenis_bantuan_id !== null) {
            $baseQuery->where('jenis_bantuan_id', $request->jenis_bantuan_id);
        }

        if ($request->has('periode_bantuan') && $request->periode_bantuan !== null) {
            $baseQuery->where('periode_bantuan', $request->periode_bantuan);
        }

        $disbursements = $baseQuery->get([
            'id',
            'nominal_dihitung',
            'keluarga_id',
            'jenis_bantuan_id'
        ]);
        
        $reportData = collect();
        $allJenisBantuanForColumns = collect();

        foreach ($disbursements as $penerimaBantuan) {
            $desa = $penerimaBantuan->keluarga->desa;
            $jenisBantuan = $penerimaBantuan->jenisBantuan;
            $nominal = $penerimaBantuan->nominal_dihitung;

            if (!$desa || !$jenisBantuan) {
                continue;
            }

            $desaId = $desa->id;
            $desaName = $desa->nama_desa;
            $jenisBantuanName = $jenisBantuan->nama_bantuan;

            $allJenisBantuanForColumns->push($jenisBantuanName);

            if (!$reportData->has($desaId)) {
                $reportData->put($desaId, [
                    'desa_id'                 => $desaId,
                    'nama_desa'               => $desaName,
                    'total_penerima_overall'  => 0,
                    'total_nominal_overall'   => 0,
                    'jenis_bantuan_counts'    => [],
                ]);
            }

            $desaEntry = $reportData->get($desaId);
            $desaEntry['total_penerima_overall']++;
            $desaEntry['total_nominal_overall'] += $nominal;

            if (!isset($desaEntry['jenis_bantuan_counts'][$jenisBantuanName])) {
                $desaEntry['jenis_bantuan_counts'][$jenisBantuanName] = 0;
            }
            $desaEntry['jenis_bantuan_counts'][$jenisBantuanName]++;

            $reportData->put($desaId, $desaEntry);
        }

        $reportData = $reportData->sortBy('nama_desa')->values();

        $allJenisBantuanForColumns = $allJenisBantuanForColumns->unique()->sort()->values();

        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageReportData = $reportData->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $paginatedReportData = new LengthAwarePaginator(
            $currentPageReportData,
            $reportData->count(),
            $perPage,
            $currentPage,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        $jenisBantuans = JenisBantuan::orderBy('nama_bantuan')->get();
        $desas = Desa::orderBy('nama_desa')->get();
        
        $periodeBantuanOptions = PenerimaBantuan::selectRaw('DISTINCT periode_bantuan')
                                ->orderBy('periode_bantuan', 'desc')
                                ->get()
                                ->mapWithKeys(function ($item) {
                                    return [$item->periode_bantuan->format('Y-m-d') => $item->periode_bantuan->translatedFormat('F Y')];
                                })
                                ->toArray();

        return view('reports.total_penyaluran_per_desa.index', compact(
            'paginatedReportData',
            'allJenisBantuanForColumns',
            'jenisBantuans',
            'desas',
            'periodeBantuanOptions',
            'request'
        ));
    }

    public function printTotalPenyaluranPerDesaReport(Request $request)
    {
        $baseQuery = PenerimaBantuan::query()
            ->where('status_penerima', 'tersalurkan')
            ->with([
                'keluarga.desa',
                'jenisBantuan'
            ]);

        if ($request->has('jenis_bantuan_id') && $request->jenis_bantuan_id !== null) {
            $baseQuery->where('jenis_bantuan_id', $request->jenis_bantuan_id);
        }

        if ($request->has('periode_bantuan') && $request->periode_bantuan !== null) {
            $baseQuery->where('periode_bantuan', $request->periode_bantuan);
        }

        $disbursements = $baseQuery->get([
            'id',
            'nominal_dihitung',
            'keluarga_id',
            'jenis_bantuan_id'
        ]);
        
        $reportData = collect();
        $allJenisBantuanForColumns = collect();

        foreach ($disbursements as $penerimaBantuan) {
            $desa = $penerimaBantuan->keluarga->desa;
            $jenisBantuan = $penerimaBantuan->jenisBantuan;
            $nominal = $penerimaBantuan->nominal_dihitung;

            if (!$desa || !$jenisBantuan) {
                continue;
            }

            $desaId = $desa->id;
            $desaName = $desa->nama_desa;
            $jenisBantuanName = $jenisBantuan->nama_bantuan;

            $allJenisBantuanForColumns->push($jenisBantuanName);

            if (!$reportData->has($desaId)) {
                $reportData->put($desaId, [
                    'desa_id'                 => $desaId,
                    'nama_desa'               => $desaName,
                    'total_penerima_overall'  => 0,
                    'total_nominal_overall'   => 0,
                    'jenis_bantuan_counts'    => [],
                ]);
            }

            $desaEntry = $reportData->get($desaId);
            $desaEntry['total_penerima_overall']++;
            $desaEntry['total_nominal_overall'] += $nominal;

            if (!isset($desaEntry['jenis_bantuan_counts'][$jenisBantuanName])) {
                $desaEntry['jenis_bantuan_counts'][$jenisBantuanName] = 0;
            }
            $desaEntry['jenis_bantuan_counts'][$jenisBantuanName]++;

            $reportData->put($desaId, $desaEntry);
        }

        $reportData = $reportData->sortBy('nama_desa')->values();
        $allJenisBantuanForColumns = $allJenisBantuanForColumns->unique()->sort()->values();

        $filtersApplied = [];

        if ($request->jenis_bantuan_id) {
            $jenisBantuan = JenisBantuan::find($request->jenis_bantuan_id);
            if ($jenisBantuan) {
                $filtersApplied[] = 'Jenis Bantuan: ' . $jenisBantuan->nama_bantuan;
            }
        }

        if ($request->periode_bantuan) {
            $filtersApplied[] = 'Periode Bantuan (Alokasi): ' . Carbon::parse($request->periode_bantuan)->translatedFormat('F Y');
        }

        $data = [
            'title'                     => 'Laporan Total Penyaluran per Desa',
            'reportData'                => $reportData,
            'allJenisBantuanForColumns' => $allJenisBantuanForColumns,
            'filtersApplied'            => $filtersApplied,
        ];

        $pdf = app('dompdf.wrapper')->loadView('reports.total_penyaluran_per_desa.pdf', $data);

        $pdf->setPaper('A4', 'landscape'); 

        return $pdf->download('laporan-total-penyaluran-per-desa_' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    public function showJadwalPenyaluranReport(Request $request)
    {
        $query = PenerimaBantuan::query();

        $query->where('penerima_bantuan.status_penerima', 'belum_tersalurkan');

        $query->join('keluarga', 'penerima_bantuan.keluarga_id', '=', 'keluarga.id')
              ->join('desa', 'keluarga.desa_id', '=', 'desa.id') // Perhatikan 'desas' (plural)
              ->join('jenis_bantuan', 'penerima_bantuan.jenis_bantuan_id', '=', 'jenis_bantuan.id');

        // Pastikan untuk eager load relasi yang masih dibutuhkan di view
        $query->with([
            'ditugaskanKepadaDistributor' // Relasi ke User/Distributor
        ]);


        if ($request->has('periode_bantuan') && $request->periode_bantuan !== null) {
            $query->where('penerima_bantuan.periode_bantuan', $request->periode_bantuan);
        }

        if ($request->has('jenis_bantuan_id') && $request->jenis_bantuan_id !== null) {
            $query->where('penerima_bantuan.jenis_bantuan_id', $request->jenis_bantuan_id);
        }

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $query->where('keluarga.desa_id', $request->desa_id);
        }

        if ($request->has('ditugaskan_kepada_distributor_id') && $request->ditugaskan_kepada_distributor_id !== null) {
            $query->where('penerima_bantuan.ditugaskan_kepada_distributor_id', $request->ditugaskan_kepada_distributor_id);
        }

        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('keluarga.nik_kepala_keluarga', 'like', '%' . $searchTerm . '%')
                  ->orWhere('keluarga.nama_kepala_keluarga', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy('penerima_bantuan.periode_bantuan', 'asc')
              ->orderBy('desa.nama_desa', 'asc')
              ->orderBy('jenis_bantuan.nama_bantuan', 'asc')
              ->select('penerima_bantuan.*'); 

        $perPage = 15;
        $jadwalPenyalurans = $query->paginate($perPage);

        $jenisBantuans = JenisBantuan::orderBy('nama_bantuan')->get();
        $desas = Desa::orderBy('nama_desa')->get();
        $users = User::orderBy('name')->get();

        $periodeBantuanOptions = PenerimaBantuan::selectRaw('DISTINCT periode_bantuan')
                                ->orderBy('periode_bantuan', 'asc')
                                ->get()
                                ->mapWithKeys(function ($item) {
                                    return [$item->periode_bantuan->format('Y-m-d') => $item->periode_bantuan->translatedFormat('F Y')];
                                })
                                ->toArray();

        return view('reports.jadwal_penyaluran.index', compact(
            'jadwalPenyalurans',
            'jenisBantuans',
            'desas',
            'users',
            'periodeBantuanOptions',
            'request'
        ));
    }

    public function printJadwalPenyaluranReport(Request $request)
    {
        $query = PenerimaBantuan::query();

        $query->where('penerima_bantuan.status_penerima', 'belum_tersalurkan');

        $query->join('keluarga', 'penerima_bantuan.keluarga_id', '=', 'keluarga.id')
              ->join('desa', 'keluarga.desa_id', '=', 'desa.id')
              ->join('jenis_bantuan', 'penerima_bantuan.jenis_bantuan_id', '=', 'jenis_bantuan.id');

        $query->with([
            'ditugaskanKepadaDistributor'
        ]);

        if ($request->has('periode_bantuan') && $request->periode_bantuan !== null) {
            $query->where('penerima_bantuan.periode_bantuan', $request->periode_bantuan);
        }

        if ($request->has('jenis_bantuan_id') && $request->jenis_bantuan_id !== null) {
            $query->where('penerima_bantuan.jenis_bantuan_id', $request->jenis_bantuan_id);
        }

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $query->where('keluarga.desa_id', $request->desa_id);
        }

        if ($request->has('ditugaskan_kepada_distributor_id') && $request->ditugaskan_kepada_distributor_id !== null) {
            $query->where('penerima_bantuan.ditugaskan_kepada_distributor_id', $request->ditugaskan_kepada_distributor_id);
        }

        if ($request->has('search') && $request->search !== null) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('keluarga.nik_kepala_keluarga', 'like', '%' . $searchTerm . '%')
                  ->orWhere('keluarga.nama_kepala_keluarga', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->orderBy('penerima_bantuan.periode_bantuan', 'asc')
              ->orderBy('desa.nama_desa', 'asc')
              ->orderBy('jenis_bantuan.nama_bantuan', 'asc')
              ->select('penerima_bantuan.*');

        $jadwalPenyalurans = $query->get(); // Gunakan get() bukan paginate() untuk PDF

        $filtersApplied = [];

        if ($request->periode_bantuan) {
            $filtersApplied[] = 'Periode Bantuan: ' . Carbon::parse($request->periode_bantuan)->translatedFormat('F Y');
        }

        if ($request->jenis_bantuan_id) {
            $jenisBantuan = JenisBantuan::find($request->jenis_bantuan_id);
            if ($jenisBantuan) {
                $filtersApplied[] = 'Jenis Bantuan: ' . $jenisBantuan->nama_bantuan;
            }
        }

        if ($request->desa_id) {
            $desa = Desa::find($request->desa_id);
            if ($desa) {
                $filtersApplied[] = 'Desa: ' . $desa->nama_desa;
            }
        }

        if ($request->ditugaskan_kepada_distributor_id) {
            $user = User::find($request->ditugaskan_kepada_distributor_id);
            if ($user) {
                $filtersApplied[] = 'Petugas Ditugaskan: ' . $user->name;
            }
        }

        if ($request->search) {
            $filtersApplied[] = 'Pencarian (NIK/Nama KK): ' . $request->search;
        }

        if (empty($filtersApplied)) {
            $filtersApplied[] = 'Tidak ada filter diterapkan. Menampilkan semua data belum tersalurkan.';
        }


        $data = [
            'title'                     => 'Laporan Jadwal Penyaluran',
            'jadwalPenyalurans'         => $jadwalPenyalurans,
            'filtersApplied'            => $filtersApplied,
        ];

        $pdf = app('dompdf.wrapper')->loadView('reports.jadwal_penyaluran.pdf', $data);

        $pdf->setPaper('A4', 'portrait'); 

        return $pdf->download('laporan-jadwal-penyaluran_' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    public function showAnalisisNominalReport(Request $request)
    {
        $baseQuery = PenerimaBantuan::query()
            ->where('penerima_bantuan.status_penerima', 'tersalurkan')
            ->join('keluarga', 'penerima_bantuan.keluarga_id', '=', 'keluarga.id')
            ->join('desa', 'keluarga.desa_id', '=', 'desa.id')
            ->join('jenis_bantuan', 'penerima_bantuan.jenis_bantuan_id', '=', 'jenis_bantuan.id');

        if ($request->has('periode_bantuan') && $request->periode_bantuan !== null) {
            $baseQuery->where('penerima_bantuan.periode_bantuan', $request->periode_bantuan);
        }

        if ($request->has('jenis_bantuan_id') && $request->jenis_bantuan_id !== null) {
            $baseQuery->where('penerima_bantuan.jenis_bantuan_id', $request->jenis_bantuan_id);
        }

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $baseQuery->where('keluarga.desa_id', $request->desa_id);
        }

        if ($request->has('min_nominal') && $request->min_nominal !== null) {
            $minNominal = (float)str_replace(['.', ','], ['', '.'], $request->min_nominal);
            $baseQuery->where('penerima_bantuan.nominal_dihitung', '>=', $minNominal);
        }

        if ($request->has('max_nominal') && $request->max_nominal !== null) {
            $maxNominal = (float)str_replace(['.', ','], ['', '.'], $request->max_nominal);
            $baseQuery->where('penerima_bantuan.nominal_dihitung', '<=', $maxNominal);
        }

        // --- Statistik Keseluruhan ---
        $overallStats = $baseQuery->clone()
                                ->select([]) // Kosongkan SELECT sebelumnya
                                ->selectRaw('COUNT(penerima_bantuan.id) as total_penerima, SUM(penerima_bantuan.nominal_dihitung) as total_nominal')
                                ->first();

        $totalPenerimaOverall = $overallStats->total_penerima ?? 0;
        $totalNominalOverall = $overallStats->total_nominal ?? 0;
        $avgNominalOverall = $totalPenerimaOverall > 0 ? $totalNominalOverall / $totalPenerimaOverall : 0;


        // --- Agregasi per Jenis Bantuan ---
        $analysisByJenisBantuan = $baseQuery->clone()
            ->select([]) // Kosongkan SELECT sebelumnya
            ->groupBy('jenis_bantuan.id', 'jenis_bantuan.nama_bantuan')
            ->selectRaw('jenis_bantuan.nama_bantuan, COUNT(penerima_bantuan.id) as count, SUM(penerima_bantuan.nominal_dihitung) as sum')
            ->orderBy('jenis_bantuan.nama_bantuan', 'asc')
            ->get();

        // --- Agregasi per Desa ---
        $analysisByDesa = $baseQuery->clone()
            ->select([]) // Kosongkan SELECT sebelumnya
            ->groupBy('desa.id', 'desa.nama_desa')
            ->selectRaw('desa.nama_desa, COUNT(penerima_bantuan.id) as count, SUM(penerima_bantuan.nominal_dihitung) as sum')
            ->orderBy('desa.nama_desa', 'asc')
            ->get();

        // Data untuk dropdown filter
        $jenisBantuans = JenisBantuan::orderBy('nama_bantuan')->get();
        $desas = Desa::orderBy('nama_desa')->get();
        $periodeBantuanOptions = PenerimaBantuan::selectRaw('DISTINCT periode_bantuan')
                                ->orderBy('periode_bantuan', 'desc')
                                ->get()
                                ->mapWithKeys(function ($item) {
                                    return [$item->periode_bantuan->format('Y-m-d') => $item->periode_bantuan->translatedFormat('F Y')];
                                })
                                ->toArray();
        
        return view('reports.analisis_nominal.index', compact(
            'totalPenerimaOverall',
            'totalNominalOverall',
            'avgNominalOverall',
            'analysisByJenisBantuan',
            'analysisByDesa',
            'jenisBantuans',
            'desas',
            'periodeBantuanOptions',
            'request'
        ));
    }

    public function printAnalisisNominalReport(Request $request)
    {
        $baseQuery = PenerimaBantuan::query()
            ->where('penerima_bantuan.status_penerima', 'tersalurkan')
            ->join('keluarga', 'penerima_bantuan.keluarga_id', '=', 'keluarga.id')
            ->join('desa', 'keluarga.desa_id', '=', 'desa.id')
            ->join('jenis_bantuan', 'penerima_bantuan.jenis_bantuan_id', '=', 'jenis_bantuan.id');

        if ($request->has('periode_bantuan') && $request->periode_bantuan !== null) {
            $baseQuery->where('penerima_bantuan.periode_bantuan', $request->periode_bantuan);
        }

        if ($request->has('jenis_bantuan_id') && $request->jenis_bantuan_id !== null) {
            $baseQuery->where('penerima_bantuan.jenis_bantuan_id', $request->jenis_bantuan_id);
        }

        if ($request->has('desa_id') && $request->desa_id !== null) {
            $baseQuery->where('keluarga.desa_id', $request->desa_id);
        }

        if ($request->has('min_nominal') && $request->min_nominal !== null) {
            $minNominal = (float)str_replace(['.', ','], ['', '.'], $request->min_nominal);
            $baseQuery->where('penerima_bantuan.nominal_dihitung', '>=', $minNominal);
        }

        if ($request->has('max_nominal') && $request->max_nominal !== null) {
            $maxNominal = (float)str_replace(['.', ','], ['', '.'], $request->max_nominal);
            $baseQuery->where('penerima_bantuan.nominal_dihitung', '<=', $maxNominal);
        }

        // --- Statistik Keseluruhan ---
        $overallStats = $baseQuery->clone()
                                ->select([])
                                ->selectRaw('COUNT(penerima_bantuan.id) as total_penerima, SUM(penerima_bantuan.nominal_dihitung) as total_nominal')
                                ->first();

        $totalPenerimaOverall = $overallStats->total_penerima ?? 0;
        $totalNominalOverall = $overallStats->total_nominal ?? 0;
        $avgNominalOverall = $totalPenerimaOverall > 0 ? $totalNominalOverall / $totalPenerimaOverall : 0;


        // --- Agregasi per Jenis Bantuan ---
        $analysisByJenisBantuan = $baseQuery->clone()
            ->select([])
            ->groupBy('jenis_bantuan.id', 'jenis_bantuan.nama_bantuan')
            ->selectRaw('jenis_bantuan.nama_bantuan, COUNT(penerima_bantuan.id) as count, SUM(penerima_bantuan.nominal_dihitung) as sum')
            ->orderBy('jenis_bantuan.nama_bantuan', 'asc')
            ->get();

        // --- Agregasi per Desa ---
        $analysisByDesa = $baseQuery->clone()
            ->select([])
            ->groupBy('desa.id', 'desa.nama_desa')
            ->selectRaw('desa.nama_desa, COUNT(penerima_bantuan.id) as count, SUM(penerima_bantuan.nominal_dihitung) as sum')
            ->orderBy('desa.nama_desa', 'asc')
            ->get();

        // Siapkan Filters Applied untuk ditampilkan di PDF
        $filtersApplied = [];

        if ($request->periode_bantuan) {
            $filtersApplied[] = 'Periode Bantuan: ' . Carbon::parse($request->periode_bantuan)->translatedFormat('F Y');
        }

        if ($request->jenis_bantuan_id) {
            $jenisBantuan = JenisBantuan::find($request->jenis_bantuan_id);
            if ($jenisBantuan) {
                $filtersApplied[] = 'Jenis Bantuan: ' . $jenisBantuan->nama_bantuan;
            }
        }

        if ($request->desa_id) {
            $desa = Desa::find($request->desa_id);
            if ($desa) {
                $filtersApplied[] = 'Desa: ' . $desa->nama_desa;
            }
        }

        if ($request->min_nominal && $request->max_nominal) {
            $filtersApplied[] = 'Nominal: Rp. ' . number_format($minNominal, 0, ',', '.') . ' s/d Rp. ' . number_format($maxNominal, 0, ',', '.');
        } elseif ($request->min_nominal) {
            $filtersApplied[] = 'Nominal Minimal: Rp. ' . number_format($minNominal, 0, ',', '.');
        } elseif ($request->max_nominal) {
            $filtersApplied[] = 'Nominal Maksimal: Rp. ' . number_format($maxNominal, 0, ',', '.');
        }

        if (empty($filtersApplied)) {
            $filtersApplied[] = 'Tidak ada filter diterapkan. Menampilkan semua data tersalurkan.';
        }


        $data = [
            'title'                     => 'Laporan Analisis Nominal Bantuan Tersalurkan',
            'totalPenerimaOverall'      => $totalPenerimaOverall,
            'totalNominalOverall'       => $totalNominalOverall,
            'avgNominalOverall'         => $avgNominalOverall,
            'analysisByJenisBantuan'    => $analysisByJenisBantuan,
            'analysisByDesa'            => $analysisByDesa,
            'filtersApplied'            => $filtersApplied,
        ];

        $pdf = app('dompdf.wrapper')->loadView('reports.analisis_nominal.pdf', $data);

        // Atur kertas ke A4 dan orientasi landscape
        $pdf->setPaper('A4', 'landscape'); 

        return $pdf->download('laporan-analisis-nominal-bantuan_' . Carbon::now()->format('YmdHis') . '.pdf');
    }

    public function showDetailDataKeluargaReport(Request $request)
    {
        $keluargasQuery = Keluarga::query()
            ->with(['desa', 'anggotaKeluarga']); // Eager load relasi

        // Terapkan filter
        if ($request->has('desa_id') && $request->desa_id !== null) {
            $keluargasQuery->where('desa_id', $request->desa_id);
        }

        if ($request->has('nik_kepala_keluarga') && $request->nik_kepala_keluarga !== null) {
            $keluargasQuery->where('nik_kepala_keluarga', 'like', '%' . $request->nik_kepala_keluarga . '%');
        }

        if ($request->has('nama_kepala_keluarga') && $request->nama_kepala_keluarga !== null) {
            $keluargasQuery->where('nama_kepala_keluarga', 'like', '%' . $request->nama_kepala_keluarga . '%');
        }

        if ($request->has('pekerjaan') && $request->pekerjaan !== null) {
            $keluargasQuery->where('pekerjaan', 'like', '%' . $request->pekerjaan . '%');
        }

        if ($request->has('status_ekonomi') && $request->status_ekonomi !== null) {
            $keluargasQuery->where('status_ekonomi', $request->status_ekonomi);
        }

        if ($request->has('kondisi_rumah') && $request->kondisi_rumah !== null) {
            $keluargasQuery->where('kondisi_rumah', $request->kondisi_rumah);
        }

        // Ambil data dengan paginasi
        $keluargas = $keluargasQuery->orderBy('nama_kepala_keluarga', 'asc')->paginate(10); // Sesuaikan jumlah item per halaman

        // Data untuk dropdown filter
        $desas = Desa::orderBy('nama_desa')->get();
        
        // Options untuk enum status_ekonomi dan kondisi_rumah
        $statusEkonomiOptions = [
            ['value' => 'sangat_miskin', 'label' => 'Sangat Miskin'],
            ['value' => 'miskin', 'label' => 'Miskin'],
            ['value' => 'rentan', 'label' => 'Rentan'],
            ['value' => 'menengah', 'label' => 'Menengah'],
        ];

        $kondisiRumahOptions = [
            ['value' => 'sangat_buruk', 'label' => 'Sangat Buruk'],
            ['value' => 'buruk', 'label' => 'Buruk'],
            ['value' => 'sedang', 'label' => 'Sedang'],
            ['value' => 'baik', 'label' => 'Baik'],
        ];

        return view('reports.detail_data_keluarga.index', compact(
            'keluargas',
            'desas',
            'statusEkonomiOptions',
            'kondisiRumahOptions',
            'request'
        ));
    }

    public function printDetailDataKeluargaReport(Request $request)
    {
        $keluargasQuery = Keluarga::query()
            ->with(['desa', 'anggotaKeluarga' => function($query) {
                $query->orderBy('hubungan_dengan_kk', 'asc') // Sort anggota: KK dulu, baru yang lain
                      ->orderBy('nama', 'asc');
            }]); // Eager load relasi dengan sorting anggota

        // Terapkan filter (sama persis dengan showDetailDataKeluargaReport)
        if ($request->has('desa_id') && $request->desa_id !== null) {
            $keluargasQuery->where('desa_id', $request->desa_id);
        }

        if ($request->has('nik_kepala_keluarga') && $request->nik_kepala_keluarga !== null) {
            $keluargasQuery->where('nik_kepala_keluarga', 'like', '%' . $request->nik_kepala_keluarga . '%');
        }

        if ($request->has('nama_kepala_keluarga') && $request->nama_kepala_keluarga !== null) {
            $keluargasQuery->where('nama_kepala_keluarga', 'like', '%' . $request->nama_kepala_keluarga . '%');
        }
        
        // Filter nomor telepon tidak digunakan sesuai permintaan

        if ($request->has('pekerjaan') && $request->pekerjaan !== null) {
            $keluargasQuery->where('pekerjaan', 'like', '%' . $request->pekerjaan . '%');
        }

        if ($request->has('status_ekonomi') && $request->status_ekonomi !== null) {
            $keluargasQuery->where('status_ekonomi', $request->status_ekonomi);
        }

        if ($request->has('kondisi_rumah') && $request->kondisi_rumah !== null) {
            $keluargasQuery->where('kondisi_rumah', $request->kondisi_rumah);
        }

        // Ambil semua data tanpa paginasi untuk PDF
        $keluargas = $keluargasQuery->orderBy('desa_id', 'asc')
                                    ->orderBy('nama_kepala_keluarga', 'asc')
                                    ->get();

        // Siapkan Filters Applied untuk ditampilkan di PDF
        $filtersApplied = [];

        if ($request->desa_id) {
            $desa = Desa::find($request->desa_id);
            if ($desa) {
                $filtersApplied[] = 'Desa: ' . $desa->nama_desa;
            }
        }

        if ($request->nik_kepala_keluarga) {
            $filtersApplied[] = 'NIK Kepala Keluarga: ' . $request->nik_kepala_keluarga;
        }

        if ($request->nama_kepala_keluarga) {
            $filtersApplied[] = 'Nama Kepala Keluarga: ' . $request->nama_kepala_keluarga;
        }

        if ($request->status_ekonomi) {
            $filtersApplied[] = 'Status Ekonomi: ' . Str::title(str_replace('_', ' ', $request->status_ekonomi));
        }

        if ($request->kondisi_rumah) {
            $filtersApplied[] = 'Kondisi Rumah: ' . Str::title(str_replace('_', ' ', $request->kondisi_rumah));
        }
        
        if ($request->pekerjaan) {
            $filtersApplied[] = 'Pekerjaan: ' . $request->pekerjaan;
        }

        if (empty($filtersApplied)) {
            $filtersApplied[] = 'Tidak ada filter diterapkan. Menampilkan semua data keluarga.';
        }

        $data = [
            'title'                     => 'Laporan Detail Data Keluarga',
            'keluargas'                 => $keluargas,
            'filtersApplied'            => $filtersApplied,
        ];

        $pdf = app('dompdf.wrapper')->loadView('reports.detail_data_keluarga.pdf', $data);

        // Atur kertas ke A4 dan orientasi portrait (untuk detail keluarga yang panjang ke bawah)
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('laporan-detail-data-keluarga_' . Carbon::now()->format('YmdHis') . '.pdf');
    }
}
