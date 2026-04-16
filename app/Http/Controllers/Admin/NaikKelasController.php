<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NaikKelasController extends Controller
{
    /**
     * Halaman utama - pilih metode
     */
    public function index()
    {
        $totalSiswaAktif = Siswa::where('status', 'aktif')->count();

        return view('admin.naik-kelas.index', compact('totalSiswaAktif'));
    }

    /**
     * Form naik kelas angkatan (mapping banyak kelas)
     */
    /**
     * Form naik kelas angkatan
     */
    public function angkatan(Request $request)
    {
        // Data untuk dropdown filter
        $tahunAjaranList = Kelas::distinct()->orderByDesc('tahun_ajaran')->pluck('tahun_ajaran');
        $jurusans = Jurusan::orderBy('nama')->get();

        // Query dasar: Tampilkan SEMUA KELAS (tanpa whereHas)
        $kelasesQuery = Kelas::with('jurusan');

        // Terapkan filter jika ada
        if ($request->filled('filter_tahun')) {
            $kelasesQuery->where('tahun_ajaran', $request->filter_tahun);
        }
        if ($request->filled('filter_tingkat')) {
            $kelasesQuery->where('tingkat', $request->filter_tingkat);
        }
        if ($request->filled('filter_jurusan')) {
            $kelasesQuery->where('jurusan_id', $request->filter_jurusan);
        }
        if ($request->filled('search')) {
            $kelasesQuery->where('nama', 'like', "%{$request->search}%");
        }

        $kelases = $kelasesQuery->orderBy('tingkat')
            ->orderBy('nama')
            ->get()
            ->each(fn($k) => $k->siswa_count = Siswa::where('kelas_id', $k->id)->where('status', 'aktif')->count());

        // Semua kelas yang tersedia sebagai tujuan (tanpa filter)
        $kelasTujuan = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('nama')->get();

        return view('admin.naik-kelas.angkatan', compact(
            'kelases',
            'kelasTujuan',
            'tahunAjaranList',
            'jurusans'
        ));
    }

    /**
     * Proses angkatan
     */
    /**
     * Proses angkatan
     */
    public function prosesAngkatan(Request $request)
    {
        $request->validate([
            'mapping' => 'required|array',
        ]);

        // Filter hanya yang tujuannya diisi (bukan string kosong)
        $validMappings = collect($request->mapping)->filter(fn($id) => filled($id));

        if ($validMappings->isEmpty()) {
            return back()->with('error', 'Pilih minimal satu kelas tujuan untuk dipindahkan.');
        }

        $totalUpdated = 0;

        DB::beginTransaction();
        try {
            foreach ($validMappings as $asalId => $tujuanId) {
                if ($asalId == $tujuanId) {
                    continue;
                }

                $totalUpdated += Siswa::where('kelas_id', $asalId)
                    ->where('status', 'aktif')
                    ->update(['kelas_id' => $tujuanId]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses.');
        }

        return back()->with('success', "{$totalUpdated} siswa berhasil dipindahkan.");
    }

    /**
     * Form naik kelas per kelas
     */
    public function perKelas(Request $request)
    {
        $kelases = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('nama')->get();
        $kelasTujuan = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('nama')->get();

        $selectedKelas = null;
        $siswas = collect();

        if ($request->filled('kelas_asal_id')) {
            $selectedKelas = Kelas::with('jurusan')->findOrFail($request->kelas_asal_id);
            $siswas = Siswa::where('kelas_id', $selectedKelas->id)
                ->where('status', 'aktif')
                ->orderBy('nama')
                ->get();
        }

        return view('admin.naik-kelas.per-kelas', compact('kelases', 'kelasTujuan', 'selectedKelas', 'siswas'));
    }

    /**
     * Proses per kelas
     */
    public function prosesPerKelas(Request $request)
    {
        $request->validate([
            'kelas_asal_id'   => 'required|exists:kelases,id',
            'kelas_tujuan_id' => 'required|exists:kelases,id',
            'siswa_ids'       => 'required|array|min:1',
            'siswa_ids.*'     => 'exists:siswas,id',
        ]);

        if ($request->kelas_asal_id == $request->kelas_tujuan_id) {
            return back()->with('error', 'Kelas asal dan tujuan tidak boleh sama.');
        }

        $kelasTujuan = Kelas::findOrFail($request->kelas_tujuan_id);

        DB::beginTransaction();
        try {
            $updated = Siswa::whereIn('id', $request->siswa_ids)
                ->where('status', 'aktif')
                ->update(['kelas_id' => $request->kelas_tujuan_id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses.');
        }

        return redirect()->route('admin.naik-kelas.per-kelas', [
            'kelas_asal_id' => $request->kelas_asal_id,
        ])->with('success', "{$updated} siswa berhasil dipindahkan ke kelas {$kelasTujuan->nama}.");
    }

    /**
     * Form naik kelas per siswa
     */
    public function perSiswa(Request $request)
    {
        $kelases = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('nama')->get();
        $kelasTujuan = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('nama')->get();

        $siswas = Siswa::with('kelas.jurusan')
            ->where('status', 'aktif')
            ->when($request->filled('filter_kelas'), fn($q) => $q->where('kelas_id', $request->filter_kelas))
            ->when($request->filled('search'), fn($q) => $q->where(function ($sq) use ($request) {
                $sq->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('nis', 'like', "%{$request->search}%");
            }))
            ->orderBy('kelas_id')
            ->orderBy('nama')
            ->get()
            ->groupBy(fn($s) => $s->kelas->nama);

        return view('admin.naik-kelas.per-siswa', compact('kelases', 'kelasTujuan', 'siswas'));
    }

    /**
     * Proses per siswa
     */
    public function prosesPerSiswa(Request $request)
    {
        $request->validate([
            'kelas_tujuan_id' => 'required|exists:kelases,id',
            'siswa_ids'       => 'required|array|min:1',
            'siswa_ids.*'     => 'exists:siswas,id',
        ]);

        $kelasTujuan = Kelas::findOrFail($request->kelas_tujuan_id);

        DB::beginTransaction();
        try {
            $updated = Siswa::whereIn('id', $request->siswa_ids)
                ->where('status', 'aktif')
                ->update(['kelas_id' => $request->kelas_tujuan_id]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses.');
        }

        return redirect()->route('admin.naik-kelas.per-siswa', [
            'filter_kelas' => $request->filter_kelas,
            'search'       => $request->search,
        ])->with('success', "{$updated} siswa berhasil dipindahkan ke kelas {$kelasTujuan->nama}.");
    }
}
