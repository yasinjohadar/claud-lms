# Exam Module — Installation Guide

## 1. Copy files

Merge the `exam/` folder into your Laravel project root, preserving paths:

```
exam/app/          → app/
exam/config/       → config/
exam/database/     → database/
exam/resources/    → resources/
exam/tests/        → tests/
```

## 2. Composer dependencies

```bash
composer require phpoffice/phpspreadsheet
composer require laravel/ai   # optional — AI question features
```

## 3. Register routes

In your **admin** route group (with auth + admin middleware):

```php
require base_path('routes/exam-admin.php'); // copy from export/exam/routes/admin.php
```

Merge AI question routes inside your existing `Route::prefix('ai')` group:

```php
require base_path('routes/exam-admin-ai-questions.php');
```

In your **student** route group:

```php
require base_path('routes/exam-student.php');
```

In your **student API** group:

```php
require base_path('routes/exam-api.php');
```

Route files are in `export/exam/routes/` — rename when copying to avoid conflicts.

## 4. Run migrations

```bash
php artisan migrate
```

Run seeders as needed:

```bash
php artisan db:seed --class=QuestionTypeSeeder
php artisan db:seed --class=HtmlCssQuestionBankSeeder
```

## 5. External models required

Your target project must provide (or stub):

| Model | Used for |
|-------|----------|
| `App\Models\Course` | Quiz & question bank scoping |
| `App\Models\Lesson` | Optional quiz lesson link |
| `App\Models\CourseModule` | `module_type` enum includes `quiz`, `question_module` |
| `App\Models\CourseSection` | Section-level questions |
| `App\Models\User` | Creator, student attempts |
| `App\Models\ProgrammingLanguage` | Code question types pivot |

See `DEPENDENCIES.md` and `snippets/user-relationships.php`.

## 6. Sidebar / navigation

Add admin links from `snippets/sidebar-admin.blade.php` to your admin sidebar.

## 7. Config

Copy `config/ai.php` or merge the `questions_engine` section into your existing config.

## 8. Middleware

Register if using question module debug:

```php
// bootstrap/app.php or Kernel
'debug.question-module' => \App\Http\Middleware\DebugQuestionModuleRoute::class,
```

## 9. Verify

```bash
php artisan route:list --name=quizzes
php artisan route:list --name=question-bank
php artisan test --filter=QuestionBank
```

## 10. Gamification integration (optional)

If you also install the gamification module, dispatch `App\Events\QuizCompleted` when a student finishes a quiz. See `INTEGRATION-HOOKS.md`.
