<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Menampilkan halaman form login
    public function login()
    {
        return view('auth.login');
    }

    // 2. Mengecek kecocokan username dan password
    public function authenticate(Request $request)
    {
        // Validasi form tidak boleh kosong
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);    
        
        // Coba login (Auth::attempt otomatis mengeksekusi pencocokan ke database)
        if (Auth::attempt($credentials)) {
            // Jika berhasil, perbarui sesi (keamanan tambahan)
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->intended('/pesanan');
            } elseif (Auth::user()->role == 'penjahit') {
                return redirect()->intended('/penjahit/dashboard');
            }
        }

        // Jika gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ]);
    }
    
    // 3. Proses keluar (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}
