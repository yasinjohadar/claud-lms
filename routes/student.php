<?php

use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\LessonProgressController;
use App\Http\Controllers\Student\StudentCourseController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'check.user.active', 'role:student', 'ensure.student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/courses', [StudentCourseController::class, 'index'])->name('courses.index');
        Route::post('/lessons/{lesson}/progress', [LessonProgressController::class, 'store'])
            ->middleware('ensure.enrolled')
            ->name('lessons.progress');
    });

Route::middleware(['auth', 'check.user.active', 'role:student', 'ensure.student'])
    ->prefix('student')
    ->group(function () {
        require base_path('routes/exam-student.php');
        require base_path('routes/gamification-student.php');
    });
