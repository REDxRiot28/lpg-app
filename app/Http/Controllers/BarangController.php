<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::latest()->paginate(10);

        return view('barangs.index', compact('barangs'));
    }

    public function create()
    {
        return view('barangs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'namabarang' => 'required|string|max:255',
            'kode'       => 'required|string|max:50|unique:barangs,kode',
            'harga'      => 'required|numeric|min:0',
        ]);

        Barang::create($data);

        return redirect()
            ->route('barangs.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        return view('barangs.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        return view('barangs.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $data = $request->validate([
            'namabarang' => 'required|string|max:255',
            'kode'       => 'required|string|max:50|unique:barangs,kode,' . $barang->id,
            'harga'      => 'required|numeric|min:0',
        ]);

        $barang->update($data);

        return redirect()
            ->route('barangs.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();

        return redirect()
            ->route('barangs.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
