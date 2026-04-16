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
        $tanggal = today();
        $totalSiswaAktif = Siswa::where('status', 'aktif')->count();

        $hadirHariIni = Absensi::where('tanggal', $tanggal)->where('status', 'hadir')->count();

        // Siswa yang sudah punya record absensi apapun hari ini
        $sudahDiabsen = Absensi::where('tanggal', $tanggal)->distinct('siswa_id')->count('siswa_id');

        // Belum absen = siswa aktif yang belum ada baris di tabel absensis hari ini
        $belumAbsen = max(0, $totalSiswaAktif - $sudahDiabsen);

        // Izin + Sakit + Alpa
        $izinSakitAlpa = Absensi::where('tanggal', $tanggal)
            ->whereIn('status', ['izin', 'sakit', 'alpa'])
            ->count();

        // Tidak hadir = izin/sakit/alpa + belum absen sama sekali
        $tidakHadir = $izinSakitAlpa + $belumAbsen;

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
