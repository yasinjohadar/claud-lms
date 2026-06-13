<?php

namespace App\Observers;

use App\Models\CourseEnrollment;
use App\Services\EnrollmentService;

class CourseEnrollmentObserver
{
    public function __construct(
        protected EnrollmentService $enrollmentService
    ) {}

    public function saved(CourseEnrollment $enrollment): void
    {
        if ($enrollment->course) {
            $this->enrollmentService->syncCourseStudentsCount($enrollment->course);
        }
    }

    public function deleted(CourseEnrollment $enrollment): void
    {
        if ($enrollment->course) {
            $this->enrollmentService->syncCourseStudentsCount($enrollment->course);
        }
    }
}
