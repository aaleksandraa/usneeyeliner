<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\User;
use App\Models\UserLoginLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataIntegrityTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_course_removes_lessons_and_assignments(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = CourseLesson::factory()->for($course)->create();
        $student->courses()->attach($course);

        $this->actingAs($admin)->delete(route('admin.courses.destroy', $course))->assertRedirect(route('admin.courses.index'));

        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
        $this->assertDatabaseMissing('course_lessons', ['id' => $lesson->id]);
        $this->assertDatabaseMissing('course_user', ['course_id' => $course->id, 'user_id' => $student->id]);
    }

    public function test_deleting_student_removes_assignments_and_login_logs(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->create();
        $course = Course::factory()->create();
        $student->courses()->attach($course);
        UserLoginLog::create(['user_id' => $student->id, 'ip_address' => '203.0.113.10', 'user_agent' => 'Test Browser', 'device_hash' => hash('sha256', 'test')]);

        $this->actingAs($admin)->delete(route('admin.students.destroy', $student))->assertRedirect(route('admin.students.index'));

        $this->assertDatabaseMissing('users', ['id' => $student->id]);
        $this->assertDatabaseMissing('course_user', ['course_id' => $course->id, 'user_id' => $student->id]);
        $this->assertDatabaseMissing('user_login_logs', ['user_id' => $student->id]);
    }
}
