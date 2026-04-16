<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
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

        if (!Auth::attempt([
            'username' => $request->username,
            'password' => $request->password,
        ], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password salah.'],
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user()->role);
    }

    /**
     * Menampilkan halaman register.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user()->role);
        }

        return view('auth.register');
    }

    /**
     * Proses register siswa.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nipd'           => 'required|string|max:20|exists:siswas,nipd',
            'username'       => 'required|string|max:50|unique:users,username',
            'password'       => 'required|string|min:6|confirmed',
        ]);

        $siswa = Siswa::where('nipd', $validated['nipd'])->first();

        // Cek apakah siswa sudah punya akun
        if ($siswa->user_id) {
            throw ValidationException::withMessages([
                'nipd' => ['NIPD ini sudah terdaftar sebagai akun. Silakan login atau hubungi administrator.'],
            ]);
        }

        // Cek apakah email otomatis (berdasarkan NIPD) sudah dipakai user lain
        $autoEmail = strtolower($siswa->nipd) . '@lentera.sch.id';
        if (User::where('email', $autoEmail)->exists()) {
            throw ValidationException::withMessages([
                'nipd' => ['Tidak dapat membuat akun. Hubungi administrator untuk menyelesaikan.'],
            ]);
        }

        // Buat akun user
        $user = User::create([
            'name'     => $siswa->nama,
            'username' => $validated['username'],
            'email'    => $autoEmail,
            'password' => Hash::make($validated['password']),
            'role'     => 'siswa',
        ]);

        // Hubungkan akun ke data siswa
        $siswa->update(['user_id' => $user->id]);

        // Login otomatis
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard.siswa')->with('success', 'Akun berhasil dibuat dan Anda sudah login.');
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
