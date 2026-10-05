<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // ==========================================
    // 1. TAMPILKAN HALAMAN
    // ==========================================

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // ==========================================
    // 2. PROSES LOGIN (Email ATAU No HP)
    // ==========================================

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|min:6',
        ]);

        $input    = $request->input('login');
        $password = $request->input('password');

        // Deteksi: Email atau No HP?
        $field = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Kalau No HP, bersihkan karakter non-digit
        if ($field === 'phone') {
            $input = preg_replace('/[^0-9]/', '', $input);
        }

        // Coba login
        if (Auth::attempt([$field => $input, 'password' => $password])) {
            $request->session()->regenerate();

            // Redirect sesuai role
            return Auth::user()->role === 'admin'
                ? redirect()->route('products.index')->with('success', 'Halo Admin, ' . Auth::user()->name)
                : redirect()->route('home')->with('success', 'Halo, ' . Auth::user()->name);
        }

        // Login gagal
        return back()->withErrors(['login' => 'Email/No HP atau password salah.'])->onlyInput('login');
    }

    // ==========================================
    // 3. PROSES REGISTER
    // ==========================================

    public function register(Request $request)
    {
        // Validasi
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|unique:users',
            'phone'    => 'nullable|min:10|max:15|unique:users',
            'address'  => 'nullable|string',
            'password' => 'required|min:6|confirmed',
        ]);

        // Minimal salah satu: email atau phone
        if (empty($validated['email']) && empty($validated['phone'])) {
            return back()->withErrors(['email' => 'Isi salah satu: Email atau No HP.'])->withInput();
        }

        // Bersihkan No HP
        if (!empty($validated['phone'])) {
            $validated['phone'] = preg_replace('/[^0-9]/', '', $validated['phone']);
        }

        // Simpan user baru
        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'] ?? null,
            'phone'    => $validated['phone'] ?? null,
            'address'  => $validated['address'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'user',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // ==========================================
    // 4. LOGOUT
    // ==========================================

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'Anda telah logout.');
    }
}
