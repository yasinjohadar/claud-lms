# Exam Module — External Dependencies

## Composer packages

| Package | Purpose |
|---------|---------|
| `phpoffice/phpspreadsheet` | Question bank Excel import/export |
| `laravel/ai` | AI question creation, generation, solving |
| `spatie/laravel-permission` | Role checks in controllers (if used) |

## Eloquent models (must exist in target project)

| Model | Relationship |
|-------|--------------|
| `Course` | `Quiz`, `QuestionBank`, `QuestionModule` belong to course |
| `Lesson` | Optional `Quiz.lesson_id` |
| `CourseModule` | Polymorphic module type `quiz`, `question_module` |
| `CourseSection` | Section questions via `CourseSectionQuestion` |
| `User` | `creator_id`, student attempts |
| `ProgrammingLanguage` | Many-to-many with `QuestionBank` |

## Events emitted (for gamification / notifications)

| Event | When |
|-------|------|
| `App\Events\QuizCompleted` | Student submits quiz |
| `App\Events\QuizStarted` | Student starts quiz |

## Config keys used

From `config/ai.php`:

- `questions_engine` — AI model for question generation

From `config/notification_hub.php` (optional):

- `student.quiz.started`, `student.quiz.completed`, `student.quiz.previewed`

## Database tables created by migrations

See `database/migrations/` in this export. Key tables:

- `quizzes`, `quiz_questions`, `quiz_attempts`, `quiz_responses`, `quiz_settings`, `quiz_analytics`
- `question_types`, `question_bank`, `question_options`
- `question_pools`, `question_pool_items`
- `question_modules`, `question_module_questions`, `question_module_attempts`, `question_module_responses`
- `course_section_questions`
- `ai_question_generations`, `ai_question_solutions`
- `question_programming_language` (pivot)

## Views expecting shared layouts

Blade views extend:

- `admin.layouts.master`
- `student.layouts.master`

Ensure your target project has compatible layout sections (`@section('content')`, `@section('script')`, `@stack('scripts')`).

## CSS classes

Views reference shared admin styles (`group-show-hero`, `dashboard-table`, `admin-stats-card`, etc.). Copy relevant CSS from source `public/assets/css/custom.css` or restyle views.
