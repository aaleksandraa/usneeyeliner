<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLesson;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function show(Request $request, Course $course): View
    {
        $this->authorizeAccess($request, $course);
        abort_unless($course->status === 'active', 404);
        $course->load('lessons');

        return view('student.courses.show', compact('course'));
    }

    public function lesson(Request $request, Course $course, CourseLesson $lesson): View
    {
        $this->authorizeAccess($request, $course);
        abort_unless($course->status === 'active' && $lesson->course_id === $course->id, 404);
        $course->load('lessons');

        return view('student.courses.lesson', compact('course', 'lesson'));
    }

    private function authorizeAccess(Request $request, Course $course): void
    {
        abort_unless($request->user()->courses()->whereKey($course->id)->exists(), 403);
    }
}
