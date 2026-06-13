<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use App\Services\HeroSlideService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function __construct(
        protected HeroSlideService $slideService
    ) {
        $this->middleware('auth');
        $this->middleware('permission:hero-slide-list')->only('index');
        $this->middleware('permission:hero-slide-create')->only(['create', 'store', 'duplicate']);
        $this->middleware('permission:hero-slide-edit')->only(['edit', 'update', 'reorder', 'toggle']);
        $this->middleware('permission:hero-slide-delete')->only('destroy');
    }

    public function index(): View
    {
        $slides = HeroSlide::ordered()->get();
        $stats = [
            'total' => $slides->count(),
            'active' => $slides->where('is_active', true)->count(),
            'scheduled' => $slides->filter(fn ($s) => $s->starts_at || $s->expires_at)->count(),
        ];

        return view('admin.pages.hero-slides.index', compact('slides', 'stats'));
    }

    public function create(): View
    {
        return view('admin.pages.hero-slides.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $this->mergeAiTags($request);
        $validated = $this->slideService->validatePayload($request);
        $this->slideService->store(
            $validated,
            $request->file('background_image_file'),
            $request->file('visual_image_file')
        );

        return redirect()->route('admin.hero-slides.index')->with('success', 'تم إنشاء الشريحة بنجاح');
    }

    public function edit(HeroSlide $heroSlide): View
    {
        return view('admin.pages.hero-slides.edit', array_merge($this->formOptions(), ['slide' => $heroSlide]));
    }

    public function update(Request $request, HeroSlide $heroSlide): RedirectResponse
    {
        $this->mergeAiTags($request);
        $validated = $this->slideService->validatePayload($request, true);
        $this->slideService->update(
            $heroSlide,
            $validated,
            $request->file('background_image_file'),
            $request->file('visual_image_file'),
            $request->boolean('remove_background_image'),
            $request->boolean('remove_visual_image')
        );

        return redirect()->route('admin.hero-slides.index')->with('success', 'تم تحديث الشريحة بنجاح');
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        $this->slideService->destroy($heroSlide);

        return redirect()->route('admin.hero-slides.index')->with('success', 'تم حذف الشريحة');
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:hero_slides,id',
        ]);

        $this->slideService->reorder($validated['order']);

        return response()->json(['success' => true, 'message' => 'تم تحديث الترتيب']);
    }

    public function toggle(HeroSlide $heroSlide): JsonResponse
    {
        $slide = $this->slideService->toggleActive($heroSlide);

        return response()->json([
            'success' => true,
            'is_active' => $slide->is_active,
            'message' => $slide->is_active ? 'تم تفعيل الشريحة' : 'تم إيقاف الشريحة',
        ]);
    }

    public function duplicate(HeroSlide $heroSlide): RedirectResponse
    {
        $copy = $this->slideService->duplicate($heroSlide);

        return redirect()
            ->route('admin.hero-slides.edit', $copy)
            ->with('success', 'تم نسخ الشريحة — يمكنك تعديلها الآن');
    }

    private function formOptions(): array
    {
        return [
            'layouts' => HeroSlide::LAYOUTS,
            'contentAligns' => HeroSlide::CONTENT_ALIGNS,
            'minHeights' => HeroSlide::MIN_HEIGHTS,
            'backgroundTypes' => HeroSlide::BACKGROUND_TYPES,
            'headingModes' => HeroSlide::HEADING_MODES,
            'visualTypes' => HeroSlide::VISUAL_TYPES,
            'themeVariants' => HeroSlide::THEME_VARIANTS,
            'buttonStyles' => HeroSlide::BUTTON_STYLES,
        ];
    }

    private function mergeAiTags(Request $request): void
    {
        if ($request->filled('visual_extras_ai_tags')) {
            $tags = collect(explode(',', $request->input('visual_extras_ai_tags')))
                ->map(fn ($t) => trim($t))
                ->filter()
                ->values()
                ->all();
            $extras = $request->input('visual_extras', []);
            $extras['ai_tags'] = $tags;
            $request->merge(['visual_extras' => $extras]);
        }
    }
}
