<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        // Jika sudah login, arahkan ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt login menggunakan kolom 'username'
        if (!Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        // Regenerate session untuk keamanan
        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user()->role);
    }

    /**
     * Proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect ke dashboard berdasarkan role.
     */
    private function redirectByRole(string $role)
    {
        return match ($role) {
            'administrator' => redirect()->route('dashboard.admin'),
            'scanner'       => redirect()->route('dashboard.scanner'),
            'siswa'         => redirect()->route('dashboard.siswa'),
            default         => redirect()->route('login'),
        };
    }
}
