<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard Administrator.
     */
    public function admin()
    {
        // Data dummy — nanti akan diganti dengan query database
        $stats = [
            'total_siswa'    => 0,
            'hadir_hari_ini' => 0,
            'tidak_hadir'    => 0,
            'total_kelas'    => 0,
        ];

        return view('dashboard.admin', compact('stats'));
    }

    /**
     * Dashboard Scanner — halaman utama scan barcode.
     */
    public function scanner()
    {
        // Data dummy — nanti akan diisi riwayat scan terbaru
        $recentScans = collect();

        return view('dashboard.scanner', compact('recentScans'));
    }

    /**
     * Dashboard Siswa.
     */
    public function siswa()
    {
        $user = Auth::user();

        // Data dummy — nanti akan diganti dengan data absensi siswa
        $absensiBulanIni = [
            'hadir'  => 0,
            'izin'   => 0,
            'sakit'  => 0,
            'alpa'   => 0,
        ];

        $riwayatAbsensi = collect();

        return view('dashboard.siswa', compact('user', 'absensiBulanIni', 'riwayatAbsensi'));
    }
}
