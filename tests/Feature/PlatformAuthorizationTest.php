<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlatformAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_is_redirected_from_admin_pages_to_own_dashboard(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertRedirect(route('dashboard'));
    }

    public function test_admin_can_open_admin_routes(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/admin')->assertOk();
    }

    public function test_course_list_contains_edit_and_confirmed_delete_actions(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create();

        $this->actingAs($admin)->get(route('admin.courses.index'))
            ->assertOk()
            ->assertSee(route('admin.courses.edit', $course), false)
            ->assertSee(route('admin.courses.destroy', $course), false)
            ->assertSee('Trajno obrisati kurs, lekcije i dodjele?');
    }

    public function test_student_can_open_assigned_course(): void
    {
        $student = User::factory()->create();
        $course = Course::factory()->create();
        $student->courses()->attach($course);
        $this->actingAs($student)->get(route('courses.show', $course))->assertOk();
    }

    public function test_student_cannot_open_unassigned_course(): void
    {
        $this->actingAs(User::factory()->create())->get(route('courses.show', Course::factory()->create()))->assertForbidden();
    }

    public function test_student_cannot_open_lesson_from_another_course(): void
    {
        $student = User::factory()->create();
        $assigned = Course::factory()->create();
        $student->courses()->attach($assigned);
        $foreignLesson = CourseLesson::factory()->create();
        $this->actingAs($student)->get(route('courses.lessons.show', [$assigned, $foreignLesson]))->assertNotFound();
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        $user = User::factory()->inactive()->create(['password' => 'password']);
        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_student_login_creates_audit_log(): void
    {
        $user = User::factory()->create(['password' => 'password']);
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.8', 'HTTP_USER_AGENT' => 'Test Browser'])->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('user_login_logs', ['user_id' => $user->id, 'ip_address' => '203.0.113.8', 'user_agent' => 'Test Browser']);
    }

    public function test_student_profile_update_cannot_change_another_user(): void
    {
        $student = User::factory()->create();
        $other = User::factory()->create(['first_name' => 'Original']);
        $this->actingAs($student)->put(route('profile.update'), ['user_id' => $other->id, 'first_name' => 'Changed', 'last_name' => $student->last_name, 'email' => $student->email])->assertRedirect();
        $this->assertSame('Original', $other->fresh()->first_name);
        $this->assertSame('Changed', $student->fresh()->first_name);
    }
}
