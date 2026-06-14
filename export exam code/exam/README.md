# Exam Module Export

Portable copy of the ClaudSoft LMS assessment stack:

- Quizzes (CRUD, attempts, grading, analytics)
- Question bank (all types, CRUD, bulk actions)
- Excel + type-specific import (Excel/JSON)
- Question pools
- Question modules (embedded practice tests)
- AI question creation, generation, and solving

## Contents

```
exam/
├── app/                  Models, controllers, services, events
├── config/               ai.php (full file)
├── database/migrations/  Quiz & question tables
├── database/seeders/   Question types, sample banks, quizzes
├── resources/views/    Admin + student Blade views
├── resources/prompts/  AI prompt templates
├── routes/             admin, student, api route snippets
├── tests/              Import feature + unit tests
├── snippets/           Integration helpers
├── MANIFEST.json       File inventory
├── INSTALL.md          Step-by-step setup
├── DEPENDENCIES.md     External models & packages
└── INTEGRATION-HOOKS.md Events fired by this module
```

## Composer packages (minimum)

- `phpoffice/phpspreadsheet` — Excel import
- `laravel/ai` — AI question tools (optional if AI features disabled)

See `DEPENDENCIES.md` for full list.
