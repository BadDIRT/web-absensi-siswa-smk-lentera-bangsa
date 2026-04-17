<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Absensi extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'siswa_id',
        'scanned_by',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'keterangan',
        'foto_surat',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * AUTO-GENERATE: Membuat record 'belum_absen' untuk siswa aktif yang belum ada catatan hari ini.
     * Dipanggil saat Admin/Scanner membuka dashboard.
     */
    public static function generateBelumAbsenHariIni(): void
    {
        $today = today()->toDateString();

        // Ambil ID siswa yang SUDAH punya record absensi hari ini (status apapun)
        $sudahAdaRecord = Absensi::where('tanggal', $today)->pluck('siswa_id');

        // Cari siswa aktif yang BELUM punya record
        $siswaBelumAbsen = Siswa::where('status', 'aktif')
            ->whereNotIn('id', $sudahAdaRecord)
            ->pluck('id');

        // Insert batch agar lebih cepat
        $data = [];
        $now = now();
        foreach ($siswaBelumAbsen as $siswaId) {
            $data[] = [
                'siswa_id'   => $siswaId,
                'tanggal'    => $today,
                'status'     => 'belum_absen',
                'created_at' => $now,
            ];
        }

        if (!empty($data)) {
            // Chunk insert untuk mencegah memory limit jika siswa sangat banyak
            foreach (array_chunk($data, 500) as $chunk) {
                DB::table('absensis')->insert($chunk);
            }
        }
    }

    /**
     * FINALIZE ALPA: Mengubah status 'belum_absen' menjadi 'alpa' untuk tanggal yang sudah lewat.
     * Dipanggil saat Admin membuka dashboard/rekap di hari baru.
     */
    public static function finalizeAlpaKemarin(): void
    {
        // Cari semua record yang masih 'belum_absen' dengan tanggal LEBIH KECIL dari hari ini
        $affectedRows = Absensi::where('status', 'belum_absen')
            ->where('tanggal', '<', today()->toDateString())
            ->update(['status' => 'alpa']);

        // Opsional: Log jika ada yang diubah (bisa di-comment jika tidak diperlukan)
        // if ($affectedRows > 0) {
        //     \Log::info("Auto-Alpa: {$affectedRows} record absensi kemarin berhasil diubah menjadi alpa.");
        // }
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'hadir'       => 'Hadir',
            'izin'        => 'Izin',
            'sakit'       => 'Sakit',
            'alpa'        => 'Alpa',
            'belum_absen' => 'Belum Absen',
            default       => '—',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'hadir'       => 'green',
            'izin'        => 'blue',
            'sakit'       => 'amber',
            'alpa'        => 'red',
            'belum_absen' => 'gray',
            default       => 'gray',
        };
    }
}
