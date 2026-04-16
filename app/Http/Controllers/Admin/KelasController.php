<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $kelases = Kelas::with('jurusan')
            ->withCount(['siswas as siswa_aktif_count' => fn($q) => $q->where('status', 'aktif')])
            ->when(
                $request->search,
                fn($q, $s) =>
                $q->where('nama', 'like', "%{$s}%")
            )
            ->when(
                $request->jurusan_id,
                fn($q, $id) =>
                $q->where('jurusan_id', $id)
            )
            ->when(
                $request->tingkat,
                fn($q, $t) =>
                $q->where('tingkat', $t)
            )
            ->latest()
            ->paginate(10);

        $jurusans = Jurusan::orderBy('nama')->get();

        return view('admin.kelas.index', compact('kelases', 'jurusans'));
    }

    public function create()
    {
        $jurusans = Jurusan::orderBy('nama')->get();
        return view('admin.kelas.form', compact('jurusans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jurusan_id'   => 'required|exists:jurusans,id',
            'nama'         => 'required|string|max:50',
            'tingkat'      => 'required|in:10,11,12',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        Kelas::create($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show(Kelas $kela)
    {
        $kela->load('jurusan');

        $siswas = $kela->siswas()
            ->with('user')
            ->where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        $stats = [
            'aktif'       => $kela->siswas()->where('status', 'aktif')->count(),
            'tidak_aktif' => $kela->siswas()->where('status', 'tidak_aktif')->count(),
            'pindah'      => $kela->siswas()->where('status', 'pindah')->count(),
            'lulus'       => $kela->siswas()->where('status', 'lulus')->count(),
        ];

        return view('admin.kelas.show', compact('kela', 'siswas', 'stats'));
    }

    public function edit(Kelas $kela)
    {
        $jurusans = Jurusan::orderBy('nama')->get();
        return view('admin.kelas.form', ['kelas' => $kela, 'jurusans' => $jurusans]);
    }

    public function update(Request $request, Kelas $kela)
    {
        $validated = $request->validate([
            'jurusan_id'   => 'required|exists:jurusans,id',
            'nama'         => 'required|string|max:50',
            'tingkat'      => 'required|in:10,11,12',
            'tahun_ajaran' => 'required|string|max:9',
        ]);

        $kela->update($validated);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
