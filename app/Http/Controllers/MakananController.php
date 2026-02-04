<?php

namespace App\Http\Controllers;

use App\Models\Makanan;
use Illuminate\Http\Request;

class MakananController extends Controller
{
    public function index()
    {
        $makanans = Makanan::latest()->paginate(10);

        return view('makanans.index', compact('makanans'));
    }

    public function create()
    {
        return view('makanans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'namamakanan' => 'required|string|max:255',
            'kode'       => 'required|string|max:50|unique:makanans,kode',
            'harga'      => 'required|numeric|min:0',
        ]);

        Barang::create($data);

        return redirect()
            ->route('makanans.index')
            ->with('success', 'Makanan berhasil ditambahkan.');
    }

    public function show(Makanan $makanan)
    {
        return view('makanans.show', compact('makanan'));
    }

    public function edit(Makanan $Makanan)
    {
        return view('makanans.edit', compact('makanan'));
    }

    public function update(Request $request, Makanan $makanan)
    {
        $data = $request->validate([
            'namamakanan' => 'required|string|max:255',
            'kode'       => 'required|string|max:50|unique:makanans,kode,' . $makanan->id,
            'harga'      => 'required|numeric|min:0',
        ]);

        $barang->update($data);

        return redirect()
            ->route('makanans.index')
            ->with('success', 'Makanan berhasil diperbarui.');
    }

    public function destroy(Makanan $makanan)
    {
        $makanan->delete();

        return redirect()
            ->route('makanans.index')
            ->with('success', 'Makanan berhasil dihapus.');
    }
}
