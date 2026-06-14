<?php

/**
 * Gamification module â€” admin routes
 *
 * Merge inside your admin/student/api route group in the target project.
 * See INSTALL.md for integration steps.
 */

use App\Http\Controllers\Admin\Gamification\DashboardController as GamificationDashboardController;
use App\Http\Controllers\Admin\Gamification\PointsController as AdminPointsController;
use App\Http\Controllers\Admin\Gamification\LevelController as AdminLevelController;
use App\Http\Controllers\Admin\Gamification\BadgeController as AdminBadgeController;
use App\Http\Controllers\Admin\Gamification\AchievementController as AdminAchievementController;
use App\Http\Controllers\Admin\Gamification\LeaderboardController as AdminLeaderboardController;
use App\Http\Controllers\Admin\Gamification\ChallengeController as AdminChallengeController;
use App\Http\Controllers\Admin\Gamification\ShopCategoryController as AdminShopCategoryController;
use App\Http\Controllers\Admin\Gamification\ShopItemController as AdminShopItemController;
use App\Http\Controllers\Admin\Gamification\PurchaseController as AdminPurchaseController;
use App\Http\Controllers\Admin\Gamification\SocialActivityController as AdminSocialActivityController;
use App\Http\Controllers\Admin\Gamification\CompetitionController as AdminCompetitionController;
use App\Http\Controllers\Admin\Gamification\AnalyticsController as AdminAnalyticsController;

        // ========== Gamification Routes ==========

        Route::prefix('gamification')->name('admin.gamification.')->group(function () {
            // Dashboard
            Route::get('/', [GamificationDashboardController::class, 'index'])->name('dashboard');
            Route::get('/analytics', [GamificationDashboardController::class, 'analytics'])->name('analytics');
            Route::post('/recalculate-all', [GamificationDashboardController::class, 'recalculateAll'])->name('recalculate-all');

            // Points Management
            Route::prefix('points')->name('points.')->group(function () {
                Route::get('/', [AdminPointsController::class, 'index'])->name('index');
                Route::get('/create', [AdminPointsController::class, 'create'])->name('create');
                Route::get('/search-students', [AdminPointsController::class, 'searchStudents'])->name('search-students');
                Route::post('/preview-recipients', [AdminPointsController::class, 'previewRecipients'])->name('preview-recipients');
                Route::post('/', [AdminPointsController::class, 'store'])->name('store');
                Route::get('/user/{user}', [AdminPointsController::class, 'userTransactions'])->name('user-transactions');
                Route::get('/report', [AdminPointsController::class, 'report'])->name('report');
                Route::post('/recalculate/{user}', [AdminPointsController::class, 'recalculate'])->name('recalculate');
                Route::delete('/{transaction}', [AdminPointsController::class, 'destroy'])->name('destroy');
            });

            // Levels Management
            Route::prefix('levels')->name('levels.')->group(function () {
                Route::get('/', [AdminLevelController::class, 'index'])->name('index');
                Route::get('/create', [AdminLevelController::class, 'create'])->name('create');
                Route::post('/', [AdminLevelController::class, 'store'])->name('store');
                Route::get('/{level}/edit', [AdminLevelController::class, 'edit'])->name('edit');
                Route::put('/{level}', [AdminLevelController::class, 'update'])->name('update');
                Route::delete('/{level}', [AdminLevelController::class, 'destroy'])->name('destroy');
                Route::get('/statistics', [AdminLevelController::class, 'statistics'])->name('statistics');
                Route::post('/generate', [AdminLevelController::class, 'generate'])->name('generate');
            });

            // Badges Management
            Route::prefix('badges')->name('badges.')->group(function () {
                Route::get('/', [AdminBadgeController::class, 'index'])->name('index');
                Route::get('/create', [AdminBadgeController::class, 'create'])->name('create');
                Route::post('/', [AdminBadgeController::class, 'store'])->name('store');
                Route::get('/award-manual', [AdminBadgeController::class, 'awardForm'])->name('award.form');
                Route::get('/award-manual/preview', [AdminBadgeController::class, 'previewTargets'])->name('award.preview');
                Route::get('/award-manual/students', [AdminBadgeController::class, 'searchStudents'])->name('award.students');
                Route::post('/award-manual', [AdminBadgeController::class, 'awardManual'])->name('award.store');
                Route::post('/award', [AdminBadgeController::class, 'awardToUser'])->name('award');
                Route::get('/statistics/overview', [AdminBadgeController::class, 'statistics'])->name('statistics');
                Route::get('/{badge}/award', [AdminBadgeController::class, 'awardFormForBadge'])->name('award.badge');
                Route::get('/{badge}', [AdminBadgeController::class, 'show'])->name('show');
                Route::get('/{badge}/edit', [AdminBadgeController::class, 'edit'])->name('edit');
                Route::put('/{badge}', [AdminBadgeController::class, 'update'])->name('update');
                Route::delete('/{badge}', [AdminBadgeController::class, 'destroy'])->name('destroy');
                Route::post('/{badge}/toggle-active', [AdminBadgeController::class, 'toggleActive'])->name('toggle-active');
            });

            // Achievements Management
            Route::prefix('achievements')->name('achievements.')->group(function () {
                Route::get('/', [AdminAchievementController::class, 'index'])->name('index');
                Route::get('/create', [AdminAchievementController::class, 'create'])->name('create');
                Route::post('/', [AdminAchievementController::class, 'store'])->name('store');
                Route::post('/recalculate-all', [AdminAchievementController::class, 'recalculateAll'])->name('recalculate-all');
                Route::get('/statistics/overview', [AdminAchievementController::class, 'statistics'])->name('statistics');
                Route::get('/{achievement}', [AdminAchievementController::class, 'show'])->name('show');
                Route::get('/{achievement}/edit', [AdminAchievementController::class, 'edit'])->name('edit');
                Route::put('/{achievement}', [AdminAchievementController::class, 'update'])->name('update');
                Route::delete('/{achievement}', [AdminAchievementController::class, 'destroy'])->name('destroy');
                Route::post('/{achievement}/toggle-active', [AdminAchievementController::class, 'toggleActive'])->name('toggle-active');
            });

            // Leaderboards Management
            Route::prefix('leaderboards')->name('leaderboards.')->group(function () {
                Route::get('/', [AdminLeaderboardController::class, 'index'])->name('index');
                Route::get('/create', [AdminLeaderboardController::class, 'create'])->name('create');
                Route::post('/', [AdminLeaderboardController::class, 'store'])->name('store');
                Route::get('/{leaderboard}', [AdminLeaderboardController::class, 'show'])->name('show');
                Route::get('/{leaderboard}/edit', [AdminLeaderboardController::class, 'edit'])->name('edit');
                Route::put('/{leaderboard}', [AdminLeaderboardController::class, 'update'])->name('update');
                Route::delete('/{leaderboard}', [AdminLeaderboardController::class, 'destroy'])->name('destroy');
                Route::post('/{leaderboard}/update', [AdminLeaderboardController::class, 'updateLeaderboard'])->name('update-data');
                Route::post('/update-all', [AdminLeaderboardController::class, 'updateAll'])->name('update-all');
                Route::post('/{leaderboard}/award-rewards', [AdminLeaderboardController::class, 'awardRewards'])->name('award-rewards');
                Route::post('/{leaderboard}/toggle-active', [AdminLeaderboardController::class, 'toggleActive'])->name('toggle-active');
            });

            // Challenges Management
            Route::prefix('challenges')->name('challenges.')->group(function () {
                Route::get('/', [AdminChallengeController::class, 'index'])->name('index');
                Route::get('/create', [AdminChallengeController::class, 'create'])->name('create');
                Route::post('/', [AdminChallengeController::class, 'store'])->name('store');
                Route::get('/statistics/overview', [AdminChallengeController::class, 'statistics'])->name('statistics');
                Route::post('/assign-to-user', [AdminChallengeController::class, 'assignToUser'])->name('assign-to-user');
                Route::post('/assign-to-multiple', [AdminChallengeController::class, 'assignToMultipleUsers'])->name('assign-to-multiple');
                Route::get('/{challenge}', [AdminChallengeController::class, 'show'])->name('show');
                Route::get('/{challenge}/edit', [AdminChallengeController::class, 'edit'])->name('edit');
                Route::put('/{challenge}', [AdminChallengeController::class, 'update'])->name('update');
                Route::delete('/{challenge}', [AdminChallengeController::class, 'destroy'])->name('destroy');
                Route::post('/{challenge}/toggle-active', [AdminChallengeController::class, 'toggleActive'])->name('toggle-active');
                Route::get('/{challenge}/participants', [AdminChallengeController::class, 'participants'])->name('participants');
                Route::post('/user-challenges/{userChallenge}/update-progress', [AdminChallengeController::class, 'updateUserProgress'])->name('update-user-progress');
                Route::post('/user-challenges/{userChallenge}/cancel', [AdminChallengeController::class, 'cancelUserChallenge'])->name('cancel-user-challenge');
            });

            // Shop Categories Management
            Route::prefix('shop/categories')->name('shop.categories.')->group(function () {
                Route::get('/', [AdminShopCategoryController::class, 'index'])->name('index');
                Route::post('/', [AdminShopCategoryController::class, 'store'])->name('store');
                Route::get('/{shopCategory}', [AdminShopCategoryController::class, 'show'])->name('show');
                Route::put('/{shopCategory}', [AdminShopCategoryController::class, 'update'])->name('update');
                Route::delete('/{shopCategory}', [AdminShopCategoryController::class, 'destroy'])->name('destroy');
                Route::post('/{shopCategory}/toggle-active', [AdminShopCategoryController::class, 'toggleActive'])->name('toggle-active');
            });

            // Shop Items Management
            Route::prefix('shop/items')->name('shop.items.')->group(function () {
                Route::get('/', [AdminShopItemController::class, 'index'])->name('index');
                Route::get('/create', [AdminShopItemController::class, 'create'])->name('create');
                Route::post('/', [AdminShopItemController::class, 'store'])->name('store');
                Route::get('/statistics/overview', [AdminShopItemController::class, 'statistics'])->name('statistics');
                Route::get('/top-selling', [AdminShopItemController::class, 'topSelling'])->name('top-selling');
                Route::get('/featured', [AdminShopItemController::class, 'featured'])->name('featured');
                Route::get('/{shopItem}', [AdminShopItemController::class, 'show'])->name('show');
                Route::get('/{shopItem}/edit', [AdminShopItemController::class, 'edit'])->name('edit');
                Route::put('/{shopItem}', [AdminShopItemController::class, 'update'])->name('update');
                Route::delete('/{shopItem}', [AdminShopItemController::class, 'destroy'])->name('destroy');
                Route::post('/{shopItem}/toggle-active', [AdminShopItemController::class, 'toggleActive'])->name('toggle-active');
                Route::post('/{shopItem}/apply-discount', [AdminShopItemController::class, 'applyDiscount'])->name('apply-discount');
                Route::post('/{shopItem}/remove-discount', [AdminShopItemController::class, 'removeDiscount'])->name('remove-discount');
                Route::post('/{shopItem}/update-stock', [AdminShopItemController::class, 'updateStock'])->name('update-stock');
            });

            // Purchases Management
            Route::prefix('shop/purchases')->name('shop.purchases.')->group(function () {
                Route::get('/', [AdminPurchaseController::class, 'index'])->name('index');
                Route::get('/statistics', [AdminPurchaseController::class, 'statistics'])->name('statistics');
                Route::get('/report', [AdminPurchaseController::class, 'report'])->name('report');
                Route::get('/{purchase}', [AdminPurchaseController::class, 'show'])->name('show');
            });

            // Social Activities Management
            Route::prefix('social/activities')->name('social.activities.')->group(function () {
                Route::get('/', [AdminSocialActivityController::class, 'index'])->name('index');
                Route::get('/statistics', [AdminSocialActivityController::class, 'statistics'])->name('statistics');
                Route::get('/{socialActivity}', [AdminSocialActivityController::class, 'show'])->name('show');
                Route::delete('/{socialActivity}', [AdminSocialActivityController::class, 'destroy'])->name('destroy');
            });

            // Competitions Management
            Route::prefix('social/competitions')->name('social.competitions.')->group(function () {
                Route::get('/', [AdminCompetitionController::class, 'index'])->name('index');
                Route::get('/statistics', [AdminCompetitionController::class, 'statistics'])->name('statistics');
                Route::get('/{competition}', [AdminCompetitionController::class, 'show'])->name('show');
                Route::post('/{competition}/end', [AdminCompetitionController::class, 'end'])->name('end');
                Route::delete('/{competition}', [AdminCompetitionController::class, 'destroy'])->name('destroy');
            });

            // Analytics
            Route::prefix('analytics')->name('analytics.')->group(function () {
                Route::get('/dashboard', [AdminAnalyticsController::class, 'dashboard'])->name('dashboard');
                Route::get('/points', [AdminAnalyticsController::class, 'points'])->name('points');
                Route::get('/levels', [AdminAnalyticsController::class, 'levels'])->name('levels');
                Route::get('/badges', [AdminAnalyticsController::class, 'badges'])->name('badges');
                Route::get('/engagement', [AdminAnalyticsController::class, 'engagement'])->name('engagement');
                Route::get('/students/{user}/report', [AdminAnalyticsController::class, 'studentReport'])->name('student-report');
                Route::post('/clear-cache', [AdminAnalyticsController::class, 'clearCache'])->name('clear-cache');
            });
        });
