<?php

namespace App\Support;

use App\Models\HeroSlide;

class HeroSlidePresenter
{
    public function __construct(
        protected HeroSlide $slide
    ) {}

    public static function make(HeroSlide $slide): self
    {
        return new self($slide);
    }

    public function slideClasses(): string
    {
        $classes = [
            'hero-slide',
            'hero-slide-' . $this->slide->theme_variant,
        ];

        if ($this->slide->custom_css_class) {
            $classes[] = $this->slide->custom_css_class;
        }

        if ($this->slide->min_height === 'tall') {
            $classes[] = 'hero-slide--tall';
        } elseif ($this->slide->min_height === 'compact') {
            $classes[] = 'hero-slide--compact';
        }

        if ($this->slide->layout === 'content_left_visual_right') {
            $classes[] = 'hero-slide--swap';
        }

        return implode(' ', $classes);
    }

    public function slideStyle(): string
    {
        $vars = [
            '--slide-accent' => $this->slide->accent_color,
            '--slide-accent-2' => $this->slide->accent_color_2 ?: $this->slide->accent_color,
        ];

        $parts = collect($vars)->map(fn ($v, $k) => "{$k}: {$v}")->implode('; ');

        return $parts;
    }

    public function backgroundStyle(): string
    {
        $slide = $this->slide;

        return match ($slide->background_type) {
            'solid' => $slide->background_color
                ? "background: {$slide->background_color};"
                : '',
            'gradient' => $this->gradientStyle(),
            'image' => $this->imageBackgroundStyle(),
            default => '',
        };
    }

    public function showThemeBackground(): bool
    {
        return $this->slide->background_type === 'theme';
    }

    public function showDecorativeShapes(): bool
    {
        return $this->slide->show_decorative_shapes && $this->slide->background_type === 'theme';
    }

    public function contentColumnClass(): string
    {
        $align = match ($this->slide->content_align) {
            'center' => 'text-center',
            'end' => 'text-center text-lg-end',
            default => 'text-center text-lg-start',
        };

        $mobile = $this->slide->mobile_content_align
            ? match ($this->slide->mobile_content_align) {
                'center' => 'text-center',
                'end' => 'text-end',
                default => 'text-start',
            }
            : null;

        $col = $this->slide->isVisualOnly() ? 'col-12' : 'col-lg-6';

        return trim("{$col} hero-content {$align}" . ($mobile ? " {$mobile}" : ''));
    }

    public function visualColumnClass(): string
    {
        $hidden = $this->slide->hide_visual_on_mobile ? 'd-none d-lg-block' : 'd-block';

        return "col-lg-6 hero-visual {$hidden}";
    }

    public function showContent(): bool
    {
        return ! $this->slide->isVisualOnly();
    }

    public function showVisual(): bool
    {
        return ! $this->slide->isContentOnly() && $this->slide->visual_type !== 'hidden';
    }

    public function rowClass(): string
    {
        $classes = ['row', 'align-items-center', 'gy-5', 'min-vh-hero'];

        if ($this->slide->layout === 'content_left_visual_right') {
            $classes[] = 'flex-lg-row-reverse';
        }

        return implode(' ', $classes);
    }

    public function typingPhrasesJson(): string
    {
        return json_encode($this->slide->heading_typing_phrases ?? [], JSON_UNESCAPED_UNICODE);
    }

    private function gradientStyle(): string
    {
        $from = $this->slide->gradient_from ?: $this->slide->accent_color;
        $to = $this->slide->gradient_to ?: ($this->slide->accent_color_2 ?: $this->slide->accent_color);
        $angle = $this->slide->gradient_angle ?? 135;

        if ($this->slide->gradient_type === 'radial') {
            return "background: radial-gradient(circle, {$from}, {$to});";
        }

        return "background: linear-gradient({$angle}deg, {$from}, {$to});";
    }

    private function imageBackgroundStyle(): string
    {
        $url = $this->slide->background_image_url;
        if (! $url) {
            return '';
        }

        $position = $this->slide->background_position ?? 'center center';
        $size = $this->slide->background_size ?? 'cover';

        return "background-image: url('{$url}'); background-position: {$position}; background-size: {$size}; background-repeat: no-repeat;";
    }

    public function imageOverlayStyle(): ?string
    {
        if ($this->slide->background_type !== 'image') {
            return null;
        }

        $color = $this->slide->background_overlay_color ?? '#000000';
        $opacity = $this->slide->background_overlay_opacity ?? 0.35;

        return "background-color: {$color}; opacity: {$opacity};";
    }
}
