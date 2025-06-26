<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (!User::where('email', 'admin@utcb.airways')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@utcb.airways',
                'password' => Hash::make('administrator'),
                'role' => 'admin',
            ]);
        }
    }
}