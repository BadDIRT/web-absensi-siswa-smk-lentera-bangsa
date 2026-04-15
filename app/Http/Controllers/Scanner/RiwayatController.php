<?php

namespace App\Http\Controllers\Scanner;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();

        $absensis = Absensi::with(['siswa.kelas.jurusan', 'scanner'])
            ->where('tanggal', $tanggal)
            ->when(
                $request->search,
                fn($q, $s) =>
                $q->whereHas(
                    'siswa',
                    fn($sq) =>
                    $sq->where('nama', 'like', "%{$s}%")->orWhere('nis', 'like', "%{$s}%")
                )
            )
            ->latest('jam_masuk')
            ->paginate(20);

        $totalScan = Absensi::where('tanggal', $tanggal)->count();
        $totalHadir = Absensi::where('tanggal', $tanggal)->where('status', 'hadir')->count();

        return view('scanner.riwayat', compact('absensis', 'tanggal', 'totalScan', 'totalHadir'));
    }
}
