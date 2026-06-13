<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class StudentCourseController extends Controller
{
    public function index(): View
    {
        $student = auth()->user()->student;
        $enrollments = $student->enrollments()
            ->with('course.category')
            ->orderByDesc('enrolled_at')
            ->paginate(12);

        return view('student.courses.index', compact('enrollments'));
    }
}
