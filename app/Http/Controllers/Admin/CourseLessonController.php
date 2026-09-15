<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseLessonRequest;
use App\Http\Requests\Admin\UpdateCourseLessonRequest;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Services\VimeoThumbnailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CourseLessonController extends Controller
{
    public function __construct(private readonly VimeoThumbnailService $thumbnailService) {}

    public function create(Course $course): View
    {
        return view('admin.courses.lessons-create', compact('course'));
    }

    public function store(StoreCourseLessonRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        $data['vimeo_thumbnail_url'] = $this->thumbnailService->fetch($data['vimeo_url']);
        $course->lessons()->create($data);

        return redirect()->route('admin.courses.show', $course)->with('success', 'Lekcija je dodana.');
    }

    public function edit(Course $course, CourseLesson $lesson): View
    {
        $this->assertBelongs($course, $lesson);

        return view('admin.courses.lessons-edit', compact('course', 'lesson'));
    }

    public function update(UpdateCourseLessonRequest $request, Course $course, CourseLesson $lesson): RedirectResponse
    {
        $this->assertBelongs($course, $lesson);
        $data = $request->validated();
        $thumbnailUrl = $this->thumbnailService->fetch($data['vimeo_url']);
        if ($thumbnailUrl || $data['vimeo_url'] !== $lesson->vimeo_url) {
            $data['vimeo_thumbnail_url'] = $thumbnailUrl;
        }
        $lesson->update($data);

        return redirect()->route('admin.courses.show', $course)->with('success', 'Lekcija je sačuvana.');
    }

    public function destroy(Course $course, CourseLesson $lesson): RedirectResponse
    {
        $this->assertBelongs($course, $lesson);
        $lesson->delete();

        return back()->with('success', 'Lekcija je obrisana.');
    }

    private function assertBelongs(Course $course, CourseLesson $lesson): void
    {
        abort_unless($lesson->course_id === $course->id, 404);
    }
}
