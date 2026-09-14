<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CourseVimeoEmbedTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_course_by_pasting_a_vimeo_iframe(): void
    {
        Http::fake([
            'vimeo.com/api/oembed.json*' => Http::response([
                'thumbnail_url' => 'https://i.vimeocdn.com/video/123456789_1280x720.jpg',
            ]),
        ]);
        $admin = User::factory()->create(['role' => 'admin']);
        $iframe = '<iframe src="https://player.vimeo.com/video/123456789?h=a1b2c3d4&amp;badge=0"></iframe>';

        $response = $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Profesionalni kurs',
            'description' => 'Opis kursa',
            'status' => 'active',
            'vimeo_url' => $iframe,
        ]);

        $course = Course::sole();
        $response->assertRedirect(route('admin.courses.show', $course));
        $this->assertSame('https://player.vimeo.com/video/123456789?h=a1b2c3d4&badge=0', $course->vimeo_url);
        $this->assertSame('https://player.vimeo.com/video/123456789?h=a1b2c3d4', $course->vimeo_embed_url);
        $this->assertSame('https://i.vimeocdn.com/video/123456789_1280x720.jpg', $course->thumbnail_url);
    }

    public function test_non_vimeo_embed_is_rejected(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->from(route('admin.courses.create'))->post(route('admin.courses.store'), [
            'title' => 'Neispravan kurs',
            'description' => 'Opis kursa',
            'status' => 'active',
            'vimeo_url' => '<iframe src="https://example.com/video/123"></iframe>',
        ])->assertRedirect(route('admin.courses.create'))->assertSessionHasErrors('vimeo_url');

        $this->assertDatabaseCount('courses', 0);
    }

    public function test_assigned_student_sees_responsive_course_vimeo_player(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->create(['vimeo_url' => 'https://vimeo.com/123456789']);
        $student->courses()->attach($course);

        $this->actingAs($student)->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('https://player.vimeo.com/video/123456789', false)
            ->assertSee('aspect-video', false);
    }
}
