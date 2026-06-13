<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    public const LAYOUTS = [
        'content_right_visual_left',
        'content_left_visual_right',
        'content_only',
        'visual_only',
    ];

    public const CONTENT_ALIGNS = ['start', 'center', 'end'];

    public const MIN_HEIGHTS = ['default', 'tall', 'compact'];

    public const BACKGROUND_TYPES = ['theme', 'solid', 'gradient', 'image'];

    public const HEADING_MODES = ['static', 'typing'];

    public const VISUAL_TYPES = ['hidden', 'image', 'icon', 'main', 'code', 'design', 'ai'];

    public const THEME_VARIANTS = ['main', 'code', 'design', 'ai'];

    public const BUTTON_STYLES = ['primary', 'glass'];

    protected $fillable = [
        'admin_title',
        'sort_order',
        'is_active',
        'starts_at',
        'expires_at',
        'pagination_label',
        'layout',
        'content_align',
        'min_height',
        'mobile_content_align',
        'background_type',
        'background_color',
        'gradient_from',
        'gradient_to',
        'gradient_angle',
        'gradient_type',
        'background_image',
        'background_overlay_color',
        'background_overlay_opacity',
        'background_position',
        'background_size',
        'accent_color',
        'accent_color_2',
        'show_decorative_shapes',
        'theme_variant',
        'badge_text',
        'badge_icon',
        'heading_mode',
        'heading_prefix',
        'heading_highlight',
        'heading_typing_phrases',
        'description',
        'buttons',
        'visual_type',
        'visual_image',
        'visual_image_alt',
        'visual_icon',
        'visual_extras',
        'hide_visual_on_mobile',
        'custom_css_class',
        'aria_label',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'gradient_angle' => 'integer',
        'background_overlay_opacity' => 'float',
        'show_decorative_shapes' => 'boolean',
        'heading_typing_phrases' => 'array',
        'buttons' => 'array',
        'visual_extras' => 'array',
        'hide_visual_on_mobile' => 'boolean',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            });
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getBackgroundImageUrlAttribute(): ?string
    {
        if (! $this->background_image) {
            return null;
        }

        if (str_starts_with($this->background_image, 'http')) {
            return $this->background_image;
        }

        return Storage::disk('public')->url($this->background_image);
    }

    public function getVisualImageUrlAttribute(): ?string
    {
        if (! $this->visual_image) {
            return null;
        }

        if (str_starts_with($this->visual_image, 'http')) {
            return $this->visual_image;
        }

        return Storage::disk('public')->url($this->visual_image);
    }

    public function isContentOnly(): bool
    {
        return $this->layout === 'content_only' || $this->visual_type === 'hidden';
    }

    public function isVisualOnly(): bool
    {
        return $this->layout === 'visual_only';
    }
}
