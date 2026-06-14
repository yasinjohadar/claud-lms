<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled Commands for Backup System
Schedule::command('backup:run-scheduled')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('backup:cleanup-expired')
    ->daily()
    ->at('02:00')
    ->withoutOverlapping();

Schedule::command('gamification:daily-tasks')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('gamification:update-stats')
    ->hourly()
    ->withoutOverlapping()
    ->runInBackground();

Schedule::command('gamification:weekly-summary')
    ->weeklyOn(0, '09:00')
    ->withoutOverlapping()
    ->runInBackground();
