<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Services\CourseAccessService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentLessonController extends Controller
{
    public function __construct(
        protected CourseAccessService $accessService
    ) {}

    public function show(Request $request, CourseLesson $lesson): View
    {
        $lesson->load(['section.course']);

        $course = $lesson->section?->course;
        abort_unless($course && Course::query()->whereKey($course->id)->published()->exists(), 404);

        if (! $this->accessService->canAccessLesson($request->user(), $lesson)) {
            abort(403, 'لا يمكنك مشاهدة هذا الدرس. يرجى التسجيل في الكورس أولاً.');
        }

        $student = $request->user()->student;
        $enrollment = $student->enrollments()
            ->where('course_id', $course->id)
            ->whereIn('status', ['active', 'completed', 'pending'])
            ->firstOrFail();

        $sections = $course->sections()
            ->with([
                'lessons' => fn ($q) => $q->orderBy('sort_order'),
                'modules' => fn ($q) => $q->where('is_visible', true)->orderBy('sort_order'),
            ])
            ->orderBy('sort_order')
            ->get();

        $lessonProgress = $enrollment->lessonProgress()->get()->keyBy('course_lesson_id');
        $currentProgress = $lessonProgress->get($lesson->id);

        $allLessons = $sections->flatMap->lessons->values();
        $currentIndex = $allLessons->search(fn ($item) => $item->id === $lesson->id);

        $prevLesson = $currentIndex !== false && $currentIndex > 0
            ? $allLessons[$currentIndex - 1]
            : null;
        $nextLesson = $currentIndex !== false && $currentIndex < $allLessons->count() - 1
            ? $allLessons[$currentIndex + 1]
            : null;

        $totalLessons = $allLessons->count();
        $completedLessons = $lessonProgress->where('status', 'completed')->count();

        return view('student.pages.lessons.show', [
            'lesson' => $lesson,
            'course' => $course,
            'enrollment' => $enrollment,
            'sections' => $sections,
            'lessonProgress' => $lessonProgress,
            'currentProgress' => $currentProgress,
            'prevLesson' => $prevLesson,
            'nextLesson' => $nextLesson,
            'totalLessons' => $totalLessons,
            'completedLessons' => $completedLessons,
            'progressUrl' => route('student.lessons.progress', $lesson),
        ]);
    }
}
