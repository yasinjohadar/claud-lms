# Gamification Module — Integration Hooks

## Auto-wired by GamificationServiceProvider

The included `GamificationServiceProvider` registers listeners for:

### Gamification internal events

| Event | Listeners |
|-------|-----------|
| `BadgeEarned` | SendNotification, CheckBadges, CheckAchievements, SocialActivity |
| `AchievementUnlocked` | SendNotification, CheckAchievements, SocialActivity |
| `LevelUp` | SendNotification, CheckBadges, CheckAchievements, SocialActivity |
| `PointsEarned` | SendNotification, CheckBadges, CheckAchievements, UpdateLeaderboard, UpdateChallenge, UpdateCompetition |
| `XPEarned` | Same as PointsEarned |
| `StreakUpdated` | CheckBadges, CheckAchievements, UpdateChallenge |
| `ChallengeCompleted` | SendNotification, SocialActivity |
| `LeaderboardRankChanged` | SendNotification |

### Domain events (must exist in target LMS)

| Event | Listener | Points source |
|-------|----------|---------------|
| `QuizCompleted` | `QuizCompletedListener` | `config/gamification.php` → `quiz_completion` |
| `LessonCompleted` | `LessonCompletedListener` | `lesson_completion` |
| `CourseCompleted` | `CourseCompletedListener` | `course_completion` |
| `VideoWatched` | `VideoWatchedListener` | `video_watch` |
| `AssignmentSubmitted` | `AssignmentSubmittedListener` | `assignment_submission` |
| `Login` | `UserLoginListener` | Daily streak tracking |
| `Registered` | `AwardReferralPointsListener` | Referral bonus |

## Minimal QuizCompleted event stub

If installing gamification without full exam module:

```php
namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class QuizCompleted
{
    use Dispatchable;

    public function __construct(
        public mixed $attempt,
        public mixed $quiz,
        public User $user,
    ) {}
}
```

Adjust constructor to match what `QuizCompletedListener` expects (read listener source).

## Point earning catalog

`App\Services\Gamification\PointEarningCatalog` maps activity types to config keys. Extend when adding new LMS activities.

## Badge / achievement criteria

Mappers in `app/Support/Gamification/`:

- `BadgeCriteriaMapper` — criteria like `quizzes_passed`, `quiz_completion`
- `AchievementCriteriaMapper` — tier progress

## Console commands for maintenance

```bash
php artisan gamification:recalc-badges
php artisan gamification:update-leaderboards
php artisan gamification:check-all-badges
```

## Exam + gamification together

1. Install both exports
2. Ensure `QuizCompleted` is dispatched on quiz submit
3. `GamificationServiceProvider` already maps `QuizCompleted` → `QuizCompletedListener`
4. Run seeders for badges/achievements that reference quiz criteria
