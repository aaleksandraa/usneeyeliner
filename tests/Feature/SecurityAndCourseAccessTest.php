<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SecurityAndCourseAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_student_course_and_lesson_securely(): void
    {
        Http::fake([
            'vimeo.com/api/oembed.json*' => Http::response([
                'thumbnail_url' => 'https://i.vimeocdn.com/video/course-cover_1280x720.jpg',
            ]),
        ]);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.students.store'), [
            'first_name' => 'Amina',
            'last_name' => 'Kovač',
            'email' => 'amina@example.com',
            'password' => 'Sigurna123',
            'password_confirmation' => 'Sigurna123',
            'role' => 'admin',
            'status' => 'inactive',
        ])->assertRedirect();

        $student = User::where('email', 'amina@example.com')->sole();
        $this->assertSame('student', $student->role);
        $this->assertSame('active', $student->status);
        $this->assertTrue(Hash::check('Sigurna123', $student->password));

        $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Digitalni marketing',
            'description' => 'Kompletan praktični kurs.',
            'vimeo_url' => 'https://vimeo.com/123456789',
            'status' => 'active',
        ])->assertRedirect();

        $course = Course::where('title', 'Digitalni marketing')->sole();
        $this->assertSame('https://i.vimeocdn.com/video/course-cover_1280x720.jpg', $course->vimeo_thumbnail_url);

        $this->actingAs($admin)->post(route('admin.courses.lessons.store', $course), [
            'title' => 'Uvod u kurs',
            'description' => 'Prva lekcija.',
            'vimeo_url' => 'https://vimeo.com/987654321',
            'sort_order' => 1,
        ])->assertRedirect(route('admin.courses.show', $course));

        $this->assertDatabaseHas('course_lessons', ['course_id' => $course->id, 'title' => 'Uvod u kurs']);
    }

    public function test_student_dashboard_lists_all_and_only_assigned_active_courses(): void
    {
        $student = User::factory()->create();
        $first = Course::factory()->create(['title' => 'A Kurs', 'status' => 'active']);
        $second = Course::factory()->create(['title' => 'B Kurs', 'status' => 'active']);
        $inactive = Course::factory()->create(['title' => 'C Neaktivan', 'status' => 'inactive']);
        $unassigned = Course::factory()->create(['title' => 'D Nedodijeljen', 'status' => 'active']);
        $student->courses()->attach([$second->id, $inactive->id, $first->id]);

        $this->actingAs($student)->get(route('dashboard'))
            ->assertOk()
            ->assertSeeInOrder(['A Kurs', 'B Kurs'])
            ->assertDontSee('C Neaktivan')
            ->assertDontSee('D Nedodijeljen');

        $this->actingAs($student)->get(route('courses.show', $first))->assertOk();
        $this->actingAs($student)->get(route('courses.show', $inactive))->assertNotFound();
        $this->actingAs($student)->get(route('courses.show', $unassigned))->assertForbidden();
    }

    public function test_admin_can_assign_without_duplicates_and_remove_course_access(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->create();
        $course = Course::factory()->create();

        $this->actingAs($admin)->post(route('admin.students.courses.store', $student), ['course_id' => $course->id])->assertRedirect();
        $this->actingAs($admin)->post(route('admin.students.courses.store', $student), ['course_id' => $course->id])->assertRedirect();
        $this->assertDatabaseCount('course_user', 1);
        $this->actingAs($student)->get(route('courses.show', $course))->assertOk();

        $this->actingAs($admin)->delete(route('admin.students.courses.destroy', [$student, $course]))->assertRedirect();
        $this->assertDatabaseCount('course_user', 0);
        $this->actingAs($student)->get(route('courses.show', $course))->assertForbidden();
    }

    public function test_student_cannot_execute_admin_mutations(): void
    {
        $student = User::factory()->create();
        $otherStudent = User::factory()->create();
        $course = Course::factory()->create();

        $this->actingAs($student)->post(route('admin.courses.store'), [
            'title' => 'Nedozvoljen kurs',
            'description' => 'Ne smije biti kreiran.',
        ])->assertForbidden();
        $this->actingAs($student)->post(route('admin.students.courses.store', $otherStudent), [
            'course_id' => $course->id,
        ])->assertForbidden();

        $this->assertDatabaseMissing('courses', ['title' => 'Nedozvoljen kurs']);
        $this->assertDatabaseCount('course_user', 0);
    }

    public function test_lesson_must_belong_to_the_course_for_admin_and_student(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->create();
        $course = Course::factory()->create();
        $otherCourse = Course::factory()->create();
        $foreignLesson = CourseLesson::factory()->create(['course_id' => $otherCourse->id]);
        $student->courses()->attach($course);

        $this->actingAs($admin)->get(route('admin.courses.lessons.edit', [$course, $foreignLesson]))->assertNotFound();
        $this->actingAs($student)->get(route('courses.lessons.show', [$course, $foreignLesson]))->assertNotFound();
    }

    public function test_inactive_authenticated_users_are_blocked(): void
    {
        $inactiveStudent = User::factory()->inactive()->create();
        $inactiveAdmin = User::factory()->admin()->inactive()->create();

        $this->actingAs($inactiveStudent)->get(route('dashboard'))->assertForbidden();
        $this->actingAs($inactiveAdmin)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_security_headers_are_present(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()')
            ->assertHeader('Content-Security-Policy');
    }

    public function test_untrusted_vimeo_thumbnail_host_is_not_saved(): void
    {
        Http::fake([
            'vimeo.com/api/oembed.json*' => Http::response([
                'thumbnail_url' => 'https://attacker.example/cover.jpg',
            ]),
        ]);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Siguran thumbnail',
            'description' => 'Kurs bez nepouzdane slike.',
            'vimeo_url' => 'https://vimeo.com/123456789',
            'status' => 'active',
        ])->assertRedirect();

        $this->assertNull(Course::where('title', 'Siguran thumbnail')->sole()->vimeo_thumbnail_url);
    }
}
