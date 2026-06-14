
<?php

/**
 * Exam module â€” API routes (question-modules stats + quizzes)
 * Merge inside authenticated student API group.
 */

        Route::get('question-modules/stats', [StudentQuestionModuleStatsApiController::class, 'index'])->name('question-modules.stats');
        Route::get('question-modules/{questionModuleId}/stats', [StudentQuestionModuleStatsApiController::class, 'moduleStats'])->name('question-modules.module-stats');
        Route::prefix('quizzes')->name('quizzes.')->group(function () {
            Route::get('{id}/preview', [StudentQuizApiController::class, 'preview'])->name('preview');
            Route::post('{id}/start', [StudentQuizApiController::class, 'start'])->name('start');
            Route::get('attempts', [StudentQuizApiController::class, 'myAttempts'])->name('attempts.index');
            Route::get('attempts/{attempt}', [StudentQuizApiController::class, 'showAttempt'])->name('attempts.show');
            Route::post('attempts/{attempt}/answer', [StudentQuizApiController::class, 'saveAnswer'])->name('attempts.answer');
            Route::post('attempts/{attempt}/submit', [StudentQuizApiController::class, 'submit'])->name('attempts.submit');
        });
