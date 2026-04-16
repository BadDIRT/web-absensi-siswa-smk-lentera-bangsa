<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PengaturanController extends Controller
{
    public function index(Request $request)
    {
        $users = User::when(
            $request->search,
            fn($q, $s) =>
            $q->where('name', 'like', "%{$s}%")
                ->orWhere('username', 'like', "%{$s}%")
        )
            ->when($request->role, fn($q, $r) => $q->where('role', $r))
            ->latest()
            ->paginate(10);

        return view('admin.pengaturan.index', compact('users'));
    }

    public function create()
    {
        return view('admin.pengaturan.form', [
            'user' => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:administrator,scanner,siswa',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        // Load relasi siswa hanya jika role-nya siswa
        $siswa = null;
        if ($user->role === 'siswa') {
            $siswa = $user->siswa?->load('kelas.jurusan');
        }

        $bulan = now()->format('Y-m');
        $absensis = collect();
        $stats = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0];
        $scanHariIni = 0;
        $totalScan = 0;

        // Data absensi jika siswa yang punya data siswa
        if ($siswa) {
            $absensis = Absensi::where('siswa_id', $siswa->id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->orderByDesc('tanggal')
                ->get();

            $stats = [
                'hadir' => $absensis->where('status', 'hadir')->count(),
                'izin'  => $absensis->where('status', 'izin')->count(),
                'sakit' => $absensis->where('status', 'sakit')->count(),
                'alpa'  => $absensis->where('status', 'alpa')->count(),
            ];
        }

        // Statistik scan jika role scanner
        if ($user->role === 'scanner') {
            $scanHariIni = Absensi::where('scanned_by', $user->id)
                ->where('tanggal', today())
                ->count();

            $totalScan = Absensi::where('scanned_by', $user->id)->count();
        }

        return view('admin.pengaturan.show', compact(
            'user',
            'siswa',
            'absensis',
            'stats',
            'bulan',
            'scanHariIni',
            'totalScan'
        ));
    }

    public function edit(User $user)
    {
        return view('admin.pengaturan.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email'    => 'required|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role'     => 'required|in:administrator,scanner,siswa',
        ]);

        if (filled($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.pengaturan.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
