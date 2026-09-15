<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $courses = $request->user()->courses()
            ->where('status', 'active')
            ->with('lessons')
            ->withCount('lessons')
            ->orderBy('title')
            ->get();

        return view('student.dashboard', compact('courses'));
    }
}
