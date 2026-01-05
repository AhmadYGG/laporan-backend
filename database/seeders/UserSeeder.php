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
            'name' => 'ahmeed',
            'email' => 'ahmeed@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Regular users
        User::create([
            'nik' => '1234567890123457',
            'email_phone' => '08123456790',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        User::create([
            'nik' => '1234567890123458',
            'email_phone' => '08123456791',
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);
    }
}
