<?php

namespace App\Http\Controllers;

use App\Models\TipeGas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class TipeGasController extends Controller
{
    public function index()
    {
        $tipeGas = TipeGas::orderBy('created_at', 'desc')->get();
        return view('tipe-gas.index', compact('tipeGas'));
    }

    public function create()
    {
        return view('tipe-gas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:tipe_gas,nama',
            'harga_jual' => 'required|numeric|min:0',
        ], [
            'nama.required' => 'Nama tipe gas wajib diisi',
            'nama.unique' => 'Nama tipe gas sudah ada',
            'harga_jual.required' => 'Harga jual wajib diisi',
            'harga_jual.numeric' => 'Harga jual harus berupa angka',
        ]);

        try {
            TipeGas::create([
                'nama' => $request->nama,
                'harga_jual' => $request->harga_jual,
            ]);

            return redirect()->route('tipe-gas.index')->with('success', 'Tipe gas berhasil ditambahkan.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan tipe gas: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $tipeGas = TipeGas::findOrFail($id);
        return view('tipe-gas.show', compact('tipeGas'));
    }

    public function edit($id)
    {
        $tipeGas = TipeGas::findOrFail($id);
        return view('tipe-gas.edit', compact('tipeGas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:tipe_gas,nama,' . $id,
            'harga_jual' => 'required|numeric|min:0',
        ], [
            'nama.required' => 'Nama tipe gas wajib diisi',
            'nama.unique' => 'Nama tipe gas sudah ada',
            'harga_jual.required' => 'Harga jual wajib diisi',
            'harga_jual.numeric' => 'Harga jual harus berupa angka',
        ]);

        try {
            $tipeGas = TipeGas::findOrFail($id);
            $tipeGas->update([
                'nama' => $request->nama,
                'harga_jual' => $request->harga_jual,
            ]);

            return redirect()->route('tipe-gas.index')->with('success', 'Tipe gas berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui tipe gas: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $tipeGas = TipeGas::findOrFail($id);

            if (method_exists($tipeGas, 'stokGas') && $tipeGas->stokGas()->count() > 0) {
                return redirect()->route('tipe-gas.index')->with('error', 'Tipe gas tidak dapat dihapus karena masih digunakan dalam stok.');
            }

            $tipeGas->delete();

            return redirect()->route('tipe-gas.index')->with('success', 'Tipe gas berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->route('tipe-gas.index')->with('error', 'Gagal menghapus tipe gas: ' . $e->getMessage());
        }
    }
}
