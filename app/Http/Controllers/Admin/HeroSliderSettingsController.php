<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HeroSliderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HeroSliderSettingsController extends Controller
{
    public function __construct(
        protected HeroSliderService $sliderService
    ) {
        $this->middleware('auth');
        $this->middleware('permission:hero-slide-settings');
    }

    public function edit(): View
    {
        $settings = $this->sliderService->getSettings();

        return view('admin.pages.hero-slides.settings', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $this->sliderService->validateSettings($request->all());
        $this->sliderService->updateSettings([
            'autoplay_enabled' => $request->boolean('autoplay_enabled'),
            'autoplay_delay_ms' => $validated['autoplay_delay_ms'],
            'transition_speed_ms' => $validated['transition_speed_ms'],
            'loop' => $request->boolean('loop'),
            'pause_on_hover' => $request->boolean('pause_on_hover'),
            'effect' => $validated['effect'],
            'show_navigation' => $request->boolean('show_navigation'),
            'show_pagination' => $request->boolean('show_pagination'),
            'show_progress_bar' => $request->boolean('show_progress_bar'),
        ]);

        return back()->with('success', 'تم حفظ إعدادات السلايدر');
    }
}
