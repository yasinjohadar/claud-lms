<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentCourseController extends Controller
{
    public function index(Request $request): View
    {
        $student = auth()->user()->student;

        $baseQuery = $student->enrollments()->with(['course.category', 'course.instructor']);

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'active' => (clone $baseQuery)->where('status', 'active')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'in_progress' => (clone $baseQuery)
                ->where('status', 'active')
                ->where('progress_percent', '>', 0)
                ->where('progress_percent', '<', 100)
                ->count(),
        ];

        $query = clone $baseQuery;

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('course', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $enrollments = $query
            ->orderByDesc('enrolled_at')
            ->paginate(12)
            ->withQueryString();

        return view('student.pages.courses.index', compact('enrollments', 'stats'));
    }

    public function show(Course $course): View
    {
        $student = auth()->user()->student;

        $enrollment = $student->enrollments()
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed', 'pending'])
            ->firstOrFail();

        $course->load([
            'category',
            'instructor',
            'tags',
            'sections' => fn ($q) => $q->orderBy('sort_order'),
            'sections.lessons' => fn ($q) => $q->orderBy('sort_order'),
            'sections.resources' => fn ($q) => $q->where('is_published', true)->orderBy('sort_order'),
            'sections.modules' => fn ($q) => $q->where('is_visible', true)->orderBy('sort_order'),
            'sections.modules.modulable',
            'globalResources',
        ]);

        $lessonProgress = $enrollment->lessonProgress()->get()->keyBy('course_lesson_id');

        $allLessons = $course->sections->flatMap->lessons;
        $completedCount = $lessonProgress->where('status', 'completed')->count();

        $nextLesson = $allLessons->first(function ($lesson) use ($lessonProgress) {
            $progress = $lessonProgress->get($lesson->id);

            return ! $progress || $progress->status !== 'completed';
        }) ?? $allLessons->first();

        $stats = [
            'progress' => (int) ($enrollment->progress_percent ?? 0),
            'completed_lessons' => $completedCount,
            'total_lessons' => $allLessons->count(),
            'total_sections' => $course->sections->count(),
            'duration_hours' => $course->duration_hours ?? 0,
        ];

        return view('student.pages.courses.show', compact(
            'course',
            'enrollment',
            'lessonProgress',
            'nextLesson',
            'stats'
        ));
    }
}
