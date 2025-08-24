<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenyaluranBantuan;
use App\Models\Survei; 
use Illuminate\Support\Facades\DB; 
use App\Models\Keluarga;
use App\Models\PenerimaBantuan; 

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleName = $user->role->name ?? 'Guest'; 

        $kpis = [];

        $kpis['total_survei'] = Survei::count();
        $kpis['survei_pending'] = Survei::where('status_verifikasi', 'pending')->count();
        $kpis['survei_terverifikasi'] = Survei::where('status_verifikasi', 'terverifikasi')->count();
        $kpis['survei_ditolak'] = Survei::where('status_verifikasi', 'ditolak')->count();

        $kpis['total_nominal_dialokasikan'] = PenerimaBantuan::sum('nominal_dihitung');
       
        $kpis['jumlah_penerima_bantuan'] = PenerimaBantuan::distinct('keluarga_id')->count('keluarga_id'); 

        $kpis['bantuan_belum_tersalurkan_count'] = PenerimaBantuan::where('status_penerima', 'belum_tersalurkan')->count();
        $kpis['bantuan_tersalurkan_count'] = PenerimaBantuan::where('status_penerima', 'tersalurkan')->count();

        $kpis['keluarga_lansia'] = Keluarga::whereHas('anggotaKeluarga', function ($query) {
            $query->whereIn('hubungan_dengan_kk', ['kepala_keluarga', 'istri', 'suami'])
                  ->where('is_lansia', true);
        })->count();

        $kpis['keluarga_disabilitas'] = Keluarga::whereHas('anggotaKeluarga', function ($query) {
            $query->whereIn('hubungan_dengan_kk', ['kepala_keluarga', 'istri', 'suami'])
                  ->where('is_disabilitas_berat', true);
        })->count();

        $chartsData = [];

        $statusSurveiCounts = Survei::select('status_verifikasi', DB::raw('count(*) as count'))
                                     ->groupBy('status_verifikasi')
                                     ->pluck('count', 'status_verifikasi')
                                     ->toArray();
        $chartsData['surveiStatusLabels'] = array_keys($statusSurveiCounts) ?: [];
        $chartsData['surveiStatusData'] = array_values($statusSurveiCounts) ?: [];

        $penyaluranPerPeriode = PenerimaBantuan::select(
            DB::raw("DATE_FORMAT(periode_bantuan, '%Y-%m') as periode"), 
            DB::raw('COUNT(DISTINCT keluarga_id) as jumlah_penerima'),
            DB::raw('SUM(nominal_dihitung) as total_nominal')
        )
        ->groupBy('periode')
        ->orderBy('periode')
        ->get();

        $chartsData['periodeBantuanLabels'] = $penyaluranPerPeriode->pluck('periode')->toArray() ?: [];
        $chartsData['jumlahPenerimaData'] = $penyaluranPerPeriode->pluck('jumlah_penerima')->toArray() ?: [];
        $chartsData['totalNominalData'] = $penyaluranPerPeriode->pluck('total_nominal')->toArray() ?: [];

        $distribusiJenisBantuan = PenerimaBantuan::select(
            'jenis_bantuan.nama_bantuan',
            DB::raw('COUNT(*) as count')
        )
        ->join('jenis_bantuan', 'penerima_bantuan.jenis_bantuan_id', '=', 'jenis_bantuan.id')
        ->groupBy('jenis_bantuan.nama_bantuan')
        ->pluck('count', 'jenis_bantuan.nama_bantuan')
        ->toArray();

        $chartsData['jenisBantuanLabels'] = array_keys($distribusiJenisBantuan) ?: [];
        $chartsData['jenisBantuanData'] = array_values($distribusiJenisBantuan) ?: [];

        $jadwalPenyaluran = DB::table('penerima_bantuan AS pb')
            ->select(
                'pb.periode_bantuan',
                'jb.nama_bantuan',
                'k.nama_kepala_keluarga',
                'd.nama_desa AS nama_desa',
                's.latitude AS survey_latitude',
                's.longitude AS survey_longitude'
            )
            ->join('jenis_bantuan AS jb', 'pb.jenis_bantuan_id', '=', 'jb.id')
            ->join('keluarga AS k', 'pb.keluarga_id', '=', 'k.id')
            ->join('desa AS d', 'k.desa_id', '=', 'd.id')
            ->leftJoin('survei AS s', function($join) {
                $join->on('k.id', '=', 's.keluarga_id')
                     ->whereNotNull('s.latitude') 
                     ->whereNotNull('s.longitude'); 
            })
            ->where('pb.status_penerima', 'belum_tersalurkan')
            ->orderBy('pb.periode_bantuan', 'asc')
            ->orderBy('k.nama_kepala_keluarga', 'asc')
            ->get();

        $latestActivities = [];
        $latestActivities['survei'] = Survei::with(['keluarga', 'petugasSurvei'])->orderBy('created_at', 'desc')->take(2)->get();
        $latestActivities['penerima_bantuan'] = PenerimaBantuan::with(['keluarga', 'jenisBantuan'])->orderBy('created_at', 'desc')->take(2)->get();

        $dataForView = [
            'roleName' => $roleName,
            'kpis' => $kpis,
            'chartsData' => $chartsData,
            'jadwalPenyaluran' => $jadwalPenyaluran,
            'latestActivities' => $latestActivities,
            'user' => $user, 
        ];

        if ($roleName === 'Surveyor') {
            $dataForView['kpis']['total_survei_saya'] = Survei::where('petugas_survei_id', $user->id)->count();
            $dataForView['kpis']['survei_pending_saya'] = Survei::where('petugas_survei_id', $user->id)->where('status_verifikasi', 'pending')->count();
            $dataForView['kpis']['survei_terverifikasi_saya'] = Survei::where('petugas_survei_id', $user->id)->where('status_verifikasi', 'terverifikasi')->count();
            $dataForView['kpis']['survei_ditolak_saya'] = Survei::where('petugas_survei_id', $user->id)->where('status_verifikasi', 'ditolak')->count();

            $dataForView['latestActivities']['survei'] = Survei::where('petugas_survei_id', $user->id)
                                                                 ->with(['keluarga', 'petugasSurvei'])
                                                                 ->orderBy('created_at', 'desc')
                                                                 ->take(5)
                                                                 ->get();
            
            $statusSurveiSayaCounts = Survei::where('petugas_survei_id', $user->id)
                                            ->select('status_verifikasi', DB::raw('count(*) as count'))
                                            ->groupBy('status_verifikasi')
                                            ->pluck('count', 'status_verifikasi')
                                            ->toArray();
            $dataForView['chartsData']['surveiStatusLabelsSaya'] = array_keys($statusSurveiSayaCounts) ?: [];
            $dataForView['chartsData']['surveiStatusDataSaya'] = array_values($statusSurveiSayaCounts) ?: [];
            
        } elseif ($roleName === 'Distributor') {
            $dataForView['kpis']['bantuan_saya_tersalurkan_count'] = PenyaluranBantuan::where('petugas_penyalur_id', $user->id)->count();
            
            $dataForView['kpis']['total_nominal_tersalurkan_saya'] = PenyaluranBantuan::where('petugas_penyalur_id', $user->id)
                ->join('penerima_bantuan', 'penyaluran_bantuan.penerima_bantuan_id', '=', 'penerima_bantuan.id')
                ->sum('penerima_bantuan.nominal_dihitung');

            $dataForView['kpis']['bantuan_saya_belum_tersalurkan_count'] = 0; 
            $dataForView['jadwalPenyaluran'] = DB::table('penerima_bantuan AS pb')
                ->select(
                    'pb.periode_bantuan',
                    'jb.nama_bantuan',
                    'k.nama_kepala_keluarga',
                    'd.nama_desa AS nama_desa',
                    's.latitude AS survey_latitude',
                    's.longitude AS survey_longitude'
                )
                ->join('jenis_bantuan AS jb', 'pb.jenis_bantuan_id', '=', 'jb.id')
                ->join('keluarga AS k', 'pb.keluarga_id', '=', 'k.id')
                ->join('desa AS d', 'k.desa_id', '=', 'd.id')
                ->leftJoin('survei AS s', function($join) {
                    $join->on('k.id', '=', 's.keluarga_id')
                         ->whereNotNull('s.latitude')
                         ->whereNotNull('s.longitude');
                })
                ->where('pb.status_penerima', 'belum_tersalurkan')
                ->orderBy('pb.periode_bantuan', 'asc')
                ->orderBy('k.nama_kepala_keluarga', 'asc')
                ->get();
            
            $dataForView['latestActivities']['penyaluran_log'] = PenyaluranBantuan::where('petugas_penyalur_id', $user->id)
                                                                                   ->with(['penerimaBantuan.keluarga', 'penerimaBantuan.jenisBantuan', 'petugasPenyalur'])
                                                                                   ->orderBy('created_at', 'desc')
                                                                                   ->take(5)
                                                                                   ->get();
        }
        
        return view('index', $dataForView); 
    }
}