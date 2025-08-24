<?php

namespace App\Http\Controllers;

use App\Models\JenisBantuan; // Import model JenisBantuan
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Untuk validasi unique di update

class JenisBantuanController extends Controller
{
    /**
     * Menampilkan daftar semua jenis bantuan.
     */
    public function index()
    {
        $jenisBantuans = JenisBantuan::all();
        return view('jenis_bantuans.index', compact('jenisBantuans'));
    }

    /**
     * Menampilkan formulir untuk membuat jenis bantuan baru.
     */
    public function create()
    {
        return view('jenis_bantuans.create');
    }

    /**
     * Menyimpan jenis bantuan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_bantuan' => 'required|string|max:100|unique:jenis_bantuan,nama_bantuan',
            'nominal_dasar' => 'nullable|numeric|min:0',
            'nominal_tambahan_anak' => 'nullable|numeric|min:0',
            'maksimal_anak_tambahan' => 'nullable|integer|min:0',
        ]);

        JenisBantuan::create([
            'nama_bantuan' => $request->nama_bantuan,
            'nominal_dasar' => $request->nominal_dasar,
            'nominal_tambahan_anak' => $request->nominal_tambahan_anak,
            'maksimal_anak_tambahan' => $request->maksimal_anak_tambahan,
        ]);

        return redirect()->route('jenis_bantuans.index')->with('success', 'Jenis bantuan berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail satu jenis bantuan (opsional).
     */
    public function show(JenisBantuan $jenisBantuan)
    {
    }

    /**
     * Menampilkan formulir untuk mengedit jenis bantuan yang sudah ada.
     */
    public function edit(JenisBantuan $jenisBantuan)
    {
        return view('jenis_bantuans.edit', compact('jenisBantuan'));
    }

    /**
     * Memperbarui data jenis bantuan di database.
     */
    public function update(Request $request, JenisBantuan $jenisBantuan)
    {
        $request->validate([
            'nama_bantuan' => [
                'required',
                'string',
                'max:100',
                Rule::unique('jenis_bantuan')->ignore($jenisBantuan->id),
            ],
            'nominal_dasar' => 'nullable|numeric|min:0',
            'nominal_tambahan_anak' => 'nullable|numeric|min:0',
            'maksimal_anak_tambahan' => 'nullable|integer|min:0',
        ]);

        $jenisBantuan->update([
            'nama_bantuan' => $request->nama_bantuan,
            'nominal_dasar' => $request->nominal_dasar,
            'nominal_tambahan_anak' => $request->nominal_tambahan_anak,
            'maksimal_anak_tambahan' => $request->maksimal_anak_tambahan,
        ]);

        return redirect()->route('jenis_bantuans.index')->with('success', 'Jenis bantuan berhasil diperbarui!');
    }

    /**
     * Menghapus jenis bantuan dari database.
     */
    public function destroy(JenisBantuan $jenisBantuan)
    {
        try {
            $jenisBantuan->delete();
            return redirect()->route('jenis_bantuans.index')->with('success', 'Jenis bantuan berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('jenis_bantuans.index')->with('error', 'Gagal menghapus jenis bantuan. Mungkin ada data terkait yang harus dihapus terlebih dahulu.');
        }
    }
}