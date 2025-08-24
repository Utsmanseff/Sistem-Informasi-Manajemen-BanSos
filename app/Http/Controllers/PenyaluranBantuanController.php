<?php

namespace App\Http\Controllers;

use App\Models\PenyaluranBantuan;
use App\Models\PenerimaBantuan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Penting: Import Auth
use Illuminate\Support\Facades\Storage;

class PenyaluranBantuanController extends Controller
{
    /**
     * Menampilkan daftar semua penyaluran bantuan.
     */
    public function index()
    {
        $penyaluranBantuans = PenyaluranBantuan::with(['penerimaBantuan.keluarga', 'penerimaBantuan.jenisBantuan', 'petugasPenyalur'])
                                                ->orderBy('tanggal_penyaluran', 'desc')
                                                ->paginate(10);

        return view('penyaluran_bantuan.index', compact('penyaluranBantuans'));
    }

    /**
     * Menampilkan formulir untuk membuat penyaluran bantuan baru.
     */
    public function create()
    {
        $query = PenerimaBantuan::query();
        $user = Auth::user(); 

        if ($user && $user->role('Distributor')) { 
            $query->where('ditugaskan_kepada_distributor_id', $user->id); 
        }

        $penerimaBantuans = $query->with('keluarga', 'jenisBantuan')
                                            ->where('status_penerima', 'belum_tersalurkan')
                                            ->orderBy('created_at', 'desc')
                                            ->get();

        return view('penyaluran_bantuan.create', compact('penerimaBantuans'));
    }

    /**
     * Menyimpan penyaluran bantuan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'penerima_bantuan_id' => [
                'required',
                'exists:penerima_bantuan,id',
                Rule::unique('penyaluran_bantuan', 'penerima_bantuan_id'),
            ],
            'tanggal_penyaluran' => 'required|date_format:Y-m-d\TH:i',
            'jalur_foto_penerima' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_setelah_penyaluran' => 'required|in:berhasil_disalurkan,tidak_ditemukan,menolak',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $penyaluranData = $request->only([
                'penerima_bantuan_id',
                'tanggal_penyaluran',
                'status_setelah_penyaluran',
                'catatan',
            ]);

            $penyaluranData['petugas_penyalur_id'] = Auth::id();

            if ($request->hasFile('jalur_foto_penerima')) {
                $penyaluranData['jalur_foto_penerima'] = $request->file('jalur_foto_penerima')->store('bukti_penyaluran/foto_penerima', 'public');
            }

            $penyaluran = PenyaluranBantuan::create($penyaluranData);

            if ($penyaluran->status_setelah_penyaluran === 'berhasil_disalurkan') {
                $penerimaBantuan = PenerimaBantuan::findOrFail($request->penerima_bantuan_id);
                $penerimaBantuan->update(['status_penerima' => 'tersalurkan']);
            } else {
                PenerimaBantuan::where('id', $request->penerima_bantuan_id)->update(['status_penerima' => 'belum_tersalurkan']);
            }

            DB::commit();
            return redirect()->route('penyaluran-bantuan.index')->with('success', 'Penyaluran bantuan berhasil dicatat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal mencatat penyaluran bantuan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail spesifik dari satu penyaluran bantuan.
     */
    public function show(PenyaluranBantuan $penyaluranBantuan)
    {
        $penyaluranBantuan->load(['penerimaBantuan.keluarga', 'penerimaBantuan.jenisBantuan', 'petugasPenyalur']);
        return view('penyaluran_bantuan.show', compact('penyaluranBantuan'));
    }

    /**
     * Menampilkan formulir untuk mengedit data penyaluran bantuan yang sudah ada.
     */
    public function edit(PenyaluranBantuan $penyaluranBantuan)
    {
        $penerimaBantuans = PenerimaBantuan::with('keluarga', 'jenisBantuan')->get();

        return view('penyaluran_bantuan.edit', compact('penyaluranBantuan', 'penerimaBantuans'));
    }

    /**
     * Memperbarui data penyaluran bantuan di database.
     */
    public function update(Request $request, PenyaluranBantuan $penyaluranBantuan)
    {
        $rules = [
            'penerima_bantuan_id' => [
                'required',
                'exists:penerima_bantuan,id',
                Rule::unique('penyaluran_bantuan', 'penerima_bantuan_id')->ignore($penyaluranBantuan->id),
            ],
            'tanggal_penyaluran' => 'required|date_format:Y-m-d\TH:i',
            'jalur_foto_penerima' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status_setelah_penyaluran' => 'required|in:berhasil_disalurkan,tidak_ditemukan,menolak',
            'catatan' => 'nullable|string',
        ];

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $penyaluranData = $request->only([
                'penerima_bantuan_id',
                'tanggal_penyaluran',
                'status_setelah_penyaluran',
                'catatan',
            ]);

            $penyaluranData['petugas_penyalur_id'] = Auth::id();

            $oldPenerimaBantuanId = $penyaluranBantuan->penerima_bantuan_id;

            if ($request->hasFile('jalur_foto_penerima')) {
                if ($penyaluranBantuan->jalur_foto_penerima) {
                    Storage::disk('public')->delete($penyaluranBantuan->jalur_foto_penerima);
                }
                $penyaluranData['jalur_foto_penerima'] = $request->file('jalur_foto_penerima')->store('bukti_penyaluran/foto_penerima', 'public');
            } else if ($request->boolean('remove_jalur_foto_penerima')) {
                if ($penyaluranBantuan->jalur_foto_penerima) {
                    Storage::disk('public')->delete($penyaluranBantuan->jalur_foto_penerima);
                    $penyaluranData['jalur_foto_penerima'] = null;
                }
            } else {
                unset($penyaluranData['jalur_foto_penerima']);
            }

            $penyaluranBantuan->update($penyaluranData);

            $newPenerimaBantuanId = $penyaluranBantuan->penerima_bantuan_id;
            $newStatusSetelahPenyaluran = $penyaluranBantuan->status_setelah_penyaluran;

            if ($newStatusSetelahPenyaluran === 'berhasil_disalurkan') {
                PenerimaBantuan::where('id', $newPenerimaBantuanId)->update(['status_penerima' => 'tersalurkan']);
            } else {
                PenerimaBantuan::where('id', $newPenerimaBantuanId)->update(['status_penerima' => 'belum_tersalurkan']);
            }

            if ($oldPenerimaBantuanId !== $newPenerimaBantuanId) {
                $oldPenerimaBantuan = PenerimaBantuan::find($oldPenerimaBantuanId);
                if ($oldPenerimaBantuan && $oldPenerimaBantuan->status_penerima === 'tersalurkan') {
                    $oldPenerimaBantuan->update(['status_penerima' => 'belum_tersalurkan']);
                }
            }

            DB::commit();
            return redirect()->route('penyaluran-bantuan.index')->with('success', 'Penyaluran bantuan berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui penyaluran bantuan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus penyaluran bantuan dari database.
     */
    public function destroy(PenyaluranBantuan $penyaluranBantuan)
    {
        DB::beginTransaction();
        try {
            $penerimaBantuan = $penyaluranBantuan->penerimaBantuan;
            if ($penerimaBantuan) {
                if ($penyaluranBantuan->status_setelah_penyaluran === 'berhasil_disalurkan') {
                    $penerimaBantuan->update(['status_penerima' => 'belum_tersalurkan']);
                }
            }

            if ($penyaluranBantuan->jalur_foto_penerima) {
                Storage::disk('public')->delete($penyaluranBantuan->jalur_foto_penerima);
            }

            $penyaluranBantuan->delete();

            DB::commit();
            return redirect()->route('penyaluran-bantuan.index')->with('success', 'Penyaluran bantuan berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('penyaluran-bantuan.index')->with('error', 'Gagal menghapus penyaluran bantuan: ' . $e->getMessage());
        }
    }
}