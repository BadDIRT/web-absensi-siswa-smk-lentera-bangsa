<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateUserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──
        $users = [
            ['name' => 'Administrator',   'username' => 'admin',    'email' => 'admin@lentera.sch.id',    'password' => 'password', 'role' => 'administrator'],
            ['name' => 'Petugas Scanner', 'username' => 'scanner1', 'email' => 'scanner@lentera.sch.id',  'password' => 'password', 'role' => 'scanner'],
        ];

        foreach ($users as $u) {
            User::firstOrCreate(['username' => $u['username']], [
                ...$u,
                'password' => Hash::make($u['password']),
            ]);
        }

        // ── Jurusan ──
        $jurusans = [
            ['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak'],
            ['kode' => 'TKJ', 'nama' => 'Teknik Komputer dan Jaringan'],
            ['kode' => 'MM',  'nama' => 'Multimedia'],
        ];

        foreach ($jurusans as $j) {
            Jurusan::firstOrCreate(['kode' => $j['kode']], $j);
        }

        // ── Kelas ──
        $rpl = Jurusan::where('kode', 'RPL')->first();
        $tkj = Jurusan::where('kode', 'TKJ')->first();

        $kelases = [
            ['jurusan_id' => $rpl->id, 'nama' => 'X RPL 1',  'tingkat' => 10, 'tahun_ajaran' => '2024/2025'],
            ['jurusan_id' => $rpl->id, 'nama' => 'XI RPL 1', 'tingkat' => 11, 'tahun_ajaran' => '2024/2025'],
            ['jurusan_id' => $tkj->id, 'nama' => 'X TKJ 1',  'tingkat' => 10, 'tahun_ajaran' => '2024/2025'],
        ];

        foreach ($kelases as $k) {
            Kelas::firstOrCreate(['nama' => $k['nama']], $k);
        }

        // ── Siswa ──
        $kelasX = Kelas::where('nama', 'X RPL 1')->first();

        $siswaList = [
            ['nis' => '2024001', 'nipd' => '242510181', 'nama' => 'Ahmad Rizky',       'jenis_kelamin' => 'L'],
            ['nis' => '2024002', 'nipd' => '242510182', 'nama' => 'Siti Nurhaliza',    'jenis_kelamin' => 'P'],
            ['nis' => '2024003', 'nipd' => '242510183', 'nama' => 'Budi Santoso',      'jenis_kelamin' => 'L'],
            ['nis' => '2024004', 'nipd' => '242510184', 'nama' => 'Dewi Lestari',      'jenis_kelamin' => 'P'],
            ['nis' => '2024005', 'nipd' => '242510185', 'nama' => 'Rizal Firmansyah',  'jenis_kelamin' => 'L'],
        ];

        foreach ($siswaList as $s) {
            $siswa = Siswa::firstOrCreate(
                ['nis' => $s['nis']],
                [
                    ...$s,
                    'kelas_id'   => $kelasX->id,
                    // Ini otomatis menghasilkan format A242510181A (karena sudah diubah di Model)
                    'no_barcode' => Siswa::generateBarcode($s['nipd']),
                    'status'     => 'aktif',
                ]
            );

            // Buat akun login untuk siswa pertama
            if ($s['nis'] === '2024001' && !$siswa->user_id) {
                $userSiswa = User::firstOrCreate(
                    ['username' => '2024001'],
                    [
                        'name'     => $s['nama'],
                        'email'    => '2024001@lentera.sch.id',
                        'password' => Hash::make('password'),
                        'role'     => 'siswa',
                    ]
                );
                $siswa->update(['user_id' => $userSiswa->id]);
            }
        }

        // Siswa contoh yang TIDAK punya NIPD (tidak bisa di-scan)
        Siswa::firstOrCreate(
            ['nis' => '2024006'],
            [
                'kelas_id'      => $kelasX->id,
                'nipd'          => null,
                'nama'          => 'Lisa Permata',
                'jenis_kelamin' => 'P',
                'status'        => 'aktif',
                'no_barcode'    => null,
            ]
        );

        $this->command->info('Seeder berhasil dijalankan.');
        $this->command->info('--------------------------------');
        $this->command->info('Login Admin   : admin / password');
        $this->command->info('Login Scanner : scanner1 / password');
        $this->command->info('Login Siswa   : 2024001 / password');
        $this->command->info('--------------------------------');
        $this->command->info('NIPD contoh   : 242510181');
        $this->command->info('Format Barcode: A242510181A (CODABAR)');
    }
}
