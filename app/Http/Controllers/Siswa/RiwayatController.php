<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return view('siswa.riwayat', [
                'siswa' => null,
                'absensis' => collect(),
                'bulan' => null,
                'stats' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0],
            ]);
        }

        $bulan = $request->bulan ?? now()->format('Y-m');

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

        return view('siswa.riwayat', compact('siswa', 'absensis', 'bulan', 'stats'));
    }
}
