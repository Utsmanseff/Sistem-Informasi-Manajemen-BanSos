<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\Keluarga; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; 

class AnggotaKeluargaController extends Controller
{

    /**
     * Menampilkan formulir untuk menambah anggota keluarga baru untuk keluarga tertentu.
     */
    public function create(Keluarga $keluarga)
    {
        return view('anggota_keluargas.create', compact('keluarga'));
    }

    /**
     * Menyimpan anggota keluarga baru ke database.
     */
    public function store(Request $request, Keluarga $keluarga)
    {
        $request->validate([
            'nik' => [
                'required',
                'string',
                'max:16',
                Rule::unique('anggota_keluarga', 'nik'), 
            ],
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'hubungan_dengan_kk' => 'required|in:anak,istri,suami,orang_tua,lainnya', 
            'tingkat_pendidikan' => 'nullable|string|max:50',
            'is_disabilitas_berat' => 'boolean',
            'is_lansia' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $anggotaData = $request->only([
                'nik', 'nama', 'tanggal_lahir', 'jenis_kelamin', 'hubungan_dengan_kk',
                'tingkat_pendidikan'
            ]);

            $anggotaData['is_disabilitas_berat'] = $request->has('is_disabilitas_berat');
            $anggotaData['is_lansia'] = $request->has('is_lansia');
            $anggotaData['keluarga_id'] = $keluarga->id; 

            AnggotaKeluarga::create($anggotaData);

            DB::commit();
            return redirect()->route('keluargas.show', $keluarga->id)->with('success', 'Anggota keluarga berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menambahkan anggota keluarga: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan formulir untuk mengedit data anggota keluarga yang sudah ada.
     */
    public function edit(Keluarga $keluarga, AnggotaKeluarga $anggota_keluarga)
    {
        if ($anggota_keluarga->keluarga_id !== $keluarga->id) {
            abort(404);
        }
        return view('anggota_keluargas.edit', compact('keluarga', 'anggota_keluarga'));
    }

    /**
     * Memperbarui data anggota keluarga di database.
     */
    public function update(Request $request, Keluarga $keluarga, AnggotaKeluarga $anggota_keluarga)
    {
        if ($anggota_keluarga->keluarga_id !== $keluarga->id) {
            abort(403, 'Akses tidak diizinkan.'); 
        }

        $request->validate([
            'nik' => [
                'required',
                'string',
                'max:16',
                Rule::unique('anggota_keluarga', 'nik')->ignore($anggota_keluarga->id), 
            ],
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'hubungan_dengan_kk' => 'required|in:anak,istri,suami,orang_tua,lainnya',
            'tingkat_pendidikan' => 'nullable|string|max:50',
            'is_disabilitas_berat' => 'boolean',
            'is_lansia' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $anggotaData = $request->only([
                'nik', 'nama', 'tanggal_lahir', 'jenis_kelamin', 'hubungan_dengan_kk',
                'tingkat_pendidikan'
            ]);

            $anggotaData['is_disabilitas_berat'] = $request->has('is_disabilitas_berat');
            $anggotaData['is_lansia'] = $request->has('is_lansia');

            $anggota_keluarga->update($anggotaData);

            DB::commit();
            return redirect()->route('keluargas.show', $keluarga->id)->with('success', 'Anggota keluarga berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui anggota keluarga: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus anggota keluarga dari database.
     */
    public function destroy(Keluarga $keluarga, AnggotaKeluarga $anggota_keluarga)
    {
        if ($anggota_keluarga->keluarga_id !== $keluarga->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        DB::beginTransaction();
        try {
            $anggota_keluarga->delete();

            DB::commit();
            return redirect()->route('keluargas.show', $keluarga->id)->with('success', 'Anggota keluarga berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('keluargas.show', $keluarga->id)->with('error', 'Gagal menghapus anggota keluarga: ' . $e->getMessage());
        }
    }
}