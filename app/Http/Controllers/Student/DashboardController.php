<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user()->student;
        $student->load(['activeEnrollments.course', 'orders']);

        $stats = [
            'active_courses' => $student->activeEnrollments()->count(),
            'avg_progress' => (int) round($student->activeEnrollments()->avg('progress_percent') ?? 0),
            'orders_count' => $student->orders()->count(),
            'completed_courses' => $student->enrollments()->where('status', 'completed')->count(),
        ];

        $recentEnrollments = $student->activeEnrollments()
            ->with('course')
            ->orderByDesc('enrolled_at')
            ->limit(5)
            ->get();

        return view('student.dashboard', compact('student', 'stats', 'recentEnrollments'));
    }
}
