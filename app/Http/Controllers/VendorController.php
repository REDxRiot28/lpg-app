<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::orderBy('created_at', 'desc')->get();
        return view('manajemen-user.vendor.index', compact('vendors'));
    }

    public function create()
    {
        return view('manajemen-user.vendor.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_vendor' => 'required|string|max:50|unique:vendor,kode_vendor',
            'nama_vendor' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'nullable|email|unique:vendor,email',
            'kontak_person' => 'required|string|max:255',
            'status_aktif' => 'required|boolean'
        ], [
            'kode_vendor.required' => 'Kode vendor wajib diisi',
            'kode_vendor.unique' => 'Kode vendor sudah ada',
            'nama_vendor.required' => 'Nama vendor wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'telepon.required' => 'Telepon wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'kontak_person.required' => 'Kontak person wajib diisi',
            'status_aktif.required' => 'Status aktif wajib dipilih'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Vendor::create([
                'kode_vendor' => $request->kode_vendor,
                'nama_vendor' => $request->nama_vendor,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'kontak_person' => $request->kontak_person,
                'status_aktif' => $request->status_aktif
            ]);

            return redirect()->route('vendor.index')
                ->with('success', 'Vendor berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan vendor: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('manajemen-user.vendor.show', compact('vendor'));
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('manajemen-user.vendor.edit', compact('vendor'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:vendor,id',
            'kode_vendor' => 'required|string|max:50|unique:vendor,kode_vendor,' . $request->id,
            'nama_vendor' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'nullable|email|unique:vendor,email,' . $request->id,
            'kontak_person' => 'required|string|max:255',
            'status_aktif' => 'required|boolean'
        ], [
            'kode_vendor.required' => 'Kode vendor wajib diisi',
            'kode_vendor.unique' => 'Kode vendor sudah ada',
            'nama_vendor.required' => 'Nama vendor wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'telepon.required' => 'Telepon wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'kontak_person.required' => 'Kontak person wajib diisi',
            'status_aktif.required' => 'Status aktif wajib dipilih'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $vendor = Vendor::findOrFail($request->id);
            $vendor->update([
                'kode_vendor' => $request->kode_vendor,
                'nama_vendor' => $request->nama_vendor,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'kontak_person' => $request->kontak_person,
                'status_aktif' => $request->status_aktif
            ]);

            return redirect()->route('vendor.index')
                ->with('success', 'Vendor berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui vendor: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            
            // Check if vendor is being used
            if ($vendor->pembelianGas()->count() > 0) {
                return redirect()->route('vendor.index')
                    ->with('error', 'Vendor tidak dapat dihapus karena masih memiliki transaksi pembelian');
            }

            $vendor->delete();

            return redirect()->route('vendor.index')
                ->with('success', 'Vendor berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('vendor.index')
                ->with('error', 'Gagal menghapus vendor: ' . $e->getMessage());
        }
    }
}
