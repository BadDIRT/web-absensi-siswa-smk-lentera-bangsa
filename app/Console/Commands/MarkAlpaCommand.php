<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Console\Command;

class MarkAlpaCommand extends Command
{
    protected $signature = 'absensi:mark-alpa';
    protected $description = 'Menandai siswa aktif yang tidak absen hari ini sebagai alpa (Jalankan di akhir jam sekolah)';

    public function handle()
    {
        $today = today()->toDateString();

        // Cari siswa aktif yang BELUM punya catatan absensi hari ini
        $siswaBelumAbsen = Siswa::where('status', 'aktif')
            ->whereDoesntHave('absensis', fn($q) => $q->where('tanggal', $today))
            ->pluck('id');

        if ($siswaBelumAbsen->isEmpty()) {
            $this->info('Tidak ada siswa yang perlu ditandai alpa hari ini.');
            return 0;
        }

        // Insert alpa ke tabel absensis
        $data = $siswaBelumAbsen->map(fn($id) => [
            'siswa_id'   => $id,
            'scanned_by' => null,
            'tanggal'    => $today,
            'jam_masuk'  => null,
            'status'     => 'alpa',
            'keterangan' => 'Tidak hadir tanpa keterangan',
            'created_at' => now(),
        ])->toArray();

        Absensi::insert($data);

        $this->info("Berhasil menandai {$siswaBelumAbsen->count()} siswa sebagai alpa.");
        return 0;
    }
}
