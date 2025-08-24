<?php

namespace App\Http\Controllers;

use App\Models\Survei;
use App\Models\Keluarga; 
use App\Models\User;     
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

class SurveiController extends Controller
{
    /**
     * Menampilkan daftar semua survei.
     */
    public function index(Request $request)
    {
        $query = Survei::query();
        if ($request->has('status_verifikasi') && $request->status_verifikasi !== null) {
            $statusVerifikasi = $request->status_verifikasi;

            $query->where('status_verifikasi', $statusVerifikasi);
        }
        $surveis = $query->with(['keluarga', 'petugasSurvei', 'diverifikasiOleh'])
                         ->orderBy('tanggal_survei', 'desc')
                         ->simplePaginate(10); 

        return view('surveis.index', compact('surveis'));
    }

    /**
     * Menampilkan formulir untuk mengedit data survei yang sudah ada.
     */
    public function edit(Survei $survei)
    {
        $keluargas = Keluarga::orderBy('nama_kepala_keluarga')->get();
        $petugasSurveiList = User::whereHas('role', function($query) {
            $query->where('name', 'Surveyor'); 
        })->orderBy('name')->get();
        $verifikatorList = User::orderBy('name')->get(); 

        return view('surveis.edit', compact('survei', 'keluargas', 'petugasSurveiList', 'verifikatorList'));
    }

    /**
     * Memperbarui data survei di database.
     */
    public function update(Request $request, Survei $survei)
    {
        $rules = [
            'keluarga_id' => 'required|exists:keluarga,id',
            'petugas_survei_id' => 'required|exists:users,id',
            'tanggal_survei' => 'required|date_format:Y-m-d\TH:i', 
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'jalur_foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_verifikasi' => 'required|in:pending,terverifikasi,ditolak',
            'alasan_penolakan' => 'nullable|string|max:1000',
        ];

        if ($request->status_verifikasi === 'ditolak') {
            $rules['alasan_penolakan'] = 'required|string|max:1000';
        }

        $validatedData = $request->validate($rules);

        DB::beginTransaction();
        try {
            $surveiData = $request->only([
                'keluarga_id',
                'petugas_survei_id',
                'tanggal_survei',
                'latitude',
                'longitude',
                'jalur_foto',
                'status_verifikasi',
                'alasan_penolakan',
            ]);

            if ($request->hasFile('jalur_foto')) {
                if ($survei->jalur_foto) {
                    Storage::disk('public')->delete($survei->jalur_foto);
                }
                $surveiData['jalur_foto'] = $request->file('jalur_foto')->store('dokumen_survei/foto_lokasi', 'public');
            }

            if ($survei->status_verifikasi !== $request->status_verifikasi) {
                if ($request->status_verifikasi === 'terverifikasi' || $request->status_verifikasi === 'ditolak') {
                    $surveiData['diverifikasi_oleh'] = Auth::id(); 
                    $surveiData['tanggal_verifikasi'] = now(); 
                } else { 
                    $surveiData['diverifikasi_oleh'] = null;
                    $surveiData['tanggal_verifikasi'] = null;
                }
            } else {
                if ($surveiData['status_verifikasi'] === 'pending') {
                     $surveiData['diverifikasi_oleh'] = null;
                     $surveiData['tanggal_verifikasi'] = null;
                }
            }


            $survei->update($surveiData);

            DB::commit();
            return redirect()->route('surveis.index')->with('success', 'Data survei berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui survei: ' . $e->getMessage());
        }
    }

}