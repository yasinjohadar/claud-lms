<?php

/**
 * Gamification module — student routes
 */

use App\Http\Controllers\Student\Gamification\AchievementController as StudentAchievementController;
use App\Http\Controllers\Student\Gamification\BadgeController as StudentBadgeController;
use App\Http\Controllers\Student\Gamification\ChallengeController as StudentChallengeController;
use App\Http\Controllers\Student\Gamification\CompetitionController as StudentCompetitionController;
use App\Http\Controllers\Student\Gamification\DashboardController as GamificationDashboardController;
use App\Http\Controllers\Student\Gamification\FriendshipController as StudentFriendshipController;
use App\Http\Controllers\Student\Gamification\InventoryController as StudentInventoryController;
use App\Http\Controllers\Student\Gamification\LeaderboardController as StudentLeaderboardController;
use App\Http\Controllers\Student\Gamification\LevelController as StudentLevelController;
use App\Http\Controllers\Student\Gamification\NotificationController as StudentNotificationController;
use App\Http\Controllers\Student\Gamification\PointsController as StudentPointsController;
use App\Http\Controllers\Student\Gamification\ShopController as StudentShopController;
use App\Http\Controllers\Student\Gamification\SocialActivityController as StudentSocialActivityController;
use App\Http\Controllers\Student\Gamification\StreakController as StudentStreakController;
use Illuminate\Support\Facades\Route;

Route::prefix('gamification')->name('gamification.')->group(function () {
    Route::get('/', [GamificationDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [GamificationDashboardController::class, 'profile'])->name('profile');
    Route::get('/statistics', [GamificationDashboardController::class, 'statistics'])->name('statistics');

    Route::prefix('points')->name('points.')->group(function () {
        Route::get('/', [StudentPointsController::class, 'index'])->name('index');
        Route::get('/history', [StudentPointsController::class, 'history'])->name('history');
        Route::get('/how-to-earn', [StudentPointsController::class, 'howToEarn'])->name('how-to-earn');
    });

    Route::prefix('levels')->name('levels.')->group(function () {
        Route::get('/', [StudentLevelController::class, 'index'])->name('index');
        Route::get('/all', [StudentLevelController::class, 'all'])->name('all');
        Route::get('/{level}', [StudentLevelController::class, 'show'])->name('show');
    });

    Route::prefix('streak')->name('streak.')->group(function () {
        Route::get('/', [StudentStreakController::class, 'index'])->name('index');
        Route::get('/calendar', [StudentStreakController::class, 'calendar'])->name('calendar');
        Route::get('/history', [StudentStreakController::class, 'history'])->name('history');
    });

    Route::prefix('badges')->name('badges.')->group(function () {
        Route::get('/', [StudentBadgeController::class, 'index'])->name('index');
        Route::get('/collection', [StudentBadgeController::class, 'collection'])->name('collection');
        Route::get('/recommended', [StudentBadgeController::class, 'recommended'])->name('recommended');
        Route::get('/{badge}', [StudentBadgeController::class, 'show'])->name('show');
    });

    Route::prefix('achievements')->name('achievements.')->group(function () {
        Route::get('/', [StudentAchievementController::class, 'index'])->name('index');
        Route::get('/recommended', [StudentAchievementController::class, 'recommended'])->name('recommended');
        Route::get('/{achievement}', [StudentAchievementController::class, 'show'])->name('show');
        Route::post('/{userAchievement}/claim', [StudentAchievementController::class, 'claim'])->name('claim');
    });

    Route::prefix('leaderboards')->name('leaderboards.')->group(function () {
        Route::get('/', [StudentLeaderboardController::class, 'index'])->name('index');
        Route::get('/my-rank', [StudentLeaderboardController::class, 'myRank'])->name('my-rank');
        Route::get('/{leaderboard}', [StudentLeaderboardController::class, 'show'])->name('show');
        Route::get('/{leaderboard}/division/{division}', [StudentLeaderboardController::class, 'division'])->name('division');
    });

    Route::prefix('challenges')->name('challenges.')->group(function () {
        Route::get('/', [StudentChallengeController::class, 'index'])->name('index');
        Route::get('/active', [StudentChallengeController::class, 'active'])->name('active');
        Route::get('/daily', [StudentChallengeController::class, 'daily'])->name('daily');
        Route::get('/weekly', [StudentChallengeController::class, 'weekly'])->name('weekly');
        Route::get('/monthly', [StudentChallengeController::class, 'monthly'])->name('monthly');
        Route::get('/special', [StudentChallengeController::class, 'special'])->name('special');
        Route::get('/recommended', [StudentChallengeController::class, 'recommended'])->name('recommended');
        Route::get('/my-stats', [StudentChallengeController::class, 'myStats'])->name('my-stats');
        Route::get('/history', [StudentChallengeController::class, 'history'])->name('history');
        Route::get('/{challenge}', [StudentChallengeController::class, 'show'])->name('show');
        Route::post('/{challenge}/accept', [StudentChallengeController::class, 'accept'])->name('accept');
        Route::post('/user-challenges/{userChallenge}/cancel', [StudentChallengeController::class, 'cancel'])->name('cancel');
        Route::get('/user-challenges/{userChallenge}/progress', [StudentChallengeController::class, 'progress'])->name('progress');
    });

    Route::prefix('shop')->name('shop.')->group(function () {
        Route::get('/', [StudentShopController::class, 'index'])->name('index');
        Route::get('/categories', [StudentShopController::class, 'categories'])->name('categories');
        Route::get('/categories/{shopCategory}', [StudentShopController::class, 'categoryItems'])->name('category-items');
        Route::get('/featured', [StudentShopController::class, 'featured'])->name('featured');
        Route::get('/limited-offers', [StudentShopController::class, 'timeLimitedOffers'])->name('limited-offers');
        Route::get('/search', [StudentShopController::class, 'search'])->name('search');
        Route::get('/my-stats', [StudentShopController::class, 'myStats'])->name('my-stats');
        Route::get('/purchase-history', [StudentShopController::class, 'purchaseHistory'])->name('purchase-history');
        Route::get('/items/{shopItem}', [StudentShopController::class, 'show'])->name('show');
        Route::post('/items/{shopItem}/purchase', [StudentShopController::class, 'purchase'])->name('purchase');
    });

    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [StudentInventoryController::class, 'index'])->name('index');
        Route::get('/active', [StudentInventoryController::class, 'active'])->name('active');
        Route::get('/cosmetics', [StudentInventoryController::class, 'cosmetics'])->name('cosmetics');
        Route::get('/boosters', [StudentInventoryController::class, 'boosters'])->name('boosters');
        Route::get('/consumables', [StudentInventoryController::class, 'consumables'])->name('consumables');
        Route::get('/stats', [StudentInventoryController::class, 'stats'])->name('stats');
        Route::get('/{inventory}', [StudentInventoryController::class, 'show'])->name('show');
        Route::post('/{inventory}/activate', [StudentInventoryController::class, 'activate'])->name('activate');
        Route::post('/{inventory}/deactivate', [StudentInventoryController::class, 'deactivate'])->name('deactivate');
        Route::post('/{inventory}/consume', [StudentInventoryController::class, 'consume'])->name('consume');
    });

    Route::prefix('friends')->name('friends.')->group(function () {
        Route::get('/', [StudentFriendshipController::class, 'index'])->name('index');
        Route::post('/send-request', [StudentFriendshipController::class, 'sendRequest'])->name('send-request');
        Route::post('/{friendship}/accept', [StudentFriendshipController::class, 'acceptRequest'])->name('accept');
        Route::post('/{friendship}/reject', [StudentFriendshipController::class, 'rejectRequest'])->name('reject');
        Route::post('/{friendship}/cancel', [StudentFriendshipController::class, 'cancelRequest'])->name('cancel');
        Route::post('/unfriend', [StudentFriendshipController::class, 'unfriend'])->name('unfriend');
        Route::get('/pending-requests', [StudentFriendshipController::class, 'pendingRequests'])->name('pending-requests');
        Route::get('/sent-requests', [StudentFriendshipController::class, 'sentRequests'])->name('sent-requests');
        Route::get('/suggestions', [StudentFriendshipController::class, 'suggestions'])->name('suggestions');
        Route::get('/search', [StudentFriendshipController::class, 'search'])->name('search');
        Route::get('/status', [StudentFriendshipController::class, 'status'])->name('status');
    });

    Route::prefix('competitions')->name('competitions.')->group(function () {
        Route::get('/', [StudentCompetitionController::class, 'index'])->name('index');
        Route::get('/active', [StudentCompetitionController::class, 'active'])->name('active');
        Route::get('/completed', [StudentCompetitionController::class, 'completed'])->name('completed');
        Route::post('/create', [StudentCompetitionController::class, 'create'])->name('create');
        Route::get('/my-stats', [StudentCompetitionController::class, 'myStats'])->name('my-stats');
        Route::get('/{competition}', [StudentCompetitionController::class, 'show'])->name('show');
        Route::post('/{competition}/leave', [StudentCompetitionController::class, 'leave'])->name('leave');
        Route::delete('/{competition}', [StudentCompetitionController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('social')->name('social.')->group(function () {
        Route::get('/', [StudentSocialActivityController::class, 'index'])->name('index');
        Route::get('/feed', [StudentSocialActivityController::class, 'feed'])->name('feed');
        Route::get('/my-activities', [StudentSocialActivityController::class, 'myActivities'])->name('my-activities');
        Route::get('/users/{targetUser}/activities', [StudentSocialActivityController::class, 'userActivities'])->name('user-activities');
        Route::post('/activities/{activity}/like', [StudentSocialActivityController::class, 'like'])->name('like');
        Route::post('/activities/{activity}/unlike', [StudentSocialActivityController::class, 'unlike'])->name('unlike');
        Route::post('/activities/{activity}/comment', [StudentSocialActivityController::class, 'comment'])->name('comment');
        Route::delete('/comments/{commentId}', [StudentSocialActivityController::class, 'deleteComment'])->name('delete-comment');
        Route::post('/share/achievement', [StudentSocialActivityController::class, 'shareAchievement'])->name('share-achievement');
        Route::post('/share/badge', [StudentSocialActivityController::class, 'shareBadge'])->name('share-badge');
        Route::delete('/activities/{activity}', [StudentSocialActivityController::class, 'destroy'])->name('delete-activity');
    });

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [StudentNotificationController::class, 'index'])->name('index');
        Route::get('/api', [StudentNotificationController::class, 'api'])->name('api');
        Route::get('/api/unread-count', [StudentNotificationController::class, 'unreadCount'])->name('api.unread-count');
        Route::get('/unread-count', [StudentNotificationController::class, 'unreadCount'])->name('unread-count');
        Route::post('/{notification}/mark-as-read', [StudentNotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/{notification}/mark-read', [StudentNotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-as-read', [StudentNotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::post('/mark-all-read', [StudentNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [StudentNotificationController::class, 'destroy'])->name('destroy');
        Route::get('/my-report', [StudentNotificationController::class, 'myReport'])->name('my-report');
        Route::get('/preferences', [StudentNotificationController::class, 'getPreferences'])->name('get-preferences');
        Route::post('/preferences', [StudentNotificationController::class, 'updatePreferences'])->name('update-preferences');
    });
});
