<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseResource;
use App\Models\CourseSection;
use App\Services\CourseCurriculumService;
use App\Services\CourseResourceService;
use App\Services\VideoReferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseCurriculumController extends Controller
{
    public function __construct(
        protected CourseCurriculumService $curriculumService,
        protected CourseResourceService $resourceService,
        protected VideoReferenceService $videoReferenceService
    ) {}

    public function index(Course $course): View
    {
        $course->load(['sections.lessons', 'sections.resources', 'resources' => fn ($q) => $q->whereNull('course_section_id')]);

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
            'global_resources_html' => $this->renderGlobalResources($course),
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
            'global_resources_html' => $this->renderGlobalResources($course),
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
            'global_resources_html' => $this->renderGlobalResources($course),
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
            'global_resources_html' => $this->renderGlobalResources($course),
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
            'global_resources_html' => $this->renderGlobalResources($course),
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
            'global_resources_html' => $this->renderGlobalResources($course),
            'stats' => $this->courseStats($course),
        ]);
    }

    public function storeResource(Request $request, Course $course): JsonResponse
    {
        $this->normalizeResourceSectionInput($request);

        $validated = $this->resourceService->validatePayload($request);
        $validated['course_section_id'] = $validated['course_section_id'] ?? null;
        $validated['is_published'] = filter_var($validated['is_published'] ?? true, FILTER_VALIDATE_BOOLEAN);

        $this->resourceService->store(
            $course,
            $validated,
            $request->file('file')
        );

        return $this->jsonSuccess('تم إضافة المورد بنجاح', $this->curriculumResponse($course));
    }

    public function updateResource(Request $request, Course $course, CourseResource $resource): JsonResponse
    {
        $this->ensureResourceBelongsToCourse($course, $resource);

        $this->normalizeResourceSectionInput($request);

        $validated = $this->resourceService->validatePayload($request, true);
        $validated['course_section_id'] = $validated['course_section_id'] ?? null;
        $validated['is_published'] = filter_var($validated['is_published'] ?? $resource->is_published, FILTER_VALIDATE_BOOLEAN);

        $this->resourceService->update($resource, $validated, $request->file('file'));

        return $this->jsonSuccess('تم تحديث المورد بنجاح', $this->curriculumResponse($course));
    }

    public function destroyResource(Course $course, CourseResource $resource): JsonResponse
    {
        $this->ensureResourceBelongsToCourse($course, $resource);

        $this->resourceService->destroy($resource);

        return $this->jsonSuccess('تم حذف المورد بنجاح', $this->curriculumResponse($course));
    }

    public function reorder(Request $request, Course $course): JsonResponse
    {
        $validated = $request->validate([
            'sections' => 'nullable|array',
            'sections.*' => 'integer|exists:course_sections,id',
            'lessons' => 'nullable|array',
            'lessons.*' => 'integer|exists:course_lessons,id',
            'section_id' => 'nullable|integer|exists:course_sections,id',
            'resources' => 'nullable|array',
            'resources.*' => 'integer|exists:course_resources,id',
            'resource_section_id' => 'nullable|integer|exists:course_sections,id',
            'global_resources' => 'nullable|array',
            'global_resources.*' => 'integer|exists:course_resources,id',
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

        if (! empty($validated['resources'])) {
            $this->resourceService->reorderResources(
                $course,
                $validated['resource_section_id'] ?? null,
                $validated['resources']
            );
        }

        if (! empty($validated['global_resources'])) {
            $this->resourceService->reorderResources($course, null, $validated['global_resources']);
        }

        return $this->jsonSuccess('تم تحديث الترتيب', $this->curriculumResponse($course));
    }

    protected function curriculumResponse(Course $course): array
    {
        return [
            'html' => $this->renderSections($course),
            'global_resources_html' => $this->renderGlobalResources($course),
            'stats' => $this->courseStats($course),
        ];
    }

    protected function renderSections(Course $course): string
    {
        $course->load(['sections.lessons', 'sections.resources']);

        return view('admin.courses.curriculum.partials.sections', compact('course'))->render();
    }

    protected function renderGlobalResources(Course $course): string
    {
        $course->load(['resources' => fn ($q) => $q->whereNull('course_section_id')]);
        $globalResources = $course->resources;

        return view('admin.courses.curriculum.partials.global-resources', compact('course', 'globalResources'))->render();
    }

    protected function courseStats(Course $course): array
    {
        $course->refresh();

        return [
            'sections_count' => $course->sections()->count(),
            'lessons_count' => $course->lessons_count,
            'duration_hours' => $course->duration_hours,
            'resources_count' => $course->resources()->count(),
        ];
    }

    protected function jsonSuccess(string $message, array $extra = []): JsonResponse
    {
        return response()->json(array_merge(['success' => true, 'message' => $message], $extra));
    }

    protected function normalizeSectionId(mixed $sectionId): ?int
    {
        if ($sectionId === null || $sectionId === '' || $sectionId === 'global') {
            return null;
        }

        return (int) $sectionId;
    }

    protected function normalizeResourceSectionInput(Request $request): void
    {
        $sectionId = $request->input('course_section_id');

        if ($sectionId === 'global' || $sectionId === '' || $sectionId === null) {
            $request->merge(['course_section_id' => null]);
        }
    }

    protected function ensureSectionBelongsToCourse(Course $course, CourseSection $section): void
    {
        abort_unless($section->course_id === $course->id, 404);
    }

    protected function ensureLessonBelongsToCourse(Course $course, CourseLesson $lesson): void
    {
        abort_unless($lesson->section?->course_id === $course->id, 404);
    }

    protected function ensureResourceBelongsToCourse(Course $course, CourseResource $resource): void
    {
        abort_unless($resource->course_id === $course->id, 404);
    }
}
