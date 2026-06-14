<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuestionModule;
use App\Models\User;

class ExamAccessService
{
    public function __construct(
        protected CourseAccessService $courseAccess
    ) {}

    public function userCanAccessCourse(User $user, Course $course): bool
    {
        if (! $course->isPublished()) {
            return false;
        }

        return $this->courseAccess->canAccessCourse($user, $course);
    }

    public function userCanTakeQuiz(User $user, Quiz $quiz): bool
    {
        if (! $quiz->is_published || ! $quiz->is_visible) {
            return false;
        }

        $course = $quiz->course;
        if (! $course) {
            return false;
        }

        return $this->userCanAccessCourse($user, $course);
    }

    public function userCanTakeQuestionModule(User $user, QuestionModule $module): bool
    {
        if (! $module->is_published || ! $module->is_visible) {
            return false;
        }

        $courseModule = $module->module;
        if (! $courseModule?->course) {
            return true;
        }

        return $this->userCanAccessCourse($user, $courseModule->course);
    }
}
