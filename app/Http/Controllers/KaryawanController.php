<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = User::orderBy('created_at', 'desc')->get();
        return view('manajemen-user.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('manajemen-user.karyawan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:super-admin,owner,kasir',
            'status_aktif' => 'required|boolean'
        ], [
            'name.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
            'status_aktif.required' => 'Status aktif wajib dipilih'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status_aktif' => $request->status_aktif
            ]);

            return redirect()->route('karyawan.index')
                ->with('success', 'Karyawan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan Karyawan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $karyawans = User::findOrFail($id);
        return view('manajemen-user.karyawan.show', compact('karyawans'));
    }

    public function edit($id)
    {
        $karyawans = User::findOrFail($id);
        return view('manajemen-user.karyawan.edit', compact('karyawans'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $request->id,
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:super-admin,owner,kasir',
            'status_aktif' => 'required|boolean'
        ], [
            'name.required' => 'Nama wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role wajib dipilih',
            'role.in' => 'Role tidak valid',
            'status_aktif.required' => 'Status aktif wajib dipilih'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $karyawan = User::findOrFail($request->id);
            
            $updateData = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role' => $request->role,
                'status_aktif' => $request->status_aktif
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $karyawan->update($updateData);

            return redirect()->route('karyawan.index')
                ->with('success', 'Karyawan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui Karyawan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $karyawan = User::findOrFail($id);
            
            // Prevent deleting current user
            if ($karyawan->id == auth()->id()) {
                return redirect()->route('karyawan.index')
                    ->with('error', 'Tidak dapat menghapus akun sendiri');
            }

            $karyawan->delete();

            return redirect()->route('karyawan.index')
                ->with('success', 'Karyawan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('karyawan.index')
                ->with('error', 'Gagal menghapus Karyawan: ' . $e->getMessage());
        }
    }
}
