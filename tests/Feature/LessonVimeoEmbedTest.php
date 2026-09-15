<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LessonVimeoEmbedTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_is_created_with_only_a_title(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('admin.courses.create'))
            ->assertOk()
            ->assertSee('name="title"', false)
            ->assertDontSee('name="vimeo_url"', false)
            ->assertDontSee('name="description"', false);

        $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Jednostavan kurs',
        ])->assertRedirect();

        $this->assertDatabaseHas('courses', [
            'title' => 'Jednostavan kurs',
            'description' => null,
            'status' => 'active',
        ]);
    }

    public function test_admin_can_add_a_vimeo_iframe_as_a_lesson_with_automatic_thumbnail(): void
    {
        Http::fake([
            'vimeo.com/api/oembed.json*' => Http::response([
                'thumbnail_url' => 'https://i.vimeocdn.com/video/lesson-cover_1280x720.jpg',
            ]),
        ]);
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create();
        $iframe = '<iframe src="https://player.vimeo.com/video/123456789?h=a1b2c3d4&amp;badge=0"></iframe>';

        $this->actingAs($admin)->post(route('admin.courses.lessons.store', $course), [
            'title' => 'Prva video lekcija',
            'vimeo_url' => $iframe,
            'sort_order' => 1,
        ])->assertRedirect(route('admin.courses.show', $course));

        $lesson = CourseLesson::sole();
        $this->assertSame('https://player.vimeo.com/video/123456789?h=a1b2c3d4&badge=0', $lesson->vimeo_url);
        $this->assertSame('https://player.vimeo.com/video/123456789?h=a1b2c3d4', $lesson->vimeo_embed_url);
        $this->assertSame('https://i.vimeocdn.com/video/lesson-cover_1280x720.jpg', $lesson->vimeo_thumbnail_url);
    }

    public function test_non_vimeo_lesson_embed_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        $course = Course::factory()->create();

        $this->actingAs($admin)->post(route('admin.courses.lessons.store', $course), [
            'title' => 'Neispravan video',
            'vimeo_url' => '<iframe src="https://example.com/video/123"></iframe>',
            'sort_order' => 1,
        ])->assertSessionHasErrors('vimeo_url');

        $this->assertDatabaseCount('course_lessons', 0);
    }

    public function test_opening_course_immediately_shows_first_video_and_ordered_lesson_thumbnails(): void
    {
        $student = User::factory()->create();
        $course = Course::factory()->create();
        $student->courses()->attach($course);
        CourseLesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Prvi video',
            'vimeo_url' => 'https://vimeo.com/111111111',
            'vimeo_thumbnail_url' => 'https://i.vimeocdn.com/video/first.jpg',
            'sort_order' => 1,
        ]);
        CourseLesson::factory()->create([
            'course_id' => $course->id,
            'title' => 'Drugi video',
            'vimeo_url' => 'https://vimeo.com/222222222',
            'vimeo_thumbnail_url' => 'https://i.vimeocdn.com/video/second.jpg',
            'sort_order' => 2,
        ]);

        $this->actingAs($student)->get(route('courses.show', $course))
            ->assertOk()
            ->assertSee('https://player.vimeo.com/video/111111111', false)
            ->assertSee('https://i.vimeocdn.com/video/first.jpg', false)
            ->assertSee('https://i.vimeocdn.com/video/second.jpg', false)
            ->assertSeeInOrder(['Prvi video', 'Drugi video']);

        $secondLesson = CourseLesson::where('title', 'Drugi video')->sole();
        $this->actingAs($student)->get(route('courses.lessons.show', [$course, $secondLesson]))
            ->assertOk()
            ->assertSee('https://player.vimeo.com/video/222222222', false)
            ->assertSee('aria-current="true"', false);
    }

    public function test_course_card_uses_first_lesson_thumbnail(): void
    {
        $student = User::factory()->create();
        $course = Course::factory()->create();
        $student->courses()->attach($course);
        CourseLesson::factory()->create([
            'course_id' => $course->id,
            'vimeo_thumbnail_url' => 'https://i.vimeocdn.com/video/course-card.jpg',
            'sort_order' => 1,
        ]);

        $this->actingAs($student)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('https://i.vimeocdn.com/video/course-card.jpg', false);
    }
}
