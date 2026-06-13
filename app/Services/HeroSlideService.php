<?php

namespace App\Services;

use App\Models\HeroSlide;
use App\Support\HeroSlidePresenter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class HeroSlideService
{
    public function store(array $data, ?UploadedFile $backgroundImage = null, ?UploadedFile $visualImage = null): HeroSlide
    {
        $sortOrder = (int) HeroSlide::max('sort_order') + 1;
        $attributes = $this->buildAttributes($data, $sortOrder);

        if ($backgroundImage) {
            $attributes['background_image'] = $this->storeImage($backgroundImage, 'hero-slides/backgrounds');
        }

        if ($visualImage) {
            $attributes['visual_image'] = $this->storeImage($visualImage, 'hero-slides/visuals');
        }

        return HeroSlide::create($attributes);
    }

    public function update(
        HeroSlide $slide,
        array $data,
        ?UploadedFile $backgroundImage = null,
        ?UploadedFile $visualImage = null,
        bool $removeBackgroundImage = false,
        bool $removeVisualImage = false
    ): HeroSlide {
        $attributes = $this->buildAttributes($data, $slide->sort_order);

        if ($removeBackgroundImage) {
            $this->deleteStoredFile($slide->background_image);
            $attributes['background_image'] = null;
        } elseif ($backgroundImage) {
            $this->deleteStoredFile($slide->background_image);
            $attributes['background_image'] = $this->storeImage($backgroundImage, 'hero-slides/backgrounds');
        } else {
            $attributes['background_image'] = $slide->background_image;
        }

        if ($removeVisualImage) {
            $this->deleteStoredFile($slide->visual_image);
            $attributes['visual_image'] = null;
        } elseif ($visualImage) {
            $this->deleteStoredFile($slide->visual_image);
            $attributes['visual_image'] = $this->storeImage($visualImage, 'hero-slides/visuals');
        } else {
            $attributes['visual_image'] = $slide->visual_image;
        }

        $slide->update($attributes);

        return $slide->fresh();
    }

    public function destroy(HeroSlide $slide): void
    {
        $this->deleteStoredFile($slide->background_image);
        $this->deleteStoredFile($slide->visual_image);
        $slide->delete();
    }

    public function duplicate(HeroSlide $slide): HeroSlide
    {
        $copy = $slide->replicate(['background_image', 'visual_image']);
        $copy->admin_title = $slide->admin_title . ' (نسخة)';
        $copy->sort_order = (int) HeroSlide::max('sort_order') + 1;
        $copy->is_active = false;
        $copy->save();

        return $copy;
    }

    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $index => $id) {
            HeroSlide::whereKey($id)->update(['sort_order' => $index + 1]);
        }
    }

    public function toggleActive(HeroSlide $slide): HeroSlide
    {
        $slide->update(['is_active' => ! $slide->is_active]);

        return $slide->fresh();
    }

    public function validatePayload(Request $request, bool $isUpdate = false): array
    {
        $rules = [
            'admin_title' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'sometimes|boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'pagination_label' => 'nullable|string|max:60',
            'layout' => ['required', Rule::in(HeroSlide::LAYOUTS)],
            'content_align' => ['required', Rule::in(HeroSlide::CONTENT_ALIGNS)],
            'min_height' => ['required', Rule::in(HeroSlide::MIN_HEIGHTS)],
            'mobile_content_align' => ['nullable', Rule::in(HeroSlide::CONTENT_ALIGNS)],
            'background_type' => ['required', Rule::in(HeroSlide::BACKGROUND_TYPES)],
            'background_color' => 'nullable|string|max:20',
            'gradient_from' => 'nullable|string|max:20',
            'gradient_to' => 'nullable|string|max:20',
            'gradient_angle' => 'nullable|integer|min:0|max:360',
            'gradient_type' => 'nullable|in:linear,radial',
            'background_overlay_color' => 'nullable|string|max:20',
            'background_overlay_opacity' => 'nullable|numeric|min:0|max:1',
            'background_position' => 'nullable|string|max:40',
            'background_size' => 'nullable|string|max:40',
            'accent_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'accent_color_2' => ['nullable', 'regex:/^#([A-Fa-f0-9]{6})$/'],
            'show_decorative_shapes' => 'sometimes|boolean',
            'theme_variant' => ['required', Rule::in(HeroSlide::THEME_VARIANTS)],
            'badge_text' => 'nullable|string|max:255',
            'badge_icon' => 'nullable|string|max:80',
            'heading_mode' => ['required', Rule::in(HeroSlide::HEADING_MODES)],
            'heading_prefix' => 'nullable|string|max:500',
            'heading_highlight' => 'nullable|string|max:255',
            'heading_typing_phrases' => 'nullable|array|max:10',
            'heading_typing_phrases.*' => 'nullable|string|max:120',
            'description' => 'nullable|string|max:5000',
            'buttons' => 'nullable|array|max:3',
            'buttons.*.label' => 'required_with:buttons|string|max:120',
            'buttons.*.url' => 'required_with:buttons|string|max:500',
            'buttons.*.style' => ['nullable', Rule::in(HeroSlide::BUTTON_STYLES)],
            'buttons.*.icon' => 'nullable|string|max:80',
            'buttons.*.open_in_new_tab' => 'sometimes|boolean',
            'visual_type' => ['required', Rule::in(HeroSlide::VISUAL_TYPES)],
            'visual_image_alt' => 'nullable|string|max:255',
            'visual_icon' => 'nullable|string|max:80',
            'visual_extras' => 'nullable|array',
            'hide_visual_on_mobile' => 'sometimes|boolean',
            'custom_css_class' => 'nullable|string|max:100',
            'aria_label' => 'nullable|string|max:255',
            'remove_background_image' => 'sometimes|boolean',
            'remove_visual_image' => 'sometimes|boolean',
        ];

        if ($request->input('background_type') === 'image' && ! $isUpdate) {
            $rules['background_image_file'] = 'nullable|image|max:4096';
        } else {
            $rules['background_image_file'] = 'nullable|image|max:4096';
        }

        if ($request->input('visual_type') === 'image') {
            $rules['visual_image_file'] = ($isUpdate ? 'nullable' : 'nullable') . '|image|max:4096';
        } else {
            $rules['visual_image_file'] = 'nullable|image|max:4096';
        }

        return $request->validate($rules);
    }

    private function buildAttributes(array $data, int $sortOrder): array
    {
        $buttons = collect($data['buttons'] ?? [])
            ->filter(fn ($btn) => ! empty($btn['label']) && ! empty($btn['url']))
            ->take(3)
            ->map(fn ($btn) => [
                'label' => $btn['label'],
                'url' => $btn['url'],
                'style' => $btn['style'] ?? 'primary',
                'icon' => $btn['icon'] ?? null,
                'open_in_new_tab' => (bool) ($btn['open_in_new_tab'] ?? false),
            ])
            ->values()
            ->all();

        $typingPhrases = collect($data['heading_typing_phrases'] ?? [])
            ->filter()
            ->values()
            ->all();

        return [
            'admin_title' => $data['admin_title'],
            'sort_order' => $data['sort_order'] ?? $sortOrder,
            'is_active' => (bool) ($data['is_active'] ?? true),
            'starts_at' => $data['starts_at'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'pagination_label' => $data['pagination_label'] ?? null,
            'layout' => $data['layout'],
            'content_align' => $data['content_align'],
            'min_height' => $data['min_height'],
            'mobile_content_align' => $data['mobile_content_align'] ?? null,
            'background_type' => $data['background_type'],
            'background_color' => $data['background_color'] ?? null,
            'gradient_from' => $data['gradient_from'] ?? null,
            'gradient_to' => $data['gradient_to'] ?? null,
            'gradient_angle' => (int) ($data['gradient_angle'] ?? 135),
            'gradient_type' => $data['gradient_type'] ?? 'linear',
            'background_overlay_color' => $data['background_overlay_color'] ?? null,
            'background_overlay_opacity' => isset($data['background_overlay_opacity']) ? (float) $data['background_overlay_opacity'] : null,
            'background_position' => $data['background_position'] ?? 'center center',
            'background_size' => $data['background_size'] ?? 'cover',
            'accent_color' => $data['accent_color'],
            'accent_color_2' => $data['accent_color_2'] ?? null,
            'show_decorative_shapes' => (bool) ($data['show_decorative_shapes'] ?? true),
            'theme_variant' => $data['theme_variant'],
            'badge_text' => $data['badge_text'] ?? null,
            'badge_icon' => $data['badge_icon'] ?? null,
            'heading_mode' => $data['heading_mode'],
            'heading_prefix' => $data['heading_prefix'] ?? null,
            'heading_highlight' => $data['heading_highlight'] ?? null,
            'heading_typing_phrases' => $typingPhrases ?: null,
            'description' => $data['description'] ?? null,
            'buttons' => $buttons ?: null,
            'visual_type' => $data['visual_type'],
            'visual_image_alt' => $data['visual_image_alt'] ?? null,
            'visual_icon' => $data['visual_icon'] ?? null,
            'visual_extras' => $this->normalizeVisualExtras($data['visual_extras'] ?? [], $data['visual_type'] ?? 'main'),
            'hide_visual_on_mobile' => (bool) ($data['hide_visual_on_mobile'] ?? true),
            'custom_css_class' => $data['custom_css_class'] ?? null,
            'aria_label' => $data['aria_label'] ?? null,
        ];
    }

    private function normalizeVisualExtras(array $extras, string $visualType): ?array
    {
        if ($visualType === 'main') {
            $cards = collect($extras['float_cards'] ?? [])
                ->filter(fn ($c) => ! empty($c['title']) || ! empty($c['value']))
                ->take(2)
                ->values()
                ->all();

            return $cards ? ['float_cards' => $cards] : null;
        }

        if ($visualType === 'code') {
            return ! empty($extras['code_snippet']) ? ['code_snippet' => $extras['code_snippet']] : null;
        }

        if ($visualType === 'design') {
            $icons = collect($extras['orbit_icons'] ?? [])
                ->filter(fn ($i) => ! empty($i['icon']))
                ->take(6)
                ->values()
                ->all();

            return $icons ? ['orbit_icons' => $icons, 'center_icon' => $extras['center_icon'] ?? 'fas fa-paint-brush'] : null;
        }

        if ($visualType === 'ai') {
            $tags = collect($extras['ai_tags'] ?? [])->filter()->take(8)->values()->all();

            return [
                'ai_tags' => $tags,
                'center_icon' => $extras['center_icon'] ?? 'fas fa-brain',
            ];
        }

        return null;
    }

    private function storeImage(UploadedFile $file, string $directory): string
    {
        return $file->store($directory, 'public');
    }

    private function deleteStoredFile(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}
