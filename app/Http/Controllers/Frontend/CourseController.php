<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseTag;
use App\Services\CourseCatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        protected CourseCatalogService $catalog
    ) {}

    public function index(Request $request): View
    {
        $filters = $this->catalog->filtersFromRequest($request);
        $courses = $this->catalog->paginate($filters);
        $categories = CourseCategory::active()->orderBy('order')->get();
        $tags = CourseTag::active()->orderBy('order')->get();
        $maxPrice = $this->catalog->getMaxPrice();
        $stats = $this->courseStats();

        return view('frontend.pages.courses', compact(
            'courses',
            'categories',
            'tags',
            'maxPrice',
            'filters',
            'stats'
        ));
    }

    public function search(Request $request): JsonResponse
    {
        $filters = $this->catalog->filtersFromRequest($request);
        $view = $request->input('view', 'grid');
        $limit = $request->filled('limit') ? min((int) $request->input('limit'), 20) : null;

        if ($limit) {
            $courses = $this->catalog->limit($filters, $limit);

            return response()->json([
                'html' => $this->renderCoursesHtml($courses, $view, false),
                'count' => $courses->count(),
                'pagination' => '',
            ]);
        }

        $courses = $this->catalog->paginate($filters, (int) $request->input('per_page', 9));

        return response()->json([
            'html' => $this->renderCoursesHtml($courses, $view),
            'count' => $courses->total(),
            'pagination' => view('frontend.partials.courses-pagination', compact('courses'))->render(),
            'meta' => [
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
                'per_page' => $courses->perPage(),
            ],
        ]);
    }

    public function show(string $slug): View
    {
        $course = Course::where('slug', $slug)
            ->published()
            ->with([
                'category',
                'instructor',
                'tags',
                'sections.lessons',
                'sections.resources' => fn ($q) => $q->published(),
                'globalResources',
            ])
            ->firstOrFail();

        $course->increment('views_count');

        $relatedCourses = Course::published()
            ->where('id', '!=', $course->id)
            ->where('course_category_id', $course->course_category_id)
            ->with(['category', 'instructor'])
            ->orderByDesc('students_count')
            ->take(3)
            ->get();

        if ($relatedCourses->count() < 3) {
            $relatedCourses = Course::published()
                ->where('id', '!=', $course->id)
                ->with(['category', 'instructor'])
                ->orderByDesc('students_count')
                ->take(3)
                ->get();
        }

        return view('frontend.pages.course-detail', compact('course', 'relatedCourses'));
    }

    public function serveImage(string $filename): Response
    {
        return $this->serveFile('courses/images/' . $filename);
    }

    public function serveThumbnail(string $filename): Response
    {
        return $this->serveFile('courses/thumbnails/' . $filename);
    }

    protected function serveFile(string $path): Response
    {
        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $mime = Storage::disk('public')->mimeType($path);

        return response(Storage::disk('public')->get($path), 200, [
            'Content-Type' => $mime,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    protected function renderCoursesHtml($courses, string $view = 'grid', bool $wrapGrid = true): string
    {
        $html = '';

        if ($courses->isEmpty()) {
            return '<div class="col-12">
                <div class="courses-empty">
                    <div class="courses-empty-icon"><i class="fas fa-search"></i></div>
                    <h5>لم يتم العثور على كورسات</h5>
                    <p class="text-secondary">حاول تغيير معايير البحث أو التصفية</p>
                </div>
            </div>';
        }

        foreach ($courses as $course) {
            if ($view === 'list') {
                $html .= view('frontend.partials.course-list-card', ['course' => $course])->render();
            } elseif ($wrapGrid) {
                $html .= view('frontend.partials.course-card-grid', ['course' => $course])->render();
            } else {
                $html .= view('frontend.partials.course-card', ['course' => $course])->render();
            }
        }

        return $html;
    }

    protected function courseStats(): array
    {
        $published = Course::published();

        return [
            'total' => $published->count(),
            'categories' => CourseCategory::active()->count(),
            'avg_rating' => round((float) $published->avg('rating_avg'), 1) ?: 4.8,
        ];
    }
}
