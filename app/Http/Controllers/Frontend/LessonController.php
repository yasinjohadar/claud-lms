<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Services\CourseAccessService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function __construct(
        protected CourseAccessService $accessService
    ) {}

    public function show(Request $request, int $id): View
    {
        $lesson = CourseLesson::query()
            ->with(['section.course.sections.lessons'])
            ->findOrFail($id);

        $course = $lesson->section?->course;
        abort_unless($course && Course::query()->whereKey($course->id)->published()->exists(), 404);

        if (! $this->accessService->canAccessLesson($request->user(), $lesson)) {
            abort(403, 'لا يمكنك مشاهدة هذا الدرس. يرجى التسجيل في الكورس أولاً.');
        }

        $student = $request->user()?->student;
        $completedLessonIds = collect();

        if ($student) {
            $enrollment = $student->enrollments()
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->first();

            if ($enrollment) {
                $completedLessonIds = $enrollment->lessonProgress()
                    ->where('status', 'completed')
                    ->pluck('course_lesson_id');
            }
        }

        $sections = $course->sections()->with(['lessons' => fn ($q) => $q->orderBy('sort_order')])->orderBy('sort_order')->get();

        $totalLessons = $sections->sum(fn ($s) => $s->lessons->count());
        $totalDuration = $sections->sum(fn ($s) => $s->lessons->sum('duration_seconds'));

        return view('frontend.pages.lesson-view', [
            'lesson' => $lesson,
            'course' => $course,
            'sections' => $sections,
            'completedLessonIds' => $completedLessonIds,
            'totalLessons' => $totalLessons,
            'totalDurationHours' => $totalDuration ? round($totalDuration / 3600, 1) : ($course->duration_hours ?? 0),
            'progressUrl' => auth()->check() && auth()->user()->hasRole('student')
                ? route('student.lessons.progress', $lesson)
                : null,
        ]);
    }
}
