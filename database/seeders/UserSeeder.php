<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'nik' => '1234567890123456',
            'email_phone' => '08123456789',
            'name' => 'Admin Silapso',
            'email' => 'admin@silapso.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Regular users
        User::create([
            'nik' => '1234567890123457',
            'email_phone' => '08123456790',
            'name' => 'Citizen Silapso',
            'email' => 'citizen@silapso.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        User::create([
            'nik' => '1234567890123458',
            'email_phone' => '08123456791',
            'name' => 'Jane Smith',
            'email' => 'jane@email.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);
    }
}
