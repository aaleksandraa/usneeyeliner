<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) config('admin.email');
        $password = (string) config('admin.password');

        if ($password === '') {
            throw new RuntimeException('Postavite ADMIN_PASSWORD u .env prije pokretanja AdminSeedera.');
        }

        $admin = User::query()->where('email', $email)->first()
            ?? User::query()->where('email', 'admin@example.com')->where('role', 'admin')->first()
            ?? new User;

        $admin->fill([
            'first_name' => 'Glavni',
            'last_name' => 'Administrator',
            'email' => $email,
            'password' => $password,
            'role' => 'admin',
            'status' => 'active',
        ])->save();
    }
}
