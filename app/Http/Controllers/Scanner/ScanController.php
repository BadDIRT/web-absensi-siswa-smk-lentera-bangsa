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

        // KASUS 1: Belum ada record atau statusnya masih BELUM ABSEN -> Absen Masuk
        if (!$absensi || $absensi->status === 'belum_absen') {

            if ($absensi) {
                // Update record yang sudah ada (status belum_absen -> hadir)
                $absensi->update([
                    'scanned_by' => auth()->id(),
                    'jam_masuk'  => $now->format('H:i:s'),
                    'status'     => 'hadir',
                ]);
            } else {
                // Fallback: jika ternyata belum ada record sama sekali (jarang terjadi)
                $absensi = Absensi::create([
                    'siswa_id'   => $siswa->id,
                    'scanned_by' => auth()->id(),
                    'tanggal'    => $today,
                    'jam_masuk'  => $now->format('H:i:s'),
                    'status'     => 'hadir',
                ]);
            }

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

            // KASUS 2: Sudah Hadir, tapi belum Jam Pulang -> Absen Pulang
        } elseif ($absensi->status === 'hadir' && !$absensi->jam_pulang) {

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

            // KASUS 3: Sudah lengkap (Sudah pulang / Status izin/sakit/alpa)
        } else {
            $customMsg = ($absensi->status === 'izin' || $absensi->status === 'sakit')
                ? 'Siswa hari ini tercatat ' . $absensi->statusLabel() . '.'
                : 'Siswa sudah absen masuk dan pulang hari ini.';

            return response()->json([
                'success' => false,
                'type'    => 'duplicate',
                'message' => $customMsg,
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
