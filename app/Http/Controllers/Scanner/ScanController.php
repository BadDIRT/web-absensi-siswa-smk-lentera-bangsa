<?php

namespace App\Http\Controllers\Scanner;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ScanController extends Controller
{
    public function process(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        $barcode = trim($request->input('barcode'));

        // Validasi: Format CODABAR diawali A, angka 5-20 digit, dan diakhiri A
        if (!preg_match('/^A[0-9]{5,20}A$/', $barcode)) {
            return response()->json([
                'success' => false,
                'message' => 'Format kode CODABAR tidak valid.',
            ], 422);
        }

        $siswa = Siswa::where('no_barcode', $barcode)
            ->where('status', 'aktif')
            ->with('kelas')
            ->first();

        // ... bagian bawah (logic masuk/pulang) tetap sama persis ...

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => "Siswa dengan kode tersebut tidak ditemukan atau tidak aktif.",
            ], 404);
        }

        $today = Carbon::today()->toDateString();
        $now   = Carbon::now();

        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->where('tanggal', $today)
            ->first();

        if (!$absensi) {
            Absensi::create([
                'siswa_id'   => $siswa->id,
                'scanned_by' => auth()->id(),
                'tanggal'    => $today,
                'jam_masuk'  => $now->format('H:i:s'),
                'status'     => 'hadir',
            ]);

            return response()->json([
                'success' => true,
                'type'    => 'masuk',
                'message' => 'Absen masuk berhasil',
                'data'    => [
                    'nama'  => $siswa->nama,
                    'nipd'  => $siswa->nipd,
                    'nis'   => $siswa->nis,
                    'kelas' => $siswa->kelas->nama,
                    'time'  => $now->format('H:i:s'),
                ],
            ]);
        } elseif (!$absensi->jam_pulang) {
            $absensi->update([
                'jam_pulang' => $now->format('H:i:s'),
            ]);

            return response()->json([
                'success' => true,
                'type'    => 'pulang',
                'message' => 'Absen pulang berhasil',
                'data'    => [
                    'nama'       => $siswa->nama,
                    'nipd'       => $siswa->nipd,
                    'nis'        => $siswa->nis,
                    'kelas'      => $siswa->kelas->nama,
                    'jam_masuk'  => $absensi->jam_masuk,
                    'jam_pulang' => $now->format('H:i:s'),
                ],
            ]);
        } else {
            return response()->json([
                'success' => false,
                'type'    => 'duplicate',
                'message' => 'Siswa sudah absen masuk dan pulang hari ini.',
                'data'    => [
                    'nama'       => $siswa->nama,
                    'nipd'       => $siswa->nipd,
                    'jam_masuk'  => $absensi->jam_masuk,
                    'jam_pulang' => $absensi->jam_pulang,
                ],
            ], 422);
        }
    }
}
