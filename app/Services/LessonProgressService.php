<?php

namespace App\Services;

use App\Events\CourseCompleted;
use App\Events\LessonCompleted;
use App\Events\VideoWatched;
use App\Models\CourseEnrollment;
use App\Models\CourseLesson;
use App\Models\LessonProgress;
use App\Models\Student;
use App\Services\Gamification\GamificationService;
use Illuminate\Support\Facades\DB;

class LessonProgressService
{
    public function __construct(
        protected EnrollmentService $enrollmentService,
        protected GamificationService $gamificationService
    ) {}

    public function updateProgress(
        Student $student,
        CourseLesson $lesson,
        int $lastPositionSeconds = 0,
        int $watchedSeconds = 0,
        bool $markCompleted = false
    ): LessonProgress {
        return DB::transaction(function () use ($student, $lesson, $lastPositionSeconds, $watchedSeconds, $markCompleted) {
            $enrollment = $this->enrollmentService->getEnrollmentForLesson($student, $lesson);

            if (! $enrollment || ! $enrollment->isActive()) {
                throw new \RuntimeException('الطالب غير مسجّل في هذا الكورس.');
            }

            $progress = LessonProgress::query()->firstOrNew([
                'enrollment_id' => $enrollment->id,
                'course_lesson_id' => $lesson->id,
            ]);

            $progress->student_id = $student->id;
            $progress->last_position_seconds = max($progress->last_position_seconds ?? 0, $lastPositionSeconds);
            $progress->watched_seconds = max($progress->watched_seconds ?? 0, $watchedSeconds);

            $wasCompleted = $progress->status === 'completed';

            if ($markCompleted) {
                $progress->status = 'completed';
                $progress->completed_at = $progress->completed_at ?? now();
            } elseif ($progress->status !== 'completed') {
                $progress->status = ($progress->watched_seconds > 0 || $progress->last_position_seconds > 0)
                    ? 'in_progress'
                    : 'not_started';
            }

            $progress->save();

            if ($student->user && $lesson->duration_seconds > 0 && $progress->status !== 'completed') {
                $watchPercent = min(100, (int) round(($progress->watched_seconds / $lesson->duration_seconds) * 100));
                $this->gamificationService->dispatchVideoWatchIfEligible(
                    $student->user,
                    $lesson,
                    $watchPercent
                );
            }

            $this->enrollmentService->recalculateProgress($enrollment->fresh());
            $enrollment = $enrollment->fresh(['course']);

            if ($student->user && ! $wasCompleted && $progress->status === 'completed') {
                LessonCompleted::dispatch($student->user, $lesson);
                VideoWatched::dispatch($student->user, $lesson, 100);
            }

            if ($student->user && $enrollment && (int) $enrollment->progress_percent >= 100) {
                CourseCompleted::dispatch($student->user, $enrollment->course);
            }

            return $progress->fresh();
        });
    }
}
