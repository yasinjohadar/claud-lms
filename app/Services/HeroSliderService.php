<?php

namespace App\Services;

use App\Models\HeroSlide;
use App\Models\HeroSliderSetting;
use Illuminate\Support\Collection;

class HeroSliderService
{
    public function getSettings(): HeroSliderSetting
    {
        return HeroSliderSetting::instance();
    }

    public function updateSettings(array $data): HeroSliderSetting
    {
        $settings = $this->getSettings();
        $settings->update($data);

        return $settings->fresh();
    }

    /**
     * @return Collection<int, HeroSlide>
     */
    public function getPublishedSlides(): Collection
    {
        return HeroSlide::published()->ordered()->get();
    }

    public function validateSettings(array $data): array
    {
        return validator($data, [
            'autoplay_enabled' => 'sometimes|boolean',
            'autoplay_delay_ms' => 'required|integer|min:1000|max:60000',
            'transition_speed_ms' => 'required|integer|min:100|max:5000',
            'loop' => 'sometimes|boolean',
            'pause_on_hover' => 'sometimes|boolean',
            'effect' => 'required|in:fade,slide',
            'show_navigation' => 'sometimes|boolean',
            'show_pagination' => 'sometimes|boolean',
            'show_progress_bar' => 'sometimes|boolean',
        ])->validate();
    }
}
