<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Create the default admin user.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@example.com',
            ],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin12345'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}