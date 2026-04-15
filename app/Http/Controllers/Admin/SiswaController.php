<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $siswas = Siswa::with(['kelas.jurusan', 'user'])
            ->when(
                $request->search,
                fn($q, $s) =>
                $q->where('nama', 'like', "%{$s}%")->orWhere('nis', 'like', "%{$s}%")
            )
            ->when(
                $request->kelas_id,
                fn($q, $id) =>
                $q->where('kelas_id', $id)
            )
            ->when(
                $request->status,
                fn($q, $s) =>
                $q->where('status', $s)
            )
            ->latest()
            ->paginate(10);

        $kelases = Kelas::with('jurusan')->orderBy('nama')->get();

        return view('admin.siswa.index', compact('siswas', 'kelases'));
    }

    public function create()
    {
        $kelases = Kelas::with('jurusan')->orderBy('nama')->get();
        return view('admin.siswa.form', compact('kelases'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id'      => 'required|exists:kelases,id',
            'nis'           => 'required|string|max:20|unique:siswas,nis',
            'nipd'          => 'nullable|string|max:20|unique:siswas,nipd', // ← tambahkan
            'nama'          => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string',
            'no_telepon'    => 'nullable|string|max:15',
            'status'        => 'required|in:aktif,tidak_aktif,pindah,lulus',
            'buat_akun'     => 'boolean',
        ]);

        $validated['no_barcode'] = Siswa::generateBarcode($validated['nipd']); // ← pakai nipd

        $siswa = Siswa::create($validated);

        // Buat akun login jika dicentang
        if ($request->boolean('buat_akun')) {
            User::create([
                'name'     => $validated['nama'],
                'username' => $validated['nis'],
                'email'    => strtolower($validated['nis']) . '@lentera.sch.id',
                'password' => Hash::make($validated['nis']), // default password = NIS
                'role'     => 'siswa',
            ]);
        }

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
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
            'nipd'          => 'required|string|max:20|unique:siswas,nipd,' . $siswa->id,  // ← tambahkan
            'nama'          => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string',
            'no_telepon'    => 'nullable|string|max:15',
            'status'        => 'required|in:aktif,tidak_aktif,pindah,lulus',
        ]);

        $validated['no_barcode'] = Siswa::generateBarcode($validated['nipd']); // ← pakai nipd

        $siswa->update($validated);

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return back()->with('success', 'Siswa berhasil dihapus.');
    }
}
