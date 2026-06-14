# Exam Module — Integration Hooks

Events and extension points for connecting exam to other systems (especially gamification).

## Events to dispatch

### QuizCompleted

```php
use App\Events\QuizCompleted;

event(new QuizCompleted($attempt, $quiz, $user));
```

Dispatch from `QuizAttemptController@submit` (or API equivalent) after grading.

**Gamification listener:** `App\Listeners\Gamification\QuizCompletedListener` awards points per `config/gamification.php` (`quiz_completion`, `perfect_score`).

### QuizStarted

```php
use App\Events\QuizStarted;

event(new QuizStarted($attempt, $quiz, $user));
```

Used for student action notifications (optional).

## Course module integration

`CourseModule.module_type` values used by this module:

- `quiz` — links to `Quiz` model
- `question_module` — links to `QuestionModule` model

Student learning views load quizzes via:

- `resources/views/student/pages/learn/partials/quiz.blade.php`

Wire your course player to these routes:

- `student.quizzes.show`, `student.quizzes.start`
- `student.question-module.start`

## Course section questions

Admin manages section questions via `CourseSectionController` (not included — hook point only). Model `CourseSectionQuestion` is included.

## Programming languages

Code questions use pivot table `question_programming_language`. Ensure `ProgrammingLanguage` model exists with `active()` scope.

## AI features

Requires `laravel/ai` and configured AI models. Routes under `admin/ai/question-*`.

## Notification hub (optional)

If using notification hub, register event keys:

- `student.quiz.started`
- `student.quiz.completed`
- `student.quiz.previewed`
