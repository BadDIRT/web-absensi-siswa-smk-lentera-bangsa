<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->first();

        // Cek apakah sudah ada catatan absensi hari ini
        $sudahAbsen = $siswa
            ? Absensi::where('siswa_id', $siswa->id)->where('tanggal', today())->exists()
            : true;

        return view('siswa.pengajuan', compact('siswa', 'sudahAbsen'));
    }

    public function store(Request $request)
    {
        $siswa = Siswa::where('user_id', auth()->id())->first();

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // Cegah double pengajuan di hari yang sama
        if (Absensi::where('siswa_id', $siswa->id)->where('tanggal', today())->exists()) {
            return back()->with('error', 'Anda sudah memiliki catatan absensi atau pengajuan hari ini.');
        }

        $validated = $request->validate([
            'jenis'       => 'required|in:izin,sakit',
            'keterangan'  => 'required|string|max:255',
            'foto_surat'  => 'required_if:jenis,sakit|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'siswa_id'   => $siswa->id,
            'scanned_by' => null, // Null karena diajukan mandiri, bukan oleh scanner
            'tanggal'    => today(),
            'jam_masuk'  => null,
            'status'     => $validated['jenis'],
            'keterangan' => $validated['keterangan'],
        ];

        if ($request->hasFile('foto_surat')) {
            $data['foto_surat'] = $request->file('foto_surat')->store('surat-sakit', 'public');
        }

        Absensi::create($data);

        return redirect()->route('dashboard.siswa')->with('success', 'Pengajuan ' . $validated['jenis'] . ' berhasil dikirim.');
    }
}
