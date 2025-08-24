<?php

namespace App\Http\Controllers;

use App\Models\Desa; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; 

class DesaController extends Controller
{
    /**
     * Menampilkan daftar semua desa.
     */
    public function index()
    {
        $desas = Desa::all();
        return view('desa.index', compact('desas'));
    }

    /**
     * Menampilkan formulir untuk membuat desa baru.
     */
    public function create()
    {
        return view('desa.create');
    }

    /**
     * Menyimpan desa baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_desa' => 'required|string|max:100|unique:desa,nama_desa', 
        ]);

        Desa::create([
            'nama_desa' => $request->nama_desa,
        ]);

        return redirect()->route('desas.index')->with('success', 'Desa berhasil ditambahkan!');
    }

    /**
     * Metode show, edit, update, dan destroy akan kita kerjakan nanti.
     */
    public function show(Desa $desa)
    {
        //
    }

    public function edit(Desa $desa)
    {
        return view('desa.edit', compact('desa'));
    }

    /**
     * Memperbarui data desa di database.
     */
    public function update(Request $request, Desa $desa)
    {
        $request->validate([
            'nama_desa' => [
                'required',
                'string',
                'max:100',
                Rule::unique('desa')->ignore($desa->id),
            ],
        ]);

        $desa->update([
            'nama_desa' => $request->nama_desa,
        ]);

        return redirect()->route('desas.index')->with('success', 'Desa berhasil diperbarui!');
    }

    /**
     * Menghapus desa dari database.
     */
    public function destroy(Desa $desa)
    {
        try {
            $desa->delete();
            return redirect()->route('desas.index')->with('success', 'Desa berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('desas.index')->with('error', 'Gagal menghapus desa. Mungkin ada data terkait yang harus dihapus terlebih dahulu.');
        }
    }
}