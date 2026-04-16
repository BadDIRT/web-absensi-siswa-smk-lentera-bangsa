<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Picqer\Barcode\BarcodeGeneratorPNG;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $siswas = Siswa::with(['kelas.jurusan', 'user'])
            ->when($request->search, fn($q, $s) => $q->where('nama', 'like', "%{$s}%")->orWhere('nis', 'like', "%{$s}%"))
            ->when($request->kelas_id, fn($q, $id) => $q->where('kelas_id', $id))
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->latest()
            ->paginate(10);

        // Ubah bagian ini: urutkan tahun ajaran terbaru di atas, lalu nama kelas
        $kelases = Kelas::with('jurusan')->orderByDesc('tahun_ajaran')->orderBy('nama')->get();

        return view('admin.siswa.index', compact('siswas', 'kelases'));
    }

    public function create()
    {
        $kelases = Kelas::with('jurusan')->orderBy('nama')->get();
        return view('admin.siswa.form', [
            'kelases' => $kelases,
            'siswa'   => null,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id'      => 'required|exists:kelases,id',
            'nis'           => 'required|string|max:20|unique:siswas,nis',
            'nipd'          => 'nullable|string|max:20|unique:siswas,nipd',
            'nama'          => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string',
            'no_telepon'    => 'nullable|string|max:15',
            'status'        => 'required|in:aktif,tidak_aktif,pindah,lulus',
            'buat_akun'     => 'boolean',
        ]);

        $validated['no_barcode'] = filled($validated['nipd'])
            ? Siswa::generateBarcode($validated['nipd'])
            : null;

        $siswa = Siswa::create($validated);

        if ($request->boolean('buat_akun')) {
            User::create([
                'name'     => $validated['nama'],
                'username' => $validated['nis'],
                'email'    => strtolower($validated['nis']) . '@lentera.sch.id',
                'password' => Hash::make($validated['nis']),
                'role'     => 'siswa',
            ]);
        }

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas.jurusan', 'user']);

        $bulan = now()->format('Y-m');

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

        $barcodeImage = null;
        if ($siswa->no_barcode) {
            $generator = new BarcodeGeneratorPNG();
            $barcodeImage = 'data:image/png;base64,' . base64_encode(
                $generator->getBarcode($siswa->no_barcode, $generator::TYPE_CODE_128, 2, 50)
            );
        }

        return view('admin.siswa.show', compact('siswa', 'absensis', 'stats', 'bulan', 'barcodeImage'));
    }

    public function edit(Siswa $siswa)
    {
        $kelases = Kelas::with('jurusan')->orderBy('nama')->get();
        return view('admin.siswa.form', compact('siswa', 'kelases'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'kelas_id'      => 'required|exists:kelases,id',
            'nis'           => 'required|string|max:20|unique:siswas,nis,' . $siswa->id,
            'nipd'          => 'nullable|string|max:20|unique:siswas,nipd,' . $siswa->id,
            'nama'          => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string',
            'no_telepon'    => 'nullable|string|max:15',
            'status'        => 'required|in:aktif,tidak_aktif,pindah,lulus',
        ]);

        $validated['no_barcode'] = filled($validated['nipd'])
            ? Siswa::generateBarcode($validated['nipd'])
            : null;

        $siswa->update($validated);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return back()->with('success', 'Siswa berhasil dihapus.');
    }
}
