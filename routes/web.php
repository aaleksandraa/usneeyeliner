<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CourseLessonController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentCourseController as AdminStudentCourseController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Student\CourseController as StudentCourseController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'store'])->name('password.update');
});
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::patch('/students/{student}/status', [StudentController::class, 'toggleStatus'])->name('students.status');
    Route::put('/students/{student}/password', [StudentController::class, 'resetPassword'])->name('students.password');
    Route::post('/students/{student}/courses', [AdminStudentCourseController::class, 'store'])->name('students.courses.store');
    Route::delete('/students/{student}/courses/{course}', [AdminStudentCourseController::class, 'destroy'])->name('students.courses.destroy');
    Route::resource('students', StudentController::class);
    Route::patch('/courses/{course}/status', [AdminCourseController::class, 'toggleStatus'])->name('courses.status');
    Route::resource('courses', AdminCourseController::class);
    Route::get('/courses/{course}/lessons/create', [CourseLessonController::class, 'create'])->name('courses.lessons.create');
    Route::post('/courses/{course}/lessons', [CourseLessonController::class, 'store'])->name('courses.lessons.store');
    Route::get('/courses/{course}/lessons/{lesson}/edit', [CourseLessonController::class, 'edit'])->name('courses.lessons.edit');
    Route::put('/courses/{course}/lessons/{lesson}', [CourseLessonController::class, 'update'])->name('courses.lessons.update');
    Route::delete('/courses/{course}/lessons/{lesson}', [CourseLessonController::class, 'destroy'])->name('courses.lessons.destroy');
});

Route::middleware(['auth', 'student'])->group(function () {
    Route::get('/dashboard', StudentDashboardController::class)->name('dashboard');
    Route::get('/courses/{course}', [StudentCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/lessons/{lesson}', [StudentCourseController::class, 'lesson'])->name('courses.lessons.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});
