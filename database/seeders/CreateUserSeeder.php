<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name'     => 'Administrator',
                'username' => 'admin',
                'email'    => 'admin@lentera.sch.id',
                'password' => Hash::make('password'),
                'role'     => 'administrator',
            ],
            [
                'name'     => 'Petugas Scanner',
                'username' => 'scanner1',
                'email'    => 'scanner@lentera.sch.id',
                'password' => Hash::make('password'),
                'role'     => 'scanner',
            ],
            [
                'name'     => 'Ahmad Rizky',
                'username' => '2024001', // NIS
                'email'    => 'ahmad@lentera.sch.id',
                'password' => Hash::make('password'),
                'role'     => 'siswa',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['username' => $user['username']],
                $user
            );
        }

        $this->command->info('3 user berhasil dibuat. Password: password');
    }
}
