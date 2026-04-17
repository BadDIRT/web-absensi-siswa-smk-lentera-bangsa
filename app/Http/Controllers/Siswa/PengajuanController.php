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

        // Cek apakah status hari ini masih belum_absen
        $bisaAjukan = $siswa
            ? Absensi::where('siswa_id', $siswa->id)->where('tanggal', today())->where('status', 'belum_absen')->exists()
            : false;

        return view('siswa.pengajuan', compact('siswa', 'bisaAjukan'));
    }

    public function store(Request $request)
    {
        $siswa = Siswa::where('user_id', auth()->id())->first();

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // Cari record belum_absen hari ini
        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->where('tanggal', today())
            ->where('status', 'belum_absen')
            ->first();

        if (!$absensi) {
            return back()->with('error', 'Gagal mengajukan. Anda sudah memiliki catatan absensi hari ini.');
        }

        $validated = $request->validate([
            'jenis'       => 'required|in:izin,sakit',
            'keterangan'  => 'required|string|max:255',
            'foto_surat'  => 'required_if:jenis,sakit|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = [
            'status'     => $validated['jenis'],
            'keterangan' => $validated['keterangan'],
        ];

        if ($request->hasFile('foto_surat')) {
            $data['foto_surat'] = $request->file('foto_surat')->store('surat-sakit', 'public');
        }

        // UPDATE record, bukan CREATE baru
        $absensi->update($data);

        return redirect()->route('dashboard.siswa')->with('success', 'Pengajuan ' . $validated['jenis'] . ' berhasil dikirim.');
    }
}
