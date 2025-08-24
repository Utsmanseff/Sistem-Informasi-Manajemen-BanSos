<?php

namespace App\Http\Controllers;

use Carbon\Carbon; 
use App\Models\User;
use App\Models\Keluarga;
use App\Models\JenisBantuan;
use Illuminate\Http\Request;
use App\Models\PenerimaBantuan;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PenerimaBantuanController extends Controller
{
    /**
     * Menampilkan daftar semua penerima bantuan.
     */
    public function index(Request $request)
    {
        $query = PenerimaBantuan::query();

        // Logika filter berdasarkan status_penerima (yang sudah ada)
        if ($request->has('status_penerima') && $request->status_penerima !== null) {
            $statusPenerima = $request->status_penerima;
            $query->where('status_penerima', $statusPenerima);
        }

        $user = Auth::user(); 

        if ($user && $user->role->name === 'Distributor') { 
            $query->where('ditugaskan_kepada_distributor_id', $user->id); 
        }

        $penerimaBantuans = $query->with(['keluarga', 'jenisBantuan', 'ditugaskanKepadaDistributor'])
                                ->where('status_penerima', 'belum_tersalurkan')
                                ->orderBy('periode_bantuan', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);

        return view('penerima_bantuan.index', compact('penerimaBantuans'));
    }

    /**
     * Menampilkan formulir untuk membuat penerima bantuan baru.
     */
    public function create()
    {
        $keluargas = Keluarga::whereHas('surveis', function ($query) {
                                $query->where('status_verifikasi', 'terverifikasi');
                            })
                            ->orderBy('nama_kepala_keluarga')
                            ->get();

        $jenisBantuans = JenisBantuan::orderBy('nama_bantuan')->get();

        $distributors = User::whereHas('role', function ($query) {
                                $query->where('name', 'Distributor'); 
                            })
                            ->orderBy('name') 
                            ->get();

        $periods = [];
        for ($i = -12; $i <= 12; $i++) {
            $date = Carbon::now()->addMonths($i)->startOfMonth();
            $periods[$date->toDateString()] = $date->translatedFormat('F Y'); 
        }

        /*
        // --- OPSI PERIODE PER 3 BULAN (DIKOMENTARI) ---
        $quarterPeriods = [];
        $start = Carbon::now()->subMonths(12)->startOfMonth(); // Mulai dari 1 tahun lalu
        $end = Carbon::now()->addMonths(12)->endOfMonth();     // Sampai 1 tahun ke depan
        while ($start->lessThanOrEqualTo($end)) {
            $quarterKey = $start->toDateString(); // Format YYYY-MM-01
            $quarterLabel = 'Q' . $start->quarter . ' ' . $start->year . ' (' . $start->translatedFormat('M') . '-' . $start->copy()->addMonths(2)->translatedFormat('M') . ')';
            $quarterPeriods[$quarterKey] = $quarterLabel;
            $start->addMonths(3); // Lanjut ke triwulan berikutnya
        }
        // return view('penerima_bantuan.create', compact('keluargas', 'jenisBantuans', 'quarterPeriods'));
        */

        return view('penerima_bantuan.create', compact('keluargas', 'jenisBantuans', 'periods', 'distributors'));
    }

    /**
     * Menyimpan penerima bantuan baru ke database dan menghitung nominalnya.
     */
    public function store(Request $request)
    {
        $rules = [
            'keluarga_id' => [
                'required',
                'exists:keluarga,id',
            ],
            'jenis_bantuan_id' => 'required|exists:jenis_bantuan,id',
            'periode_bantuan' => 'required|date_format:Y-m-01', 
            'catatan' => 'nullable|string',
            'ditugaskan_kepada_distributor_id' => 'nullable|exists:users,id',
            'nominal_manual' => 'nullable|numeric|min:0',
        ];

        $rules['keluarga_id'][] = Rule::unique('penerima_bantuan')->where(function ($query) use ($request) {
            return $query->where('jenis_bantuan_id', $request->jenis_bantuan_id)
                         ->where('periode_bantuan', $request->periode_bantuan); 
        });

        $jenisBantuanDipilih = JenisBantuan::find($request->jenis_bantuan_id);
        if ($jenisBantuanDipilih && $jenisBantuanDipilih->nama_bantuan !== 'BLT') {
            $rules['nominal_manual'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $keluarga = Keluarga::findOrFail($request->keluarga_id);
            $jenisBantuan = JenisBantuan::findOrFail($request->jenis_bantuan_id);

            $nominalDihitung = 0;

             if ($jenisBantuan->nama_bantuan === 'BLT') {
                $nominalDihitung = $jenisBantuan->nominal_dasar;
                if ($jenisBantuan->nominal_tambahan_anak > 0) {
                    $jumlahTanggunganAnak = $keluarga->getJumlahTanggunganAnakAttribute();
                    $nominalDihitung += ($jumlahTanggunganAnak * $jenisBantuan->nominal_tambahan_anak);
                }
            } else {
                $nominalDihitung = $request->nominal_manual;
            }
            $nominalDihitung = max(0, $nominalDihitung); 

            PenerimaBantuan::create([
                'keluarga_id' => $request->keluarga_id,
                'jenis_bantuan_id' => $request->jenis_bantuan_id,
                'periode_bantuan' => $request->periode_bantuan, 
                'nominal_dihitung' => $nominalDihitung,
                'status_penerima' => 'belum_tersalurkan',
                'catatan' => $request->catatan,
                'ditugaskan_kepada_distributor_id' => $request->ditugaskan_kepada_distributor_id,
            ]);

            DB::commit();
            return redirect()->route('penerima-bantuan.index')->with('success', 'Penerima bantuan berhasil ditambahkan dan nominal dihitung!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan penerima bantuan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail spesifik dari satu penerima bantuan.
     */
    public function show(PenerimaBantuan $penerimaBantuan)
    {
        $penerimaBantuan->load(['keluarga', 'jenisBantuan', 'penyaluran']);
        return view('penerima_bantuan.show', compact('penerimaBantuan'));
    }

    /**
     * Menampilkan formulir untuk mengedit data penerima bantuan yang sudah ada.
     */
    public function edit(PenerimaBantuan $penerimaBantuan)
    {
        $keluargas = Keluarga::whereHas('surveis', function ($query) {
                                $query->where('status_verifikasi', 'terverifikasi');
                            })
                            ->orderBy('nama_kepala_keluarga')
                            ->get();
        $jenisBantuans = JenisBantuan::orderBy('nama_bantuan')->get();

        $periods = [];
        for ($i = -12; $i <= 12; $i++) {
            $date = Carbon::now()->addMonths($i)->startOfMonth();
            $periods[$date->toDateString()] = $date->translatedFormat('F Y');
        }

        $distributors = User::whereHas('role', function ($query) {
                                $query->where('name', 'Distributor'); 
                            })
                            ->orderBy('name') 
                            ->get();

        /*
        // --- OPSI PERIODE PER 3 BULAN (DIKOMENTARI) ---
        $quarterPeriods = [];
        $start = Carbon::now()->subMonths(12)->startOfMonth();
        $end = Carbon::now()->addMonths(12)->endOfMonth();
        while ($start->lessThanOrEqualTo($end)) {
            $quarterKey = $start->toDateString();
            $quarterLabel = 'Q' . $start->quarter . ' ' . $start->year . ' (' . $start->translatedFormat('M') . '-' . $start->copy()->addMonths(2)->translatedFormat('M') . ')';
            $quarterPeriods[$quarterKey] = $quarterLabel;
            $start->addMonths(3);
        }
        // return view('penerima_bantuan.edit', compact('penerimaBantuan', 'keluargas', 'jenisBantuans', 'quarterPeriods'));
        */

        return view('penerima_bantuan.edit', compact('penerimaBantuan', 'keluargas', 'jenisBantuans', 'periods', 'distributors'));
    }

    /**
     * Memperbarui data penerima bantuan di database dan menghitung ulang nominalnya.
     */
    public function update(Request $request, PenerimaBantuan $penerimaBantuan)
    {
        $rules = [
            'keluarga_id' => [
                'required',
                'exists:keluarga,id',
            ],
            'jenis_bantuan_id' => 'required|exists:jenis_bantuan,id',
            'periode_bantuan' => 'required|date_format:Y-m-01', 
            'status_penerima' => 'required|in:belum_tersalurkan,tersalurkan',
            'catatan' => 'nullable|string',
            'ditugaskan_kepada_distributor_id' => 'nullable|exists:users,id',
            'nominal_manual' => 'nullable|numeric|min:0',
        ];

        $rules['keluarga_id'][] = Rule::unique('penerima_bantuan')->where(function ($query) use ($request) {
            return $query->where('jenis_bantuan_id', $request->jenis_bantuan_id)
                         ->where('periode_bantuan', $request->periode_bantuan); 
        })->ignore($penerimaBantuan->id);

        $jenisBantuanDipilih = JenisBantuan::find($request->jenis_bantuan_id);
        if ($jenisBantuanDipilih && $jenisBantuanDipilih->nama_bantuan !== 'BLT') {
            $rules['nominal_manual'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $keluarga = Keluarga::findOrFail($request->keluarga_id);
            $jenisBantuan = JenisBantuan::findOrFail($request->jenis_bantuan_id);

            $nominalDihitung = 0;

             if ($jenisBantuan->nama_bantuan === 'BLT') {
                $nominalDihitung = $jenisBantuan->nominal_dasar;
                if ($jenisBantuan->nominal_tambahan_anak > 0) {
                    $jumlahTanggunganAnak = $keluarga->getJumlahTanggunganAnakAttribute();
                    $nominalDihitung += ($jumlahTanggunganAnak * $jenisBantuan->nominal_tambahan_anak);
                }
            } else {
                $nominalDihitung = $request->nominal_manual;
            }
            $nominalDihitung = max(0, $nominalDihitung);

            $penerimaBantuan->update([
                'keluarga_id' => $request->keluarga_id,
                'jenis_bantuan_id' => $request->jenis_bantuan_id,
                'periode_bantuan' => $request->periode_bantuan, 
                'nominal_dihitung' => $nominalDihitung,
                'status_penerima' => $request->status_penerima,
                'catatan' => $request->catatan,
                'ditugaskan_kepada_distributor_id' => $request->ditugaskan_kepada_distributor_id,
            ]);

            DB::commit();
            return redirect()->route('penerima-bantuan.index')->with('success', 'Penerima bantuan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui penerima bantuan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus penerima bantuan dari database.
     */
    public function destroy(PenerimaBantuan $penerimaBantuan)
    {
        DB::beginTransaction();
        try {
            $penerimaBantuan->delete();

            DB::commit();
            return redirect()->route('penerima-bantuan.index')->with('success', 'Penerima bantuan berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('penerima-bantuan.index')->with('error', 'Gagal menghapus penerima bantuan: ' . $e->getMessage());
        }
    }
}