<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_creates_the_configured_main_admin(): void
    {
        config()->set('admin.email', 'info@pius-academy.com');
        config()->set('admin.password', 'TestLozinka123!');

        $this->seed(AdminSeeder::class);

        $admin = User::where('email', 'info@pius-academy.com')->sole();
        $this->assertSame('admin', $admin->role);
        $this->assertSame('active', $admin->status);
        $this->assertTrue(Hash::check('TestLozinka123!', $admin->password));
    }

    public function test_seeder_migrates_the_previous_default_admin_without_duplication(): void
    {
        User::factory()->admin()->create(['email' => 'admin@example.com']);
        config()->set('admin.email', 'info@pius-academy.com');
        config()->set('admin.password', 'TestLozinka123!');

        $this->seed(AdminSeeder::class);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['email' => 'info@pius-academy.com', 'role' => 'admin']);
        $this->assertDatabaseMissing('users', ['email' => 'admin@example.com']);
    }
}
