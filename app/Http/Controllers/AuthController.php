<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Ambil berdasarkan username
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            return redirect('/')->with('welcome', 'Login berhasil!');
        }

        return redirect('login')->with('error', 'Username atau password salah.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login')->with('success', 'Anda berhasil logout.');
    }
}
