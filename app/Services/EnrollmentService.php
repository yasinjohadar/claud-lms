<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use App\Models\Order;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function grant(
        Student $student,
        Course $course,
        string $source = 'admin_grant',
        ?User $grantedBy = null,
        ?Order $order = null,
        string $status = 'active'
    ): CourseEnrollment {
        return DB::transaction(function () use ($student, $course, $source, $grantedBy, $order, $status) {
            $enrollment = CourseEnrollment::query()
                ->where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->first();

            if ($enrollment) {
                $enrollment->update([
                    'status' => $status,
                    'source' => $source,
                    'enrolled_at' => $enrollment->enrolled_at ?? now(),
                    'order_id' => $order?->id ?? $enrollment->order_id,
                    'granted_by' => $grantedBy?->id ?? $enrollment->granted_by,
                ]);
            } else {
                $enrollment = CourseEnrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'status' => $status,
                    'source' => $source,
                    'enrolled_at' => now(),
                    'order_id' => $order?->id,
                    'granted_by' => $grantedBy?->id,
                ]);
            }

            $this->syncCourseStudentsCount($course);

            return $enrollment->fresh();
        });
    }

    public function activateFromOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order->load(['items.course', 'student']);

            foreach ($order->items as $item) {
                $this->grant(
                    $order->student,
                    $item->course,
                    'purchase',
                    null,
                    $order,
                    'active'
                );
            }
        });
    }

    public function cancel(CourseEnrollment $enrollment): void
    {
        DB::transaction(function () use ($enrollment) {
            $course = $enrollment->course;
            $enrollment->update(['status' => 'cancelled']);
            $this->syncCourseStudentsCount($course);
        });
    }

    public function recalculateProgress(CourseEnrollment $enrollment): void
    {
        $course = $enrollment->course()->with('sections.lessons')->first();
        $totalLessons = $course->sections->sum(fn ($s) => $s->lessons->count());

        if ($totalLessons === 0) {
            $enrollment->update(['progress_percent' => 0]);

            return;
        }

        $completed = LessonProgress::query()
            ->where('enrollment_id', $enrollment->id)
            ->where('status', 'completed')
            ->count();

        $percent = (int) round(($completed / $totalLessons) * 100);

        $enrollment->update([
            'progress_percent' => $percent,
            'completed_at' => $percent >= 100 ? ($enrollment->completed_at ?? now()) : null,
            'status' => $percent >= 100 ? 'completed' : ($enrollment->status === 'completed' ? 'active' : $enrollment->status),
        ]);
    }

    public function syncCourseStudentsCount(Course $course): void
    {
        $count = CourseEnrollment::query()
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->count();

        $course->update(['students_count' => $count]);
    }

    public function getEnrollmentForLesson(Student $student, CourseLesson $lesson): ?CourseEnrollment
    {
        $courseId = $lesson->course_id;

        if (! $courseId) {
            return null;
        }

        return CourseEnrollment::query()
            ->where('student_id', $student->id)
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->first();
    }
}
