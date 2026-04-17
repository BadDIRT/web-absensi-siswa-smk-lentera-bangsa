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
        // TRIGGER: Pastikan data hari kemarin sudah difinalisasi jadi alpa
        Absensi::finalizeAlpaKemarin();

        $tanggal = $request->tanggal ?? now()->toDateString();
        $kelasId = $request->kelas_id;
        $jurusanId = $request->jurusan_id;
        $tingkat = $request->tingkat; // DITAMBAHKAN

        // Build query kelas berdasarkan filter
        $kelasesQuery = Kelas::with('jurusan')
            ->when($jurusanId, fn($q) => $q->where('jurusan_id', $jurusanId))
            ->when($tingkat, fn($q) => $q->where('tingkat', $tingkat))
            ->when($kelasId, fn($q) => $q->where('id', $kelasId)) // <- YANG INI DITAMBAHKAN
            ->orderBy('tingkat')
            ->orderBy('nama');

        $kelases = $kelasesQuery->get();

        // Hitung rekap per kelas
        $rekap = [];
        foreach ($kelases as $kelas) {
            $siswaIds = $kelas->siswas()->where('status', 'aktif')->pluck('id');
            $totalSiswa = $siswaIds->count();

            $belumAbsen = Absensi::whereIn('siswa_id', $siswaIds)
                ->where('tanggal', $tanggal)
                ->where('status', 'belum_absen')->count();

            $hadir = Absensi::whereIn('siswa_id', $siswaIds)->where('tanggal', $tanggal)->where('status', 'hadir')->count();
            $izin = Absensi::whereIn('siswa_id', $siswaIds)->where('tanggal', $tanggal)->where('status', 'izin')->count();
            $sakit = Absensi::whereIn('siswa_id', $siswaIds)->where('tanggal', $tanggal)->where('status', 'sakit')->count();
            $alpa = Absensi::whereIn('siswa_id', $siswaIds)->where('tanggal', $tanggal)->where('status', 'alpa')->count();

            $rekap[] = [
                'kelas'      => $kelas,
                'total'      => $totalSiswa,
                'hadir'      => $hadir,
                'izin'       => $izin,
                'sakit'      => $sakit,
                'alpa'       => $alpa,
                'belum'      => $belumAbsen,
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

        // PERBAIKAN: Filter dropdown kelas berdasarkan jurusan DAN tingkat yang dipilih
        $kelasListQuery = Kelas::orderBy('tingkat')->orderBy('nama');
        if ($jurusanId) {
            $kelasListQuery->where('jurusan_id', $jurusanId);
        }
        if ($tingkat) {
            $kelasListQuery->where('tingkat', $tingkat);
        }
        $kelasList = $kelasListQuery->get();

        return view('admin.rekap.index', compact(
            'tanggal',
            'jurusanId',
            'kelasId',
            'tingkat', // DITAMBAHKAN
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

        // DITAMBAHKAN: Hitung statistik untuk header dan teks WhatsApp
        $stats = [
            'total'       => $siswas->count(),
            'hadir'       => $siswas->filter(fn($s) => $s->absensis->first()?->status === 'hadir')->count(),
            'izin'        => $siswas->filter(fn($s) => $s->absensis->first()?->status === 'izin')->count(),
            'sakit'       => $siswas->filter(fn($s) => $s->absensis->first()?->status === 'sakit')->count(),
            'alpa'        => $siswas->filter(fn($s) => $s->absensis->first()?->status === 'alpa')->count(),
            'belum_absen' => $siswas->filter(fn($s) => !$s->absensis->first() || $s->absensis->first()->status === 'belum_absen')->count(),
        ];

        return view('admin.rekap.detail', compact('tanggal', 'kelas', 'siswas', 'stats'));
    }

    public function siswa(Request $request, Siswa $siswa)
    {
        $siswa->load('kelas.jurusan');

        // Validasi & format tanggal
        $dari = $request->dari ? \Carbon\Carbon::parse($request->dari)->startOfDay() : now()->startOfMonth();
        $sampai = $request->sampai ? \Carbon\Carbon::parse($request->sampai)->endOfDay() : now()->endOfDay();

        // 1. Hitung semua status (termasuk belum_absen) untuk keperluan statistik card
        $allStatuses = Absensi::where('siswa_id', $siswa->id)
            ->whereBetween('tanggal', [$dari, $sampai])
            ->get()
            ->countBy('status');

        $stats = [
            'hadir'       => $allStatuses->get('hadir', 0),
            'izin'        => $allStatuses->get('izin', 0),
            'sakit'       => $allStatuses->get('sakit', 0),
            'alpa'        => $allStatuses->get('alpa', 0),
            'belum_absen' => $allStatuses->get('belum_absen', 0),
            'total'       => $allStatuses->sum(),
        ];

        // 2. Query untuk ditampilkan di tabel riwayat (exclude 'belum_absen' agar tabel tidak terlalu panjang)
        $absensis = Absensi::where('siswa_id', $siswa->id)
            ->whereBetween('tanggal', [$dari, $sampai])
            ->where('status', '!=', 'belum_absen')
            ->orderByDesc('tanggal')
            ->get();

        return view('admin.rekap.siswa', compact('siswa', 'absensis', 'stats', 'dari', 'sampai'));
    }
}
