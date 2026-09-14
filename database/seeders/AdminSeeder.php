<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@example.com'], [
            'first_name' => 'Admin', 'last_name' => 'User', 'password' => 'ChangeMe123!', 'role' => 'admin', 'status' => 'active',
        ]);
    }
}
