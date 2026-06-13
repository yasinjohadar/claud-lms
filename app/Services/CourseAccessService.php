<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseResource;
use App\Models\User;

class CourseAccessService
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {}

    public function canAccessCourse(?User $user, Course $course): bool
    {
        if ($this->isStaff($user)) {
            return true;
        }

        $student = $user?->student;
        if (! $student) {
            return false;
        }

        return $course->enrollments()
            ->where('student_id', $student->id)
            ->where('status', 'active')
            ->exists();
    }

    public function canAccessLesson(?User $user, CourseLesson $lesson): bool
    {
        if ($this->isStaff($user)) {
            return true;
        }

        $student = $user?->student;
        if (! $student) {
            return false;
        }

        $enrollment = $this->enrollmentService->getEnrollmentForLesson($student, $lesson);

        return $enrollment !== null && $enrollment->isActive();
    }

    public function canDownloadResource(?User $user, CourseResource $resource): bool
    {
        if ($this->isStaff($user)) {
            return true;
        }

        if (! $resource->is_published) {
            return false;
        }

        $student = $user?->student;
        if (! $student) {
            return false;
        }

        $course = Course::find($resource->course_id);
        if (! $course) {
            return false;
        }

        return $this->canAccessCourse($user, $course);
    }

    private function isStaff(?User $user): bool
    {
        return $user && ($user->hasRole('admin') || $user->hasRole('instructor'));
    }
}
