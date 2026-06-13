<?php

use App\Models\HeroSlide;
use App\Models\HeroSliderSetting;
use App\Services\HeroSliderService;
use App\Support\HeroSlidePresenter;
use Database\Seeders\HeroSlideSeeder;

beforeEach(function () {
    $this->seed(HeroSlideSeeder::class);
});

it('seeds four published hero slides with distinct theme variants', function () {
    $slides = app(HeroSliderService::class)->getPublishedSlides();

    expect($slides)->toHaveCount(4);
    expect($slides->pluck('theme_variant')->all())->toBe(['main', 'code', 'design', 'ai']);
});

it('excludes inactive slides from published query', function () {
    HeroSlide::query()->update(['is_active' => false]);
    HeroSlide::first()->update(['is_active' => true]);

    expect(app(HeroSliderService::class)->getPublishedSlides())->toHaveCount(1);
});

it('respects slide scheduling', function () {
    HeroSlide::query()->update(['is_active' => true]);

    HeroSlide::first()->update([
        'starts_at' => now()->addDay(),
        'expires_at' => null,
    ]);

    expect(app(HeroSliderService::class)->getPublishedSlides())->toHaveCount(3);

    HeroSlide::first()->update([
        'starts_at' => now()->subDay(),
        'expires_at' => now()->subHour(),
    ]);

    expect(app(HeroSliderService::class)->getPublishedSlides())->toHaveCount(3);
});

it('returns default swiper settings from seeder', function () {
    $settings = app(HeroSliderService::class)->getSettings();

    expect($settings->autoplay_enabled)->toBeTrue();
    expect($settings->autoplay_delay_ms)->toBe(6000);
    expect($settings->transition_speed_ms)->toBe(900);
    expect($settings->effect)->toBe('fade');
    expect($settings->loop)->toBeTrue();
});

it('renders hero swiper on home page when slides are published', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('hero-swiper', false);
    $response->assertSee('data-delay="6000"', false);
    $response->assertSee('hero-slide-design', false);
});

it('hides hero section when no slides are published', function () {
    HeroSlide::query()->update(['is_active' => false]);

    $response = $this->get('/');

    $response->assertOk();
    $response->assertDontSee('hero-swiper', false);
});

it('generates background styles for solid and gradient slides', function () {
    $solid = new HeroSlide([
        'background_type' => 'solid',
        'background_color' => '#112233',
        'accent_color' => '#059669',
    ]);
    $gradient = new HeroSlide([
        'background_type' => 'gradient',
        'gradient_from' => '#111111',
        'gradient_to' => '#222222',
        'gradient_angle' => 90,
        'gradient_type' => 'linear',
        'accent_color' => '#059669',
    ]);

    expect(HeroSlidePresenter::make($solid)->backgroundStyle())->toContain('#112233');
    expect(HeroSlidePresenter::make($gradient)->backgroundStyle())->toContain('linear-gradient(90deg');
});

it('applies mobile visual hiding class', function () {
    $slide = HeroSlide::first();
    $slide->hide_visual_on_mobile = true;
    $slide->visual_type = 'main';

    expect(HeroSlidePresenter::make($slide)->visualColumnClass())->toContain('d-none d-lg-block');
});

it('persists swiper settings updates', function () {
    $settings = HeroSliderSetting::instance();

    app(HeroSliderService::class)->updateSettings([
        'autoplay_enabled' => false,
        'autoplay_delay_ms' => 8000,
        'transition_speed_ms' => 1200,
        'loop' => false,
        'pause_on_hover' => true,
        'effect' => 'slide',
        'show_navigation' => false,
        'show_pagination' => true,
        'show_progress_bar' => false,
    ]);

    $settings->refresh();

    expect($settings->autoplay_enabled)->toBeFalse();
    expect($settings->autoplay_delay_ms)->toBe(8000);
    expect($settings->effect)->toBe('slide');
});
