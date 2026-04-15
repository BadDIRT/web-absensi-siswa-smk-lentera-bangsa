<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RekapController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();
        $kelasId = $request->kelas_id;
        $jurusanId = $request->jurusan_id;

        // Build query kelas berdasarkan filter
        $kelasesQuery = Kelas::with('jurusan')
            ->when($jurusanId, fn($q) => $q->where('jurusan_id', $jurusanId))
            ->orderBy('tingkat')
            ->orderBy('nama');

        $kelases = $kelasesQuery->get();

        // Hitung rekap per kelas
        $rekap = [];
        foreach ($kelases as $kelas) {
            $siswaIds = $kelas->siswas()->where('status', 'aktif')->pluck('id');
            $totalSiswa = $siswaIds->count();

            $hadir = Absensi::whereIn('siswa_id', $siswaIds)
                ->where('tanggal', $tanggal)
                ->where('status', 'hadir')->count();
            $izin = Absensi::whereIn('siswa_id', $siswaIds)
                ->where('tanggal', $tanggal)
                ->where('status', 'izin')->count();
            $sakit = Absensi::whereIn('siswa_id', $siswaIds)
                ->where('tanggal', $tanggal)
                ->where('status', 'sakit')->count();
            $alpa = Absensi::whereIn('siswa_id', $siswaIds)
                ->where('tanggal', $tanggal)
                ->where('status', 'alpa')->count();

            $rekap[] = [
                'kelas'     => $kelas,
                'total'     => $totalSiswa,
                'hadir'     => $hadir,
                'izin'      => $izin,
                'sakit'     => $sakit,
                'alpa'      => $alpa,
                'belum'     => $totalSiswa - ($hadir + $izin + $sakit + $alpa),
            ];
        }

        // Total keseluruhan
        $totalAll = array_reduce($rekap, fn($carry, $item) => [
            'total' => ($carry['total'] ?? 0) + $item['total'],
            'hadir' => ($carry['hadir'] ?? 0) + $item['hadir'],
            'izin'  => ($carry['izin'] ?? 0) + $item['izin'],
            'sakit' => ($carry['sakit'] ?? 0) + $item['sakit'],
            'alpa'  => ($carry['alpa'] ?? 0) + $item['alpa'],
            'belum' => ($carry['belum'] ?? 0) + $item['belum'],
        ], []);

        $jurusans = Jurusan::orderBy('nama')->get();
        $kelasList = $jurusanId
            ? Kelas::where('jurusan_id', $jurusanId)->orderBy('nama')->get()
            : collect();

        return view('admin.rekap.index', compact(
            'tanggal',
            'jurusanId',
            'kelasId',
            'jurusans',
            'kelasList',
            'rekap',
            'totalAll'
        ));
    }

    public function detail(Request $request)
    {
        $tanggal = $request->tanggal ?? now()->toDateString();
        $kelasId = $request->kelas_id;

        $kelas = Kelas::with('jurusan')->findOrFail($kelasId);

        $siswas = Siswa::where('kelas_id', $kelasId)
            ->where('status', 'aktif')
            ->with(['absensis' => fn($q) => $q->where('tanggal', $tanggal)])
            ->orderBy('nama')
            ->get();

        return view('admin.rekap.detail', compact('tanggal', 'kelas', 'siswas'));
    }
}
