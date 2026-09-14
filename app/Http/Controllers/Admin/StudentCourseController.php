<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentCourseController extends Controller
{
    public function store(Request $request, User $student): RedirectResponse
    {
        abort_unless($student->isStudent(), 404);
        $data = $request->validate(['course_id' => ['required', 'integer', 'exists:courses,id']]);
        $student->courses()->syncWithoutDetaching([$data['course_id']]);

        return back()->with('success', 'Kurs je dodijeljen.');
    }

    public function destroy(User $student, Course $course): RedirectResponse
    {
        abort_unless($student->isStudent(), 404);
        $student->courses()->detach($course->id);

        return back()->with('success', 'Kurs je uklonjen.');
    }
}
