<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Survei; 
use App\Models\Keluarga;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

class KeluargaController extends Controller
{
    /**
     * Menampilkan daftar semua keluarga.
     */
    public function index()
    {
        $keluargas = Keluarga::with('desa')->simplePaginate(10);
        return view('keluargas.index', compact('keluargas'));
    }

    /**
     * Menampilkan formulir untuk membuat keluarga baru.
     */
    public function create()
    {
        $desas = Desa::all();
        return view('keluargas.create', compact('desas'));
    }

    /**
     * Menyimpan keluarga baru ke database dan membuat entri survei awal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nik_kepala_keluarga' => 'required|string|max:16|unique:keluarga,nik_kepala_keluarga',
            'nama_kepala_keluarga' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string|max:255',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'desa_id' => 'required|exists:desa,id',
            'nomor_telepon' => 'nullable|string|max:20',
            'status_ekonomi' => 'required|string|in:sangat_miskin,miskin,rentan,menengah',
            'kondisi_rumah' => 'required|string|in:sangat_buruk,buruk,sedang,baik',
            'pendapatan_per_bulan' => 'nullable|numeric|min:0',
            'pekerjaan' => 'nullable|string|max:100',
            'is_disabilitas_berat' => 'boolean',
            'is_lansia' => 'boolean',
            'jalur_slip_gaji' => 'nullable|image|max:2048',
            'jalur_foto_kk' => 'nullable|image|max:2048',
            'catatan' => 'nullable|string',
            'jk' => 'required|string|in:Laki-laki,Perempuan',

            'tanggal_survei' => 'required|date',
            'latitude' => 'nullable|numeric|between:-90,90', 
            'longitude' => 'nullable|numeric|between:-180,180', 
            'jalur_foto_survei' => 'nullable|image|max:2048', 
        ]);

        $keluargaData = $request->only([
            'nik_kepala_keluarga', 'nama_kepala_keluarga', 'alamat_lengkap', 'rt', 'rw',
            'desa_id', 'nomor_telepon', 'status_ekonomi', 'kondisi_rumah',
            'pendapatan_per_bulan', 'pekerjaan', 'catatan', 'jk'
        ]);

        $keluargaData['is_disabilitas_berat'] = $request->has('is_disabilitas_berat');
        $keluargaData['is_lansia'] = $request->has('is_lansia');

        if ($request->hasFile('jalur_slip_gaji')) {
            $keluargaData['jalur_slip_gaji'] = $request->file('jalur_slip_gaji')->store('dokumen_keluarga/slip_gaji', 'public');
        }

        if ($request->hasFile('jalur_foto_kk')) {
            $keluargaData['jalur_foto_kk'] = $request->file('jalur_foto_kk')->store('dokumen_keluarga/foto_kk', 'public');
        }

        $keluarga = Keluarga::create($keluargaData);

        $surveiData = [
            'keluarga_id' => $keluarga->id,
            'petugas_survei_id' => Auth::id(), 
            'tanggal_survei' => $request->tanggal_survei,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status_verifikasi' => 'pending', 
        ];

        if ($request->hasFile('jalur_foto_survei')) {
            $surveiData['jalur_foto'] = $request->file('jalur_foto_survei')->store('dokumen_survei/foto_lokasi', 'public');
        }

        Survei::create($surveiData);

        return redirect()->route('keluargas.index')->with('success', 'Data keluarga dan survei awal berhasil ditambahkan!');
    }

    
    public function show(Keluarga $keluarga)
    {
        $keluarga->load(['desa', 'surveis', 'anggotaKeluarga']);
        return view('keluargas.show', compact('keluarga'));
    }

    public function edit(Keluarga $keluarga)
    {
        $desas = Desa::all();
        return view('keluargas.edit', compact('keluarga', 'desas'));
    }

    public function update(Request $request, Keluarga $keluarga)
    {
        $request->validate([
            'nik_kepala_keluarga' => [
                'required',
                'string',
                'max:16',
                Rule::unique('keluarga')->ignore($keluarga->id, 'id'),
            ],
            'nama_kepala_keluarga' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string|max:255',
            'rt' => 'nullable|string|max:10',
            'rw' => 'nullable|string|max:10',
            'desa_id' => 'required|exists:desa,id',
            'nomor_telepon' => 'nullable|string|max:20',
            'status_ekonomi' => 'required|string|in:sangat_miskin,miskin,rentan,menengah',
            'kondisi_rumah' => 'required|string|in:sangat_buruk,buruk,sedang,baik',
            'pendapatan_per_bulan' => 'nullable|numeric|min:0',
            'pekerjaan' => 'nullable|string|max:100',
            'is_disabilitas_berat' => 'boolean',
            'is_lansia' => 'boolean',
            'jalur_slip_gaji' => 'nullable|image|max:2048',
            'jalur_foto_kk' => 'nullable|image|max:2048',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction(); 
        try {
            $keluargaData = $request->only([
                'nik_kepala_keluarga', 'nama_kepala_keluarga', 'alamat_lengkap', 'rt', 'rw',
                'desa_id', 'nomor_telepon', 'status_ekonomi', 'kondisi_rumah',
                'pendapatan_per_bulan', 'pekerjaan', 'catatan'
            ]);

            $keluargaData['is_disabilitas_berat'] = $request->has('is_disabilitas_berat');
            $keluargaData['is_lansia'] = $request->has('is_lansia');

            if ($request->hasFile('jalur_slip_gaji')) {
                if ($keluarga->jalur_slip_gaji && Storage::disk('public')->exists($keluarga->jalur_slip_gaji)) {
                    Storage::disk('public')->delete($keluarga->jalur_slip_gaji);
                }
                $keluargaData['jalur_slip_gaji'] = $request->file('jalur_slip_gaji')->store('dokumen_keluarga/slip_gaji', 'public');
            } else if ($request->boolean('remove_jalur_slip_gaji')) {
                if ($keluarga->jalur_slip_gaji && Storage::disk('public')->exists($keluarga->jalur_slip_gaji)) {
                    Storage::disk('public')->delete($keluarga->jalur_slip_gaji);
                }
                $keluargaData['jalur_slip_gaji'] = null;
            }

            if ($request->hasFile('jalur_foto_kk')) {
                if ($keluarga->jalur_foto_kk && Storage::disk('public')->exists($keluarga->jalur_foto_kk)) {
                    Storage::disk('public')->delete($keluarga->jalur_foto_kk);
                }
                $keluargaData['jalur_foto_kk'] = $request->file('jalur_foto_kk')->store('dokumen_keluarga/foto_kk', 'public');
            } else if ($request->boolean('remove_jalur_foto_kk')) {
                if ($keluarga->jalur_foto_kk && Storage::disk('public')->exists($keluarga->jalur_foto_kk)) {
                    Storage::disk('public')->delete($keluarga->jalur_foto_kk);
                }
                $keluargaData['jalur_foto_kk'] = null;
            }
            
            $keluarga->update($keluargaData);

            DB::commit();
            return redirect()->route('keluargas.index')->with('success', 'Data keluarga berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui data keluarga: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus data keluarga dan semua data terkait (anggota keluarga, survei).
     */
    public function destroy(Keluarga $keluarga)
    {
        DB::beginTransaction();
        try {
            if ($keluarga->jalur_slip_gaji && Storage::disk('public')->exists($keluarga->jalur_slip_gaji)) {
                Storage::disk('public')->delete($keluarga->jalur_slip_gaji);
            }
            if ($keluarga->jalur_foto_kk && Storage::disk('public')->exists($keluarga->jalur_foto_kk)) {
                Storage::disk('public')->delete($keluarga->jalur_foto_kk);
            }

            if ($keluarga->survei) {
                if ($keluarga->survei->jalur_foto && Storage::disk('public')->exists($keluarga->survei->jalur_foto)) {
                    Storage::disk('public')->delete($keluarga->survei->jalur_foto);
                }
                $keluarga->survei()->delete();
            }

            $keluarga->delete(); 

            DB::commit();
            return redirect()->route('keluargas.index')->with('success', 'Data keluarga beserta survei dan anggota terkait berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('keluargas.index')->with('error', 'Gagal menghapus data keluarga: ' . $e->getMessage());
        }
    }
}