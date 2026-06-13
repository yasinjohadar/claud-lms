<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\Student;
use App\Services\EnrollmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {
        $this->middleware('auth');
        $this->middleware('permission:enrollment-manage');
    }

    public function store(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'source' => 'nullable|in:admin_grant,free,promo',
        ]);

        $course = Course::findOrFail($validated['course_id']);

        $this->enrollmentService->grant(
            $student,
            $course,
            $validated['source'] ?? 'admin_grant',
            $request->user(),
            null,
            'active'
        );

        return back()->with('success', 'تم تسجيل الطالب في الكورس بنجاح');
    }

    public function destroy(CourseEnrollment $enrollment): RedirectResponse
    {
        $studentId = $enrollment->student_id;
        $this->enrollmentService->cancel($enrollment);

        return redirect()
            ->route('admin.students.show', $studentId)
            ->with('success', 'تم إلغاء تسجيل الكورس');
    }
}
