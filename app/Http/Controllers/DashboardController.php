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
        // 1. TUTUP HARI KEMARIN: Ubah belum_absen -> alpa (jika Admin buka sistem di hari baru)
        Absensi::finalizeAlpaKemarin();

        // 2. BUKA HARI INI: Buat record belum_absen jika belum ada
        Absensi::generateBelumAbsenHariIni();

        $tanggal = today();
        $totalSiswaAktif = Siswa::where('status', 'aktif')->count();

        $hadirHariIni = Absensi::where('tanggal', $tanggal)->where('status', 'hadir')->count();
        $izinSakit = Absensi::where('tanggal', $tanggal)->whereIn('status', ['izin', 'sakit'])->count();
        $belumAbsen = Absensi::where('tanggal', $tanggal)->where('status', 'belum_absen')->count();

        $tidakHadir = $izinSakit + $belumAbsen;

        return view('dashboard.admin', [
            'stats' => [
                'total_siswa'    => $totalSiswaAktif,
                'hadir_hari_ini' => $hadirHariIni,
                'tidak_hadir'    => $tidakHadir,
                'belum_absen'    => $belumAbsen,
                'total_kelas'    => Kelas::count(),
            ],
            'absensiTerakhir' => Absensi::with('siswa.kelas')
                ->where('tanggal', $tanggal)
                ->where('status', '!=', 'belum_absen')
                ->latest('jam_masuk')
                ->take(5)
                ->get(),
        ]);
    }

    public function scanner()
    {
        // Scanner tidak perlu finalize kemarin, cukup generate hari ini
        Absensi::generateBelumAbsenHariIni();

        return view('dashboard.scanner', [
            'recentScans' => Absensi::with('siswa.kelas', 'scanner')
                ->where('tanggal', today())
                ->where('status', '!=', 'belum_absen')
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
                ->where('status', '!=', 'belum_absen')
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
