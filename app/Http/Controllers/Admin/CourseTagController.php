<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Models\CourseTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseTagController extends Controller
{
    use RespondsWithAjaxTable;

    public function index(Request $request)
    {
        $data = $this->buildTagsIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.courses.tags.partials.list',
            'admin.courses.tags.partials.modals'
        )) {
            return $response;
        }

        return view('admin.courses.tags.index', $data);
    }

    /**
     * @return array{tags: \Illuminate\Contracts\Pagination\LengthAwarePaginator, stats: array<string, int>}
     */
    private function buildTagsIndexData(Request $request): array
    {
        $query = CourseTag::withCount('courses');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('sort') && $request->sort === 'popular') {
            $query->orderByDesc('courses_count')->orderBy('name');
        } else {
            $query->orderBy('order')->orderBy('name');
        }

        $tags = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => CourseTag::count(),
            'active' => CourseTag::where('is_active', true)->count(),
            'used' => CourseTag::where('courses_count', '>', 0)->count(),
            'courses' => (int) CourseTag::sum('courses_count'),
            'filtered' => $tags->total(),
        ];

        return compact('tags', 'stats');
    }

    public function create()
    {
        return view('admin.courses.tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:course_tags,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? ((CourseTag::max('order') ?? 0) + 1);

        CourseTag::create($validated);

        return redirect()->route('admin.courses.tags.index')->with('success', 'تم إنشاء التاغ بنجاح');
    }

    public function edit(CourseTag $tag)
    {
        return view('admin.courses.tags.edit', compact('tag'));
    }

    public function update(Request $request, CourseTag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:course_tags,name,' . $tag->id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'order' => 'nullable|integer|min:0',
        ]);

        if ($validated['name'] !== $tag->name) {
            $validated['slug'] = $this->uniqueSlug($validated['name'], $tag->id);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $tag->update($validated);
        $tag->updateCoursesCount();

        return redirect()->route('admin.courses.tags.index')->with('success', 'تم تحديث التاغ بنجاح');
    }

    public function destroy(Request $request, CourseTag $tag)
    {
        $tag->courses()->detach();
        $tag->delete();

        if ($request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json(['success' => true, 'message' => 'تم حذف التاغ بنجاح']);
        }

        return redirect()->route('admin.courses.tags.index')->with('success', 'تم حذف التاغ بنجاح');
    }

    protected function uniqueSlug(string $name, ?int $exceptId = null): string
    {
        $slug = Str::slug($name) ?: 'tag-' . time();
        $original = $slug;
        $counter = 1;

        while (CourseTag::where('slug', $slug)->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))->exists()) {
            $slug = $original . '-' . $counter++;
        }

        return $slug;
    }
}