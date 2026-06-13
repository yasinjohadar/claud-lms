<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HeroSliderSetting extends Model
{
    public const EFFECTS = ['fade', 'slide'];

    protected $fillable = [
        'autoplay_enabled',
        'autoplay_delay_ms',
        'transition_speed_ms',
        'loop',
        'pause_on_hover',
        'effect',
        'show_navigation',
        'show_pagination',
        'show_progress_bar',
    ];

    protected $casts = [
        'autoplay_enabled' => 'boolean',
        'autoplay_delay_ms' => 'integer',
        'transition_speed_ms' => 'integer',
        'loop' => 'boolean',
        'pause_on_hover' => 'boolean',
        'show_navigation' => 'boolean',
        'show_pagination' => 'boolean',
        'show_progress_bar' => 'boolean',
    ];

    public static function instance(): self
    {
        return static::query()->firstOrCreate([], [
            'autoplay_enabled' => true,
            'autoplay_delay_ms' => 6000,
            'transition_speed_ms' => 900,
            'loop' => true,
            'pause_on_hover' => true,
            'effect' => 'fade',
            'show_navigation' => true,
            'show_pagination' => true,
            'show_progress_bar' => true,
        ]);
    }
}
