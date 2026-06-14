<?php

/**
 * Gamification module â€” student API routes
 *
 * Merge inside your admin/student/api route group in the target project.
 * See INSTALL.md for integration steps.
 */

use App\Http\Controllers\Api\Student\Gamification\AchievementApiController as StudentAchievementApiController;
use App\Http\Controllers\Api\Student\Gamification\BadgeApiController as StudentBadgeApiController;
use App\Http\Controllers\Api\Student\Gamification\ChallengeApiController as StudentChallengeApiController;
use App\Http\Controllers\Api\Student\Gamification\LeaderboardApiController as StudentLeaderboardApiController;
use App\Http\Controllers\Api\Student\Gamification\PointsApiController as StudentPointsApiController;
use App\Http\Controllers\Api\Student\Gamification\ShopApiController as StudentShopApiController;
use App\Http\Controllers\Api\Student\Gamification\StreakApiController as StudentStreakApiController;

        Route::prefix('gamification')->name('gamification.')->group(function () {
            Route::get('leaderboards', [StudentLeaderboardApiController::class, 'index'])->name('leaderboards.index');
            Route::get('leaderboards/{leaderboard}', [StudentLeaderboardApiController::class, 'show'])->name('leaderboards.show');

            Route::get('badges', [StudentBadgeApiController::class, 'index'])->name('badges.index');

            Route::get('achievements', [StudentAchievementApiController::class, 'index'])->name('achievements.index');
            Route::post('achievements/{userAchievement}/claim', [StudentAchievementApiController::class, 'claim'])->name('achievements.claim');

            Route::get('challenges', [StudentChallengeApiController::class, 'index'])->name('challenges.index');
            Route::post('challenges/{challenge}/accept', [StudentChallengeApiController::class, 'accept'])->name('challenges.accept');

            Route::get('shop', [StudentShopApiController::class, 'index'])->name('shop.index');
            Route::get('shop/items/{item}', [StudentShopApiController::class, 'show'])->name('shop.items.show');
            Route::post('shop/items/{item}/purchase', [StudentShopApiController::class, 'purchase'])->name('shop.items.purchase');

            Route::get('points', [StudentPointsApiController::class, 'index'])->name('points.index');
            Route::get('points/history', [StudentPointsApiController::class, 'history'])->name('points.history');

            Route::get('streak', [StudentStreakApiController::class, 'index'])->name('streak.index');
            Route::get('streak/calendar', [StudentStreakApiController::class, 'calendar'])->name('streak.calendar');
        });
