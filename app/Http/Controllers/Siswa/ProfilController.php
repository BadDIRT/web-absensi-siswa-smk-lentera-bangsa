<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $siswa = Siswa::with('kelas.jurusan')->where('user_id', $user->id)->first();

        return view('siswa.profil', compact('user', 'siswa'));
    }

    public function update(Request $request)
    {
        $siswa = Siswa::where('user_id', auth()->id())->first();

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $validated = $request->validate([
            'no_telepon' => 'nullable|string|max:15',
            'alamat'     => 'nullable|string',
        ]);

        $siswa->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
