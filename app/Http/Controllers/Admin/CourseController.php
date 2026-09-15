<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourseRequest;
use App\Http\Requests\Admin\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses = Course::withCount(['lessons', 'students'])->latest()->paginate(20);

        return view('admin.courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('admin.courses.create');
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['title']);
        $data['status'] = $data['status'] ?? 'active';
        $course = Course::create($data);

        return redirect()->route('admin.courses.show', $course)->with('success', 'Kurs je kreiran.');
    }

    public function show(Course $course): View
    {
        $course->load(['lessons', 'students']);

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();
        if ($data['title'] !== $course->title) {
            $data['slug'] = $this->uniqueSlug($data['title'], $course);
        }
        $course->update($data);

        return redirect()->route('admin.courses.show', $course)->with('success', 'Kurs je sačuvan.');
    }

    public function toggleStatus(Course $course): RedirectResponse
    {
        $course->update(['status' => $course->status === 'active' ? 'inactive' : 'active']);

        return back()->with('success', 'Status kursa je promijenjen.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Kurs je obrisan.');
    }

    private function uniqueSlug(string $title, ?Course $ignore = null): string
    {
        $base = Str::slug($title) ?: 'kurs';
        $slug = $base;
        $counter = 2;
        while (Course::where('slug', $slug)->when($ignore, fn ($q) => $q->whereKeyNot($ignore->id))->exists()) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}
