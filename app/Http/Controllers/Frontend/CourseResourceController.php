<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseResource;
use App\Services\CourseAccessService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseResourceController extends Controller
{
    public function __construct(
        protected CourseAccessService $accessService
    ) {}

    public function index(string $slug): View
    {
        $course = $this->findPublishedCourse($slug);
        $resources = $course->globalResources()->get();

        return view('frontend.pages.course-resources.index', compact('course', 'resources'));
    }

    public function show(string $slug, string $resourceSlug): View
    {
        $course = $this->findPublishedCourse($slug);
        $resource = $this->findGlobalResource($course, $resourceSlug);
        $resources = $course->globalResources()->get();

        return view('frontend.pages.course-resources.show', compact('course', 'resource', 'resources'));
    }

    public function download(string $slug, string $resourceSlug): StreamedResponse|Response
    {
        $course = $this->findPublishedCourse($slug);
        $resource = $this->findGlobalResource($course, $resourceSlug);

        abort_unless($this->accessService->canDownloadResource(auth()->user(), $resource), 403);

        return $this->streamFileDownload($resource);
    }

    public function downloadById(string $slug, CourseResource $resource): StreamedResponse|Response
    {
        $course = $this->findPublishedCourse($slug);

        abort_unless($resource->course_id === $course->id && $resource->is_published, 404);
        abort_unless($resource->isFile(), 404);
        abort_unless($this->accessService->canDownloadResource(auth()->user(), $resource), 403);

        return $this->streamFileDownload($resource);
    }

    protected function findPublishedCourse(string $slug): Course
    {
        return Course::where('slug', $slug)
            ->published()
            ->with(['category', 'instructor'])
            ->firstOrFail();
    }

    protected function findGlobalResource(Course $course, string $resourceSlug): CourseResource
    {
        return CourseResource::query()
            ->where('course_id', $course->id)
            ->whereNull('course_section_id')
            ->where('slug', $resourceSlug)
            ->published()
            ->firstOrFail();
    }

    protected function streamFileDownload(CourseResource $resource): StreamedResponse|Response
    {
        abort_unless($resource->isFile() && $resource->file_path, 404);

        $disk = Storage::disk('public');

        abort_unless($disk->exists($resource->file_path), 404);

        return $disk->download(
            $resource->file_path,
            $resource->file_original_name ?: basename($resource->file_path)
        );
    }
}
