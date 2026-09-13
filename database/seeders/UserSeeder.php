<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Super Admin / Pemilik Butik
        User::updateOrCreate(
            ['username' => 'owner'],
            [
                'nama_user' => 'Pemilik Butik (Owner)',
                'password'  => Hash::make('123456'),
                'role'      => 'owner',
            ]
        );

        // 2. Penjahit 1 (Pak Budi)
        User::updateOrCreate(
            ['username' => 'budi'],
            [
                'nama_user' => 'Pak Budi',
                'password'  => Hash::make('123456'),
                'role'      => 'penjahit',
            ]
        );

        // 3. Penjahit 2 (Bu Siti)
        User::updateOrCreate(
            ['username' => 'siti'],
            [
                'nama_user' => 'Bu Siti',
                'password'  => Hash::make('123456'),
                'role'      => 'penjahit',
            ]
        );
    }
}
