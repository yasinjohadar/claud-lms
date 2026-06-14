# Gamification Module Export

Portable copy of the ClaudSoft LMS motivation stack:

- Points & XP ledger
- Levels and streaks
- Badges and achievements
- Daily/weekly/monthly challenges
- Leaderboards with divisions
- Points shop, inventory, boosters
- Competitions and social activity feed
- Referral points
- Admin analytics dashboard

## Contents

```
gamification/
├── app/                  Models, services, events, listeners, provider
├── config/gamification.php
├── database/migrations/  Legacy + gamification_* tables
├── database/seeders/     Badges, achievements, levels, shop, etc.
├── resources/views/      Admin + student UI + email templates
├── routes/               admin, student, api, console schedule
├── tests/                Feature + leaderboard tests
├── snippets/             User model relationships, provider registration
├── MANIFEST.json
├── INSTALL.md
├── DEPENDENCIES.md
└── INTEGRATION-HOOKS.md  Domain events this module listens to
```

## Known gaps in source (address in target project)

- `XPService` — stub included in this export; implement or wire to `PointsService`
- `Competition` / `CompetitionParticipant` models — referenced but missing in source; add before using competitions
