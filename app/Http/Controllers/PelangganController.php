<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggans = Pelanggan::orderBy('nama', 'asc')->get();
        return view('pelanggan.index', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_kk' => 'nullable|string|max:20|unique:pelanggans,no_kk',
            'no_telp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string'
        ]);

        Pelanggan::create($request->all());

        return redirect()->back()->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_kk' => 'nullable|string|max:20|unique:pelanggans,no_kk,' . $id,
            'no_telp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string'
        ]);

        $pelanggan->update($request->all());

        return redirect()->back()->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->back()->with('success', 'Pelanggan berhasil dihapus.');
    }
}