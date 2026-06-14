# Gamification Module — Installation Guide

## 1. Copy files

```
gamification/app/       → app/
gamification/config/    → config/
gamification/database/  → database/
gamification/resources/ → resources/
gamification/tests/     → tests/
```

## 2. Register service provider

In `bootstrap/providers.php` (Laravel 11+) or `config/app.php`:

```php
App\Providers\GamificationServiceProvider::class,
```

See `snippets/providers.php`.

## 3. Register routes

**Admin** (inside admin middleware group):

```php
require base_path('routes/gamification-admin.php');
```

**Student**:

```php
require base_path('routes/gamification-student.php');
```

**API** (student authenticated group):

```php
require base_path('routes/gamification-api.php');
```

Copy from `export/gamification/routes/` and adjust filenames.

## 4. Scheduled tasks

Merge `routes/console-schedule.php` into your `routes/console.php`.

## 5. Run migrations & seeders

```bash
php artisan migrate
php artisan db:seed --class=LevelSeeder
php artisan db:seed --class=BadgeSeeder
php artisan db:seed --class=AchievementSeeder
php artisan db:seed --class=ChallengeSeeder
php artisan db:seed --class=ShopCategorySeeder
php artisan db:seed --class=ShopItemSeeder
php artisan db:seed --class=LeaderboardSeeder
php artisan db:seed --class=NotificationTypeSeeder
```

## 6. User model

Add relationships from `snippets/user-relationships.php` to `App\Models\User`.

If using referrals, add columns from migration `2026_06_04_120000_add_referral_fields_to_users_table.php`.

## 7. Config

Copy `config/gamification.php` to your project.

## 8. XPService stub

This export includes `app/Services/Gamification/XPService.php` as a stub. Implement XP logic or delegate to `PointsService`.

## 9. Competitions (optional)

Create missing models before enabling competition routes:

- `App\Models\Competition`
- `App\Models\CompetitionParticipant`

Or disable competition routes until implemented.

## 10. Verify

```bash
php artisan route:list --name=gamification
php artisan gamification:daily-tasks
php artisan test --filter=Gamification
```

## 11. Learning event hooks

Gamification awards points when domain events fire. Wire these in your LMS:

| Event | Listener (included) |
|-------|---------------------|
| `QuizCompleted` | `QuizCompletedListener` |
| `LessonCompleted` | `LessonCompletedListener` |
| `CourseCompleted` | `CourseCompletedListener` |
| `VideoWatched` | `VideoWatchedListener` |
| `AssignmentSubmitted` | `AssignmentSubmittedListener` |
| `Illuminate\Auth\Events\Login` | `UserLoginListener` (streaks) |
| `Illuminate\Auth\Events\Registered` | `AwardReferralPointsListener` |

See `INTEGRATION-HOOKS.md`.
