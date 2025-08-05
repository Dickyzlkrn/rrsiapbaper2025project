<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // User dengan role 'user'
        User::create([
            'name' => 'User Biasa',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'status' => 'approved', // Ganti jika ingin lebih aman
            'role_id' => '2',
        ]);

        // User dengan role 'tata_usaha'
        User::create([
            'name' => 'Tata Usaha',
            'email' => 'tu@example.com',
            'password' => Hash::make('password'),
            'status' => 'approved',
            'role_id' => '1',
        ]);
    }
}
