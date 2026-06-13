<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseTag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    use RespondsWithAjaxTable;

    public function index(Request $request)
    {
        $data = $this->buildIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.courses.partials.list',
            'admin.courses.partials.modals'
        )) {
            return $response;
        }

        return view('admin.courses.index', $data);
    }

    /**
     * @return array{courses: \Illuminate\Contracts\Pagination\LengthAwarePaginator, categories: \Illuminate\Support\Collection, instructors: \Illuminate\Support\Collection, stats: array<string, int|float>}
     */
    private function buildIndexData(Request $request): array
    {
        $query = Course::with(['category', 'instructor', 'tags']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('course_category_id', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('instructor')) {
            $query->where('instructor_id', $request->instructor);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === '1');
        }

        $courses = $query->latest('created_at')->paginate(15)->withQueryString();
        $categories = CourseCategory::orderBy('name')->get();
        $instructors = User::role('instructor')->orderBy('name')->get();

        $stats = [
            'total' => Course::count(),
            'published' => Course::where('status', 'published')->count(),
            'draft' => Course::where('status', 'draft')->count(),
            'featured' => Course::where('is_featured', true)->count(),
            'filtered' => $courses->total(),
        ];

        return compact('courses', 'categories', 'instructors', 'stats');
    }

    public function create()
    {
        $categories = CourseCategory::orderBy('name')->get();
        $tags = CourseTag::orderBy('name')->get();
        $instructors = User::role('instructor')->orderBy('name')->get();

        return view('admin.courses.create', compact('categories', 'tags', 'instructors'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCourse($request);

        DB::beginTransaction();

        try {
            $data = $this->prepareCourseData($validated, $request);
            $course = Course::create($data);

            if (! empty($validated['tags'])) {
                $course->tags()->sync($validated['tags']);
            }

            $course->category?->updateCoursesCount();
            foreach ($course->tags as $tag) {
                $tag->updateCoursesCount();
            }

            DB::commit();

            return redirect()->route('admin.courses.index')->with('success', 'تم إنشاء الكورس بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function edit(Course $course)
    {
        $categories = CourseCategory::orderBy('name')->get();
        $tags = CourseTag::orderBy('name')->get();
        $instructors = User::role('instructor')->orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'categories', 'tags', 'instructors'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $this->validateCourse($request, $course->id);

        DB::beginTransaction();

        try {
            $data = $this->prepareCourseData($validated, $request, $course);
            $course->update($data);
            $course->tags()->sync($validated['tags'] ?? []);

            $course->category?->updateCoursesCount();
            foreach (CourseTag::all() as $tag) {
                $tag->updateCoursesCount();
            }

            DB::commit();

            return redirect()->route('admin.courses.index')->with('success', 'تم تحديث الكورس بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, Course $course)
    {
        $category = $course->category;

        if ($course->thumbnail) {
            Storage::disk('public')->delete($course->thumbnail);
        }

        $course->tags()->detach();
        $course->delete();

        $category?->updateCoursesCount();

        if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'تم حذف الكورس بنجاح']);
        }

        return redirect()->route('admin.courses.index')->with('success', 'تم حذف الكورس بنجاح');
    }

    public function toggleFeatured(Course $course)
    {
        $course->update(['is_featured' => ! $course->is_featured]);

        return back()->with('success', 'تم تحديث حالة التمييز');
    }

    public function togglePublish(Course $course)
    {
        $newStatus = $course->status === 'published' ? 'draft' : 'published';
        $course->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? ($course->published_at ?? now()) : $course->published_at,
        ]);

        $course->category?->updateCoursesCount();

        return back()->with('success', $newStatus === 'published' ? 'تم نشر الكورس' : 'تم إلغاء النشر');
    }

    protected function validateCourse(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:courses,slug,' . $id,
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'course_category_id' => 'required|exists:course_categories,id',
            'instructor_id' => 'required|exists:users,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'price' => 'required|numeric|min:0',
            'compare_at_price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'badge' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:50',
            'thumbnail' => 'nullable|image|max:2048',
            'thumbnail_alt' => 'nullable|string|max:255',
            'rating_avg' => 'nullable|numeric|min:0|max:5',
            'rating_count' => 'nullable|integer|min:0',
            'students_count' => 'nullable|integer|min:0',
            'lessons_count' => 'nullable|integer|min:0',
            'duration_hours' => 'nullable|integer|min:0',
            'language' => 'nullable|string|max:10',
            'what_you_learn' => 'nullable|string',
            'requirements' => 'nullable|string',
            'curriculum_outline' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'is_featured' => 'boolean',
            'order' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:course_tags,id',
        ]);
    }

    protected function prepareCourseData(array $validated, Request $request, ?Course $course = null): array
    {
        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        if (empty($slug)) {
            $slug = 'course-' . time();
        }

        $counter = 1;
        $original = $slug;
        while (Course::where('slug', $slug)->when($course, fn ($q) => $q->where('id', '!=', $course->id))->exists()) {
            $slug = $original . '-' . $counter++;
        }

        $data = [
            'title' => $validated['title'],
            'slug' => $slug,
            'excerpt' => $validated['excerpt'] ?? null,
            'description' => $validated['description'] ?? null,
            'course_category_id' => $validated['course_category_id'],
            'instructor_id' => $validated['instructor_id'],
            'level' => $validated['level'],
            'price' => $validated['price'],
            'compare_at_price' => $validated['compare_at_price'] ?? null,
            'currency' => $validated['currency'] ?? 'USD',
            'badge' => $validated['badge'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'thumbnail_alt' => $validated['thumbnail_alt'] ?? null,
            'rating_avg' => $validated['rating_avg'] ?? 0,
            'rating_count' => $validated['rating_count'] ?? 0,
            'students_count' => $validated['students_count'] ?? 0,
            'lessons_count' => $validated['lessons_count'] ?? 0,
            'duration_hours' => $validated['duration_hours'] ?? 0,
            'language' => $validated['language'] ?? 'ar',
            'what_you_learn' => $this->linesToArray($validated['what_you_learn'] ?? null),
            'requirements' => $this->linesToArray($validated['requirements'] ?? null),
            'curriculum_outline' => $validated['curriculum_outline'] ?? null,
            'status' => $validated['status'],
            'published_at' => $validated['published_at'] ?? ($validated['status'] === 'published' ? now() : null),
            'is_featured' => $request->boolean('is_featured'),
            'order' => $validated['order'] ?? ((Course::max('order') ?? 0) + 1),
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
        ];

        if ($request->hasFile('thumbnail')) {
            if ($course?->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        return $data;
    }

    protected function linesToArray(?string $text): ?array
    {
        if (empty(trim($text ?? ''))) {
            return null;
        }

        return array_values(array_filter(array_map('trim', explode("\n", $text))));
    }
}
