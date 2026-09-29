<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Pemilik Warung (Admin Penuh)
        User::updateOrCreate(
            ['email' => 'admin@warung.com'],
            [
                'name'     => 'Pemilik Warung',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
            ]
        );

        // Akun Kasir / Staf (Akses Terbatas)
        User::updateOrCreate(
            ['email' => 'kasir@warung.com'],
            [
                'name'     => 'Staf Kasir',
                'password' => Hash::make('kasir123'),
                'role'     => 'karyawan',
            ]
        );
    }
}
