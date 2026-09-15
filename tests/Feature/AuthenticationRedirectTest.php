<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_opening_the_domain_is_sent_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_expired_session_on_a_protected_page_is_sent_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_logged_in_student_opening_the_domain_is_sent_to_student_dashboard(): void
    {
        $student = User::factory()->create();

        $this->actingAs($student)->get('/')->assertRedirect(route('dashboard'));
        $this->actingAs($student)->get(route('login'))->assertRedirect(route('dashboard'));
    }

    public function test_logged_in_admin_opening_the_domain_is_sent_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/')->assertRedirect(route('admin.dashboard'));
        $this->actingAs($admin)->get(route('login'))->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_opening_a_student_page_is_sent_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertRedirect(route('admin.dashboard'));
    }
}
