
/*
|--------------------------------------------------------------------------
| Gamification Scheduled Tasks â€” copy into routes/console.php
|--------------------------------------------------------------------------
*/

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
