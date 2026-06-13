<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseSection;
use Illuminate\Support\Facades\DB;

class CourseCurriculumService
{
    public function syncCourseStats(Course $course): void
    {
        $stats = CourseLesson::query()
            ->whereHas('section', fn ($q) => $q->where('course_id', $course->id))
            ->selectRaw('COUNT(*) as lessons_count, COALESCE(SUM(duration_seconds), 0) as total_seconds')
            ->first();

        $totalSeconds = (int) ($stats->total_seconds ?? 0);

        $course->update([
            'lessons_count' => (int) ($stats->lessons_count ?? 0),
            'duration_hours' => $totalSeconds > 0 ? max(1, (int) ceil($totalSeconds / 3600)) : 0,
        ]);
    }

    public function reorderSections(Course $course, array $sectionIds): void
    {
        DB::transaction(function () use ($course, $sectionIds) {
            foreach ($sectionIds as $index => $sectionId) {
                CourseSection::query()
                    ->where('course_id', $course->id)
                    ->where('id', $sectionId)
                    ->update(['sort_order' => $index + 1]);
            }
        });
    }

    public function reorderLessons(CourseSection $section, array $lessonIds): void
    {
        DB::transaction(function () use ($section, $lessonIds) {
            foreach ($lessonIds as $index => $lessonId) {
                CourseLesson::query()
                    ->where('course_section_id', $section->id)
                    ->where('id', $lessonId)
                    ->update(['sort_order' => $index + 1]);
            }

            $this->syncCourseStats($section->course);
        });
    }

    public function parseDurationToSeconds(?string $duration): ?int
    {
        if (empty(trim($duration ?? ''))) {
            return null;
        }

        $duration = trim($duration);

        if (preg_match('/^(\d+):(\d{1,2})$/', $duration, $matches)) {
            return ((int) $matches[1] * 60) + (int) $matches[2];
        }

        if (ctype_digit($duration)) {
            return (int) $duration * 60;
        }

        return null;
    }
}
