<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseCategoryController extends Controller
{
    use RespondsWithAjaxTable;

    public function index(Request $request)
    {
        $data = $this->buildCategoriesIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.courses.categories.partials.list',
            'admin.courses.categories.partials.modals'
        )) {
            return $response;
        }

        return view('admin.courses.categories.index', $data);
    }

    /**
     * @return array{categories: \Illuminate\Contracts\Pagination\LengthAwarePaginator, parentCategories: \Illuminate\Support\Collection, stats: array<string, int>}
     */
    private function buildCategoriesIndexData(Request $request): array
    {
        $query = CourseCategory::with('parent')->withCount('courses');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('parent')) {
            if ($request->parent === 'root') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', $request->parent);
            }
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $categories = $query->orderBy('order')->orderBy('name')->paginate(20)->withQueryString();
        $parentCategories = CourseCategory::whereNull('parent_id')->orderBy('name')->get();

        $stats = [
            'total' => CourseCategory::count(),
            'active' => CourseCategory::where('is_active', true)->count(),
            'inactive' => CourseCategory::where('is_active', false)->count(),
            'courses' => Course::count(),
            'filtered' => $categories->total(),
        ];

        return compact('categories', 'parentCategories', 'stats');
    }

    public function create()
    {
        $parentCategories = CourseCategory::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.courses.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);

        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['order'] = $validated['order'] ?? ((CourseCategory::max('order') ?? 0) + 1);

        CourseCategory::create($validated);

        return redirect()->route('admin.courses.categories.index')->with('success', 'تم إنشاء التصنيف بنجاح');
    }

    public function edit(CourseCategory $category)
    {
        $parentCategories = CourseCategory::whereNull('parent_id')->where('id', '!=', $category->id)->orderBy('name')->get();

        return view('admin.courses.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, CourseCategory $category)
    {
        $validated = $this->validateCategory($request, $category->id);

        if ($validated['name'] !== $category->name) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $category->id);
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $category->update($validated);
        $category->updateCoursesCount();

        return redirect()->route('admin.courses.categories.index')->with('success', 'تم تحديث التصنيف بنجاح');
    }

    public function destroy(Request $request, CourseCategory $category)
    {
        if ($category->courses()->exists()) {
            if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json(['success' => false, 'message' => 'لا يمكن حذف تصنيف يحتوي على كورسات'], 422);
            }

            return back()->with('error', 'لا يمكن حذف تصنيف يحتوي على كورسات');
        }

        $category->delete();

        if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'تم حذف التصنيف بنجاح']);
        }

        return redirect()->route('admin.courses.categories.index')->with('success', 'تم حذف التصنيف بنجاح');
    }

    public function toggleActive(CourseCategory $category)
    {
        $category->update(['is_active' => ! $category->is_active]);

        return back()->with('success', 'تم تحديث حالة التصنيف');
    }

    protected function validateCategory(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255|unique:course_categories,name,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:course_categories,id',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer|min:0',
        ]);
    }

    protected function uniqueSlug(string $name, ?int $exceptId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $counter = 1;

        while (CourseCategory::where('slug', $slug)->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))->exists()) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}