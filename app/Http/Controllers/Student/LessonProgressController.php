<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\CourseLesson;
use App\Services\LessonProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonProgressController extends Controller
{
    public function __construct(
        protected LessonProgressService $progressService
    ) {}

    public function store(Request $request, CourseLesson $lesson): JsonResponse
    {
        $validated = $request->validate([
            'last_position_seconds' => 'nullable|integer|min:0',
            'watched_seconds' => 'nullable|integer|min:0',
            'mark_completed' => 'nullable|boolean',
        ]);

        $student = $request->user()->student;

        try {
            $progress = $this->progressService->updateProgress(
                $student,
                $lesson,
                (int) ($validated['last_position_seconds'] ?? 0),
                (int) ($validated['watched_seconds'] ?? 0),
                (bool) ($validated['mark_completed'] ?? false)
            );

            $enrollment = $student->enrollments()
                ->where('course_id', $lesson->course_id)
                ->first();

            return response()->json([
                'success' => true,
                'progress' => [
                    'status' => $progress->status,
                    'watched_seconds' => $progress->watched_seconds,
                    'last_position_seconds' => $progress->last_position_seconds,
                    'completed_at' => $progress->completed_at?->toIso8601String(),
                ],
                'course_progress_percent' => $enrollment?->fresh()->progress_percent ?? 0,
            ]);
        } catch (\RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 403);
        }
    }
}
