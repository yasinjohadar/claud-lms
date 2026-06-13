<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseSection;
use App\Services\CourseCurriculumService;
use App\Services\VideoReferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseCurriculumController extends Controller
{
    public function __construct(
        protected CourseCurriculumService $curriculumService,
        protected VideoReferenceService $videoReferenceService
    ) {}

    public function index(Course $course): View
    {
        $course->load(['sections.lessons']);

        return view('admin.courses.curriculum.index', compact('course'));
    }

    public function storeSection(Request $request, Course $course): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $sortOrder = (int) $course->sections()->max('sort_order') + 1;

        $section = $course->sections()->create([
            'title' => $validated['title'],
            'sort_order' => $sortOrder,
        ]);

        return $this->jsonSuccess('تم إضافة القسم بنجاح', [
            'html' => $this->renderSections($course),
            'section_id' => $section->id,
            'stats' => $this->courseStats($course),
        ]);
    }

    public function updateSection(Request $request, Course $course, CourseSection $section): JsonResponse
    {
        $this->ensureSectionBelongsToCourse($course, $section);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $section->update($validated);

        return $this->jsonSuccess('تم تحديث القسم بنجاح', [
            'html' => $this->renderSections($course),
            'stats' => $this->courseStats($course),
        ]);
    }

    public function destroySection(Course $course, CourseSection $section): JsonResponse
    {
        $this->ensureSectionBelongsToCourse($course, $section);

        $section->delete();
        $this->curriculumService->syncCourseStats($course);

        return $this->jsonSuccess('تم حذف القسم بنجاح', [
            'html' => $this->renderSections($course),
            'stats' => $this->courseStats($course),
        ]);
    }

    public function storeLesson(Request $request, Course $course, CourseSection $section): JsonResponse
    {
        $this->ensureSectionBelongsToCourse($course, $section);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_provider' => 'required|in:youtube,vimeo,bunny_stream,bunny_cdn',
            'video_reference' => 'required|string|max:500',
            'duration' => 'nullable|string|max:20',
        ]);

        $video = $this->videoReferenceService->normalize(
            $validated['video_provider'],
            $validated['video_reference']
        );

        $sortOrder = (int) $section->lessons()->max('sort_order') + 1;

        $section->lessons()->create([
            'title' => $validated['title'],
            'video_provider' => $video['provider'],
            'video_reference' => $video['reference'],
            'duration_seconds' => $this->curriculumService->parseDurationToSeconds($validated['duration'] ?? null),
            'sort_order' => $sortOrder,
        ]);

        $this->curriculumService->syncCourseStats($course);

        return $this->jsonSuccess('تم إضافة الدرس بنجاح', [
            'html' => $this->renderSections($course),
            'stats' => $this->courseStats($course),
        ]);
    }

    public function updateLesson(Request $request, Course $course, CourseLesson $lesson): JsonResponse
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'video_provider' => 'required|in:youtube,vimeo,bunny_stream,bunny_cdn',
            'video_reference' => 'required|string|max:500',
            'duration' => 'nullable|string|max:20',
        ]);

        $video = $this->videoReferenceService->normalize(
            $validated['video_provider'],
            $validated['video_reference']
        );

        $lesson->update([
            'title' => $validated['title'],
            'video_provider' => $video['provider'],
            'video_reference' => $video['reference'],
            'duration_seconds' => $this->curriculumService->parseDurationToSeconds($validated['duration'] ?? null),
        ]);

        $this->curriculumService->syncCourseStats($course);

        return $this->jsonSuccess('تم تحديث الدرس بنجاح', [
            'html' => $this->renderSections($course),
            'stats' => $this->courseStats($course),
        ]);
    }

    public function destroyLesson(Course $course, CourseLesson $lesson): JsonResponse
    {
        $this->ensureLessonBelongsToCourse($course, $lesson);

        $lesson->delete();
        $this->curriculumService->syncCourseStats($course);

        return $this->jsonSuccess('تم حذف الدرس بنجاح', [
            'html' => $this->renderSections($course),
            'stats' => $this->courseStats($course),
        ]);
    }

    public function reorder(Request $request, Course $course): JsonResponse
    {
        $validated = $request->validate([
            'sections' => 'nullable|array',
            'sections.*' => 'integer|exists:course_sections,id',
            'lessons' => 'nullable|array',
            'lessons.*' => 'integer|exists:course_lessons,id',
            'section_id' => 'nullable|integer|exists:course_sections,id',
        ]);

        if (! empty($validated['sections'])) {
            $this->curriculumService->reorderSections($course, $validated['sections']);
        }

        if (! empty($validated['lessons']) && ! empty($validated['section_id'])) {
            $section = CourseSection::query()
                ->where('course_id', $course->id)
                ->where('id', $validated['section_id'])
                ->firstOrFail();

            $this->curriculumService->reorderLessons($section, $validated['lessons']);
        }

        return $this->jsonSuccess('تم تحديث الترتيب', [
            'html' => $this->renderSections($course),
            'stats' => $this->courseStats($course),
        ]);
    }

    protected function renderSections(Course $course): string
    {
        $course->load(['sections.lessons']);

        return view('admin.courses.curriculum.partials.sections', compact('course'))->render();
    }

    protected function courseStats(Course $course): array
    {
        $course->refresh();

        return [
            'sections_count' => $course->sections()->count(),
            'lessons_count' => $course->lessons_count,
            'duration_hours' => $course->duration_hours,
        ];
    }

    protected function jsonSuccess(string $message, array $extra = []): JsonResponse
    {
        return response()->json(array_merge(['success' => true, 'message' => $message], $extra));
    }

    protected function ensureSectionBelongsToCourse(Course $course, CourseSection $section): void
    {
        abort_unless($section->course_id === $course->id, 404);
    }

    protected function ensureLessonBelongsToCourse(Course $course, CourseLesson $lesson): void
    {
        abort_unless($lesson->section?->course_id === $course->id, 404);
    }
}
