<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboard.admin', [
            'stats' => [
                'total_siswa'    => Siswa::where('status', 'aktif')->count(),
                'hadir_hari_ini' => Absensi::where('tanggal', today())->where('status', 'hadir')->count(),
                'tidak_hadir'    => Absensi::where('tanggal', today())->whereIn('status', ['izin', 'sakit', 'alpa'])->count(),
                'total_kelas'    => Kelas::count(),
            ],
            'absensiTerakhir' => Absensi::with('siswa.kelas')
                ->where('tanggal', today())
                ->latest('jam_masuk')
                ->take(5)
                ->get(),
        ]);
    }

    public function scanner()
    {
        return view('dashboard.scanner', [
            'recentScans' => Absensi::with('siswa.kelas', 'scanner')
                ->where('tanggal', today())
                ->latest('jam_masuk')
                ->take(10)
                ->get(),
        ]);
    }

    public function siswa()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        $bulan = now()->format('Y-m');

        $stats = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpa' => 0];
        $riwayatAbsensi = collect();

        if ($siswa) {
            $riwayatAbsensi = Absensi::where('siswa_id', $siswa->id)
                ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$bulan])
                ->orderByDesc('tanggal')
                ->get();

            $stats = [
                'hadir' => $riwayatAbsensi->where('status', 'hadir')->count(),
                'izin'  => $riwayatAbsensi->where('status', 'izin')->count(),
                'sakit' => $riwayatAbsensi->where('status', 'sakit')->count(),
                'alpa'  => $riwayatAbsensi->where('status', 'alpa')->count(),
            ];
        }

        return view('dashboard.siswa', [
            'user'            => $user,
            'siswa'           => $siswa,
            'absensiBulanIni' => $stats,
            'riwayatAbsensi'  => $riwayatAbsensi,
        ]);
    }
}
