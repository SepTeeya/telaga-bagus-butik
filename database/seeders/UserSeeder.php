<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Akun Super Admin / Pemilik Butik (Owner)
        User::updateOrCreate(
            ['username' => 'owner512'],
            [
                'nama_user' => 'Owner Telaga Bagus',
                'password'  => Hash::make('butik512437'),
                'role'      => 'owner',
            ]
        );

        // 2. Akun Administrator Sistem (Admin)
        User::updateOrCreate(
            ['username' => 'yudistira512'],
            [
                'nama_user' => 'Yudistira',
                'password'  => Hash::make('butik512437'),
                'role'      => 'admin',
            ]
        );

        // 3. Penjahit 1 (Mas Galang)
        User::updateOrCreate(
            ['username' => 'galang'],
            [
                'nama_user' => 'Mas Galang',
                'password'  => Hash::make('butik437'),
                'role'      => 'penjahit',
            ]
        );

        // 4. Penjahit 2 (Bu Nusa)
        User::updateOrCreate(
            ['username' => 'nusa'],
            [
                'nama_user' => 'Bu Nusa',
                'password'  => Hash::make('butik437'),
                'role'      => 'penjahit',
            ]
        );

        // 5. Penjahit 3 (Bu Ayu)
        User::updateOrCreate(
            ['username' => 'ayu'],
            [
                'nama_user' => 'Bu Ayu',
                'password'  => Hash::make('butik437'),
                'role'      => 'penjahit',
            ]
        );
    }
}
