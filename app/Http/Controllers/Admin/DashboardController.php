<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use App\Models\UserLoginLog;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'studentCount' => User::where('role', 'student')->count(),
            'activeStudentCount' => User::where('role', 'student')->where('status', 'active')->count(),
            'courseCount' => Course::count(),
            'todayLoginCount' => UserLoginLog::whereDate('created_at', today())->count(),
        ]);
    }
}
