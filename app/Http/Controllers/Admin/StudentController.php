<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResetStudentPasswordRequest;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $students = User::query()->where('role', 'student')->withCount('courses')
            ->when($search, fn ($q) => $q->where(fn ($q) => $q->where('first_name', 'like', "%{$search}%")->orWhere('last_name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.students.index', compact('students', 'search'));
    }

    public function create(): View
    {
        return view('admin.students.create');
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $student = User::create($request->safe()->only(['first_name', 'last_name', 'email', 'password']) + ['role' => 'student', 'status' => 'active']);

        return redirect()->route('admin.students.show', $student)->with('success', 'Učenik je kreiran.');
    }

    public function show(User $student): View
    {
        $this->assertStudent($student);
        $student->load(['courses', 'loginLogs' => fn ($q) => $q->latest()->limit(50)]);
        $firstIpIds = $student->loginLogs()->selectRaw('MIN(id) as first_id')->groupBy('ip_address')->pluck('first_id')->map(fn ($id) => (int) $id)->all();
        $firstDeviceIds = $student->loginLogs()->whereNotNull('device_hash')->selectRaw('MIN(id) as first_id')->groupBy('device_hash')->pluck('first_id')->map(fn ($id) => (int) $id)->all();
        $activity = $student->loginLogs->map(function ($log) use ($firstIpIds, $firstDeviceIds) {
            $log->is_new_ip = in_array($log->id, $firstIpIds, true);
            $log->is_new_device = in_array($log->id, $firstDeviceIds, true);

            return $log;
        });
        $availableCourses = Course::whereDoesntHave('students', fn ($q) => $q->whereKey($student->id))->orderBy('title')->get();

        return view('admin.students.show', compact('student', 'activity', 'availableCourses'));
    }

    public function edit(User $student): View
    {
        $this->assertStudent($student);

        return view('admin.students.edit', compact('student'));
    }

    public function update(UpdateStudentRequest $request, User $student): RedirectResponse
    {
        $this->assertStudent($student);
        $student->update($request->validated());

        return redirect()->route('admin.students.show', $student)->with('success', 'Podaci su sačuvani.');
    }

    public function toggleStatus(User $student): RedirectResponse
    {
        $this->assertStudent($student);
        $student->update(['status' => $student->status === 'active' ? 'inactive' : 'active']);

        return back()->with('success', 'Status učenika je promijenjen.');
    }

    public function resetPassword(ResetStudentPasswordRequest $request, User $student): RedirectResponse
    {
        $this->assertStudent($student);
        $student->update(['password' => $request->validated('password')]);

        return back()->with('success', 'Lozinka je promijenjena.');
    }

    public function destroy(User $student): RedirectResponse
    {
        $this->assertStudent($student);
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Učenik je obrisan.');
    }

    private function assertStudent(User $student): void
    {
        abort_unless($student->isStudent(), 404);
    }
}
