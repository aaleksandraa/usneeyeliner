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
            ->with('lessons:id,course_id,vimeo_thumbnail_url,sort_order')
            ->withCount('lessons')
            ->orderBy('title')
            ->get();

        return view('student.dashboard', compact('courses'));
    }
}
