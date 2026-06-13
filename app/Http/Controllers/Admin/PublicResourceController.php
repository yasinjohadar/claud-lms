<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\RespondsWithAjaxTable;
use App\Http\Controllers\Controller;
use App\Models\PublicResource;
use App\Services\PublicResourceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicResourceController extends Controller
{
    use RespondsWithAjaxTable;

    public function __construct(
        protected PublicResourceService $resourceService
    ) {}

    public function index(Request $request)
    {
        $data = $this->buildIndexData($request);

        if ($response = $this->ajaxTableResponse(
            $request,
            $data,
            'admin.pages.public-resources.partials.list',
            'admin.pages.public-resources.partials.modals'
        )) {
            return $response;
        }

        return view('admin.pages.public-resources.index', $data);
    }

    public function create(): View
    {
        return view('admin.pages.public-resources.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->resourceService->validatePayload($request);
        $this->resourceService->store($validated, $request->file('file'));

        return redirect()
            ->route('admin.public-resources.index')
            ->with('success', 'تم إنشاء المورد العام بنجاح');
    }

    public function edit(PublicResource $publicResource): View
    {
        return view('admin.pages.public-resources.edit', ['resource' => $publicResource]);
    }

    public function update(Request $request, PublicResource $publicResource): RedirectResponse
    {
        $validated = $this->resourceService->validatePayload($request, true);
        $this->resourceService->update($publicResource, $validated, $request->file('file'));

        return redirect()
            ->route('admin.public-resources.index')
            ->with('success', 'تم تحديث المورد العام بنجاح');
    }

    public function destroy(Request $request, PublicResource $publicResource): RedirectResponse|JsonResponse
    {
        $this->resourceService->destroy($publicResource);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'تم حذف المورد بنجاح']);
        }

        return redirect()
            ->route('admin.public-resources.index')
            ->with('success', 'تم حذف المورد العام بنجاح');
    }

    /**
     * @return array{resources: \Illuminate\Contracts\Pagination\LengthAwarePaginator, stats: array<string, int>}
     */
    private function buildIndexData(Request $request): array
    {
        $query = PublicResource::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type') && in_array($request->type, PublicResource::TYPES, true)) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_published', $request->status === 'published');
        }

        $resources = $query->orderBy('sort_order')->orderByDesc('id')->paginate(20)->withQueryString();

        $stats = [
            'total' => PublicResource::count(),
            'published' => PublicResource::where('is_published', true)->count(),
            'links' => PublicResource::where('type', 'link')->count(),
            'files' => PublicResource::where('type', 'file')->count(),
            'filtered' => $resources->total(),
        ];

        return compact('resources', 'stats');
    }
}
