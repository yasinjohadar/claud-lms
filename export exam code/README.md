# ClaudSoft LMS — Portable Module Exports

This folder contains **read-only copies** of two subsystems extracted from the main LMS for use in another Laravel project.

| Folder | Description |
|--------|-------------|
| [`exam/`](exam/) | Quizzes, question bank, import, pools, question modules, grading, analytics, AI question tools |
| [`gamification/`](gamification/) | Points, badges, achievements, challenges, streaks, leaderboards, shop, competitions, social |

## Quick start

1. Run the export script (regenerates copies from source):

```powershell
.\export\copy-modules.ps1
```

2. Copy the desired folder(s) into your target Laravel project (see each module's `INSTALL.md`).

3. Install composer dependencies listed in each module's `DEPENDENCIES.md`.

## Notes

- Namespaces remain `App\...` — no refactoring was applied.
- The source project is **not modified** by the export script.
- `MANIFEST.json` in each module lists all files with SHA256 hashes.
- When using **both** modules together, wire `QuizCompleted` → gamification listeners (see `INTEGRATION-HOOKS.md` in each folder).
