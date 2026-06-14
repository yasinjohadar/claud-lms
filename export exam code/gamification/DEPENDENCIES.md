# Gamification Module — External Dependencies

## Composer packages

No additional packages beyond standard Laravel. Uses:

- Mail for badge/achievement/level-up emails
- Cache for analytics (optional)

## Eloquent models

### Included in this export

All gamification models under `app/Models/Gamification/` and legacy root models (`Badge`, `Achievement`, etc.).

### Required from target project

| Model | Purpose |
|-------|---------|
| `User` | All gamification data is user-scoped |

Add relationships from `snippets/user-relationships.php`.

### Missing in source (implement in target)

| Class | Referenced by |
|-------|---------------|
| `App\Models\Competition` | `CompetitionService`, controllers, seeder |
| `App\Models\CompetitionParticipant` | `CompetitionService` |
| `App\Services\Gamification\XPService` | Stub provided in export |

## Config

Full `config/gamification.php` included. Key sections:

- `points` — earning rules per activity type
- `streak_multipliers`, `leaderboard`, `challenges`
- Notification toggles

## Database tables

Legacy tables + `gamification_*` prefix tables. See migrations folder.

User table may need referral columns (`referral_code`, `referred_by`) from migration included in export.

## Domain events listened to

Gamification listeners expect these events in the target project:

| Event | Namespace |
|-------|-----------|
| `LessonCompleted` | `App\Events` |
| `CourseCompleted` | `App\Events` |
| `VideoWatched` | `App\Events` |
| `AssignmentSubmitted` | `App\Events` |
| `QuizCompleted` | `App\Events` (from exam module) |
| `Registered` | `Illuminate\Auth\Events` |
| `Login` | `Illuminate\Auth\Events` |

Copy event classes from source LMS or create compatible stubs that carry `user_id` and context.

## Notification integration

`SendNotificationListener` may interact with:

- `App\Models\GamificationNotification`
- `App\Services\Notifications\NotificationHubService` (optional — stub or skip)

## Email templates

Requires working mail config. Templates in `resources/views/emails/`.

## Scheduled commands

| Command | Schedule |
|---------|----------|
| `gamification:daily-tasks` | Daily 00:00 |
| `gamification:update-stats` | Hourly |
| `gamification:weekly-summary` | Sunday 09:00 |

Plus: `gamification:update-leaderboards`, `gamification:recalc-badges`, etc. (run manually or add schedules).
