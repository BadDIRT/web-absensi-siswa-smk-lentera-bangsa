<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateUserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Hapus data lama agar tidak duplikat saat re-seed ──
        Absensi::query()->delete();
        Siswa::query()->delete();
        Kelas::query()->delete();
        User::where('role', 'siswa')->delete();
        Jurusan::query()->delete();

        // ── 1. Users (Admin & Scanner) ──
        $users = [
            ['name' => 'Administrator',   'username' => 'admin',    'email' => 'admin@lentera.sch.id',   'password' => 'password', 'role' => 'administrator'],
            ['name' => 'Petugas Scanner', 'username' => 'scanner1', 'email' => 'scanner@lentera.sch.id', 'password' => 'password', 'role' => 'scanner'],
            ['name' => 'Scanner Pagi',    'username' => 'scanner2', 'email' => 'scanner2@lentera.sch.id', 'password' => 'password', 'role' => 'scanner'],
        ];

        foreach ($users as $u) {
            User::firstOrCreate(['username' => $u['username']], [
                ...$u,
                'password' => Hash::make($u['password']),
            ]);
        }
        $scannerUser = User::where('username', 'scanner1')->first();

        // ── 2. Jurusan ──
        $jurusans = [
            ['kode' => 'RPL', 'nama' => 'Rekayasa Perangkat Lunak', 'keterangan' => 'Fokus pada pemrograman & pengembangan aplikasi'],
            ['kode' => 'TKJ', 'nama' => 'Teknik Komputer dan Jaringan', 'keterangan' => 'Fokus pada jaringan komputer & infrastruktur'],
            ['kode' => 'MM',  'nama' => 'Multimedia', 'keterangan' => 'Fokus pada desain grafis, video & animasi'],
        ];

        foreach ($jurusans as $j) {
            Jurusan::firstOrCreate(['kode' => $j['kode']], $j);
        }
        $rpl = Jurusan::where('kode', 'RPL')->first();
        $tkj = Jurusan::where('kode', 'TKJ')->first();
        $mm  = Jurusan::where('kode', 'MM')->first();

        // ── 3. Kelas (Tahun Ajaran 2024/2025 & 2025/2026) ──
        $kelasesData = [
            // Tahun Ajaran 2024/2025 (Saat Ini)
            ['jurusan_id' => $rpl->id, 'nama' => 'RPL 1',  'tingkat' => 10, 'tahun_ajaran' => '2024/2025'],
            ['jurusan_id' => $rpl->id, 'nama' => 'RPL 2',  'tingkat' => 10, 'tahun_ajaran' => '2024/2025'],
            ['jurusan_id' => $rpl->id, 'nama' => 'RPL 1',  'tingkat' => 11, 'tahun_ajaran' => '2024/2025'],
            ['jurusan_id' => $rpl->id, 'nama' => 'RPL 1',  'tingkat' => 12, 'tahun_ajaran' => '2024/2025'],
            ['jurusan_id' => $tkj->id, 'nama' => 'TKJ 1',  'tingkat' => 10, 'tahun_ajaran' => '2024/2025'],
            ['jurusan_id' => $tkj->id, 'nama' => 'TKJ 1',  'tingkat' => 11, 'tahun_ajaran' => '2024/2025'],
            ['jurusan_id' => $mm->id,  'nama' => 'MM 1',   'tingkat' => 10, 'tahun_ajaran' => '2024/2025'],

            // Tahun Ajaran 2025/2026 (Tujuan Naik Kelas)
            ['jurusan_id' => $rpl->id, 'nama' => 'RPL 1',  'tingkat' => 11, 'tahun_ajaran' => '2025/2026'],
            ['jurusan_id' => $rpl->id, 'nama' => 'RPL 2',  'tingkat' => 11, 'tahun_ajaran' => '2025/2026'],
            ['jurusan_id' => $rpl->id, 'nama' => 'RPL 1',  'tingkat' => 12, 'tahun_ajaran' => '2025/2026'],
            ['jurusan_id' => $tkj->id, 'nama' => 'TKJ 1',  'tingkat' => 11, 'tahun_ajaran' => '2025/2026'],
            ['jurusan_id' => $mm->id,  'nama' => 'MM 1',   'tingkat' => 11, 'tahun_ajaran' => '2025/2026'],
        ];

        foreach ($kelasesData as $k) {
            Kelas::create($k); // Langsung create karena sudah di-truncate di awal
        }

        // Mapping kelas untuk memudahkan inject ke siswa
        $kelasMap = [
            'x_rpl_1'  => Kelas::where('nama', 'RPL 1')->where('tahun_ajaran', '2024/2025')->where('tingkat', 10)->first(),
            'x_rpl_2'  => Kelas::where('nama', 'RPL 2')->where('tahun_ajaran', '2024/2025')->where('tingkat', 10)->first(),
            'xi_rpl_1' => Kelas::where('nama', 'RPL 1')->where('tahun_ajaran', '2024/2025')->where('tingkat', 11)->first(),
            'xii_rpl_1' => Kelas::where('nama', 'RPL 1')->where('tahun_ajaran', '2024/2025')->where('tingkat', 12)->first(),
            'x_tkj_1'  => Kelas::where('nama', 'TKJ 1')->where('tahun_ajaran', '2024/2025')->where('tingkat', 10)->first(),
            'xi_tkj_1' => Kelas::where('nama', 'TKJ 1')->where('tahun_ajaran', '2024/2025')->where('tingkat', 11)->first(),
            'x_mm_1'   => Kelas::where('nama', 'MM 1')->where('tahun_ajaran', '2024/2025')->where('tingkat', 10)->first(),
        ];

        // ── 4. Siswa ──
        // Data nama depan & belakang realistis Indonesia
        $namaL = ['Ahmad', 'Budi', 'Rizky', 'Fajar', 'Dimas', 'Rizal', 'Arif', 'Hendra', 'Irfan', 'Joko', 'Naufal', 'Bayu', 'Surya', 'Andi', 'Galih', 'Firman', 'Teguh', 'Yoga', 'Ilham', 'Kemal'];
        $namaP = ['Siti', 'Dewi', 'Ayu', 'Rina', 'Putri', 'Sari', 'Nadia', 'Lestari', 'Permata', 'Aulia', 'Fitri', 'Dinda', 'Cantika', 'Rahma', 'Zahra', 'Nisa', 'Amelia', 'Shinta', 'Maya', 'Kartika'];
        $belakang = ['Pratama', 'Saputra', 'Firmansyah', 'Hidayat', 'Kusuma', 'Wijaya', 'Santoso', 'Ramadhan', 'Nugraha', 'Setiawan'];

        $nisCounter = 2024001;
        $nipdCounter = 242510001;

        $siswaPayload = [];

        // Fungsi untuk generate data siswa per kelas
        $generateSiswa = function ($kelasId, $jumlahL, $jumlahP) use (&$nisCounter, &$nipdCounter, $namaL, $namaP, $belakang, &$siswaPayload) {
            for ($i = 0; $i < $jumlahL; $i++) {
                $nipd = (string) $nipdCounter++;
                $siswaPayload[] = [
                    'kelas_id'      => $kelasId,
                    'nis'           => (string) $nisCounter++,
                    'nipd'          => $nipd,
                    'nama'          => $namaL[array_rand($namaL)] . ' ' . $belakang[array_rand($belakang)],
                    'jenis_kelamin' => 'L',
                    'tempat_lahir'  => 'Bandung',
                    'tanggal_lahir' => fake()->date('Y-m-d', '-15 years'),
                    'no_barcode'    => Siswa::generateBarcode($nipd),
                    'status'        => 'aktif',
                    'make_account'  => $i === 0, // Hanya siswa pertama yang dibuatkan akun
                ];
            }
            for ($i = 0; $i < $jumlahP; $i++) {
                $nipd = (string) $nipdCounter++;
                $siswaPayload[] = [
                    'kelas_id'      => $kelasId,
                    'nis'           => (string) $nisCounter++,
                    'nipd'          => $nipd,
                    'nama'          => $namaP[array_rand($namaP)] . ' ' . $belakang[array_rand($belakang)],
                    'jenis_kelamin' => 'P',
                    'tempat_lahir'  => 'Bandung',
                    'tanggal_lahir' => fake()->date('Y-m-d', '-15 years'),
                    'no_barcode'    => Siswa::generateBarcode($nipd),
                    'status'        => 'aktif',
                    'make_account'  => false,
                ];
            }
        };

        // Generate per kelas
        $generateSiswa($kelasMap['x_rpl_1']->id,  12, 10); // 22 siswa
        $generateSiswa($kelasMap['x_rpl_2']->id,  10, 12); // 22 siswa
        $generateSiswa($kelasMap['xi_rpl_1']->id, 11, 11); // 22 siswa
        $generateSiswa($kelasMap['xii_rpl_1']->id, 10, 10); // 20 siswa (Bisa buat status lulus nanti)
        $generateSiswa($kelasMap['x_tkj_1']->id,  13,  9); // 22 siswa
        $generateSiswa($kelasMap['xi_tkj_1']->id, 10, 10); // 20 siswa
        $generateSiswa($kelasMap['x_mm_1']->id,    8, 12); // 20 siswa

        // Simpan siswa & buat akun
        foreach ($siswaPayload as $payload) {
            $siswa = Siswa::create([
                'kelas_id'      => $payload['kelas_id'],
                'nis'           => $payload['nis'],
                'nipd'          => $payload['nipd'],
                'nama'          => $payload['nama'],
                'jenis_kelamin' => $payload['jenis_kelamin'],
                'tempat_lahir'  => $payload['tempat_lahir'],
                'tanggal_lahir' => $payload['tanggal_lahir'],
                'alamat'        => fake()->address(),
                'no_telepon'    => '08' . fake()->numerify('##########'),
                'no_barcode'    => $payload['no_barcode'],
                'status'        => $payload['status'],
            ]);

            if ($payload['make_account']) {
                $userSiswa = User::firstOrCreate(
                    ['username' => $payload['nis']],
                    [
                        'name'     => $payload['nama'],
                        'email'    => strtolower($payload['nis']) . '@lentera.sch.id',
                        'password' => Hash::make('password'),
                        'role'     => 'siswa',
                    ]
                );
                $siswa->update(['user_id' => $userSiswa->id]);
            }
        }

        // ── Variasi Status Siswa (Untuk Testing Filter) ──
        $siswaXiiRpl = Siswa::where('kelas_id', $kelasMap['xii_rpl_1']->id)->limit(5)->get();
        Siswa::where('nis', $siswaXiiRpl[0]->nis)->update(['status' => 'lulus']);
        Siswa::where('nis', $siswaXiiRpl[1]->nis)->update(['status' => 'lulus']);
        Siswa::where('nis', $siswaXiiRpl[2]->nis)->update(['status' => 'pindah']);
        Siswa::where('nis', $siswaXiiRpl[3]->nis)->update(['status' => 'tidak_aktif']);

        // Siswa tanpa NIPD & tanpa Barcode (Tidak bisa di-scan)
        Siswa::create([
            'kelas_id'      => $kelasMap['x_rpl_1']->id,
            'nis'           => '2024999',
            'nipd'          => null,
            'nama'          => 'Lisa Permata',
            'jenis_kelamin' => 'P',
            'no_barcode'    => null,
            'status'        => 'aktif',
        ]);

        // ── 5. Data Absensi (Untuk Testing Rekap & Dashboard) ──
        $today = today();
        $jumlahHari = 5; // Buat data 5 hari ke belakang

        // Ambil beberapa siswa untuk dijadikan patokan
        $sampleSiswaAktif = Siswa::where('status', 'aktif')->take(80)->get();

        // Ambil user siswa pertama (untuk testing fitur izin/sakit)
        $firstUserSiswa = User::where('role', 'siswa')->first();
        $firstSiswa = $firstUserSiswa ? Siswa::where('user_id', $firstUserSiswa->id)->first() : null;

        for ($i = 0; $i < $jumlahHari; $i++) {
            $tanggal = $today->copy()->subDays($i);

            // Skip hari Sabtu/Minggu
            if ($tanggal->isWeekend()) {
                continue;
            }

            $jamMasuk = $tanggal->copy()->setHour(7)->setMinute(rand(0, 15));
            $jamPulang = $tanggal->copy()->setHour(15)->setMinute(rand(0, 45));

            foreach ($sampleSiswaAktif as $siswa) {
                // Logika acak: 70% Hadir, 10% Izin, 5% Sakit, 15% Tidak Diabsen (Nanti Jadi Alpa)
                $rand = rand(1, 100);

                if ($rand <= 70) {
                    // HADIR
                    Absensi::create([
                        'siswa_id'   => $siswa->id,
                        'scanned_by' => $scannerUser->id,
                        'tanggal'    => $tanggal,
                        'jam_masuk'  => $jamMasuk->format('H:i:s'),
                        'jam_pulang' => $jamPulang->format('H:i:s'),
                        'status'     => 'hadir',
                    ]);
                } elseif ($rand <= 80) {
                    // IZIN
                    Absensi::create([
                        'siswa_id'   => $siswa->id,
                        'scanned_by' => null,
                        'tanggal'    => $tanggal,
                        'jam_masuk'  => null,
                        'status'     => 'izin',
                        'keterangan' => 'Keperluan keluarga',
                    ]);
                } elseif ($rand <= 85) {
                    // SAKIT
                    Absensi::create([
                        'siswa_id'   => $siswa->id,
                        'scanned_by' => null,
                        'tanggal'    => $tanggal,
                        'jam_masuk'  => null,
                        'status'     => 'sakit',
                        'keterangan' => 'Demam tinggi, rawat jalan',
                        'foto_surat' => 'surat-sakit/dummy.jpg', // Dummy path
                    ]);
                }
                // Jika > 85, dibiarkan kosong (otomatis jadi alpa saat command dijalankan)
            }

            // Khusus siswa pertama (yang punya akun): Pastikan hari ini statusnya IZIN untuk testing
            if ($i === 0 && $firstSiswa) {
                Absensi::updateOrCreate(
                    ['siswa_id' => $firstSiswa->id, 'tanggal' => $tanggal],
                    [
                        'scanned_by' => null,
                        'jam_masuk'  => null,
                        'status'     => 'izin',
                        'keterangan' => 'Acara keluarga di luar kota',
                        'foto_surat' => null,
                    ]
                );
            }
        }

        // ── Print Summary ──
        $this->command->info('Seeder berhasil dijalankan!');
        $this->command->info('========================================');
        $this->command->info('Login Admin       : admin / password');
        $this->command->info('Login Scanner     : scanner1 / password');
        $this->command->info('Login Scanner 2   : scanner2 / password');
        $this->command->info('----------------------------------------');
        $this->command->info('Login Siswa (RPL) : 2024001 / password');
        $this->command->info('Login Siswa (RPL) : 2024023 / password');
        $this->command->info('Login Siswa (RPL) : 2024045 / password');
        $this->command->info('Login Siswa (RPL) : 2024067 / password');
        $this->command->info('Login Siswa (TKJ) : 2024089 / password');
        $this->command->info('Login Siswa (TKJ) : 2024111 / password');
        $this->command->info('Login Siswa (MM)  : 2024131 / password');
        $this->command->info('----------------------------------------');
        $this->command->info('Total Kelas       : ' . Kelas::count() . ' kelas');
        $this->command->info('Total Siswa       : ' . Siswa::count() . ' siswa');
        $this->command->info('Total Akun Siswa  : ' . User::where('role', 'siswa')->count() . ' akun');
        $this->command->info('Total Absensi     : ' . Absensi::count() . ' record');
        $this->command->info('========================================');
        $this->command->info('NIS Tanpa Barcode : 2024999 (Lisa Permata)');
        $this->command->info('Format Barcode    : A{NIPD}A (CODABAR)');
        $this->command->info('========================================');
    }
}
