<?php

/**
 * Exam module â€” student routes (quizzes + question modules)
 *
 * Merge inside your admin/student/api route group in the target project.
 * See INSTALL.md for integration steps.
 */



        // ========== Quizzes Routes (Student) ==========

        // Review & Analytics (must be before quizzes routes to avoid route conflict)
        Route::prefix('quizzes/review')->name('student.quizzes.review.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Student\QuizReviewController::class, 'index'])->name('index'); // List all attempts
            Route::get('/{attemptId}', [\App\Http\Controllers\Student\QuizReviewController::class, 'show'])->name('show'); // Review specific attempt
            Route::get('/analytics/overview', [\App\Http\Controllers\Student\QuizReviewController::class, 'analytics'])->name('analytics'); // Performance analytics
            Route::get('/quiz/{quizId}/compare', [\App\Http\Controllers\Student\QuizReviewController::class, 'compareAttempts'])->name('compare'); // Compare attempts
            Route::get('/quiz/{quizId}/history', [\App\Http\Controllers\Student\QuizReviewController::class, 'history'])->name('history'); // Quiz history
            Route::get('/{attemptId}/question/{questionId}', [\App\Http\Controllers\Student\QuizReviewController::class, 'getQuestionReview'])->name('question'); // Question review (AJAX)
            Route::get('/{attemptId}/download-report', [\App\Http\Controllers\Student\QuizReviewController::class, 'downloadReport'])->name('download-report'); // Download report
        });

        // Browse & Take Quizzes
        Route::prefix('quizzes')->name('student.quizzes.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Student\QuizAttemptController::class, 'index'])->name('index'); // Browse available quizzes
            Route::get('/{id}', [\App\Http\Controllers\Student\QuizAttemptController::class, 'show'])->name('show'); // View quiz details before starting
            Route::post('/{id}/start', [\App\Http\Controllers\Student\QuizAttemptController::class, 'start'])->name('start'); // Start new attempt
            Route::get('/attempt/{attemptId}/take', [\App\Http\Controllers\Student\QuizAttemptController::class, 'take'])->name('take'); // Take quiz interface
            Route::post('/attempt/{attemptId}/save-answer', [\App\Http\Controllers\Student\QuizAttemptController::class, 'saveAnswer'])->name('save-answer'); // Save answer (AJAX)
            Route::post('/attempt/{attemptId}/mark-review/{questionId}', [\App\Http\Controllers\Student\QuizAttemptController::class, 'markForReview'])->name('mark-review'); // Mark question for review
            Route::post('/attempt/{attemptId}/submit', [\App\Http\Controllers\Student\QuizAttemptController::class, 'submit'])->name('submit'); // Submit quiz
            Route::post('/attempt/{attemptId}/mark-completed', [\App\Http\Controllers\Student\QuizAttemptController::class, 'markCompleted'])->name('mark-completed'); // Mark as completed "تم الإنجاز" ✅
            Route::get('/attempt/{attemptId}/progress', [\App\Http\Controllers\Student\QuizAttemptController::class, 'getProgress'])->name('progress'); // Get progress (AJAX)
        });

        // ========== Question Modules Routes (Student) ==========

        // Question Module Attempts
        Route::prefix('question-modules')->name('student.question-module.')->group(function () {
            Route::get('/{questionModule}/start', [QuestionModuleAttemptController::class, 'start'])->name('start'); // Start new attempt
            Route::get('/attempts/{attempt}/take', [QuestionModuleAttemptController::class, 'take'])->name('take'); // Take test interface
            Route::post('/attempts/{attempt}/save-answer', [QuestionModuleAttemptController::class, 'saveAnswer'])->name('save-answer'); // Save answer (AJAX)
            Route::post('/attempts/{attempt}/submit', [QuestionModuleAttemptController::class, 'submit'])->name('submit'); // Submit test
            Route::get('/attempts/{attempt}/result', [QuestionModuleAttemptController::class, 'result'])->name('result'); // View results
        });

        // Question Module Statistics
        Route::prefix('question-modules/stats')->name('student.question-module.stats.')->group(function () {
            Route::get('/', [QuestionModuleStatsController::class, 'index'])->name('index'); // Main statistics page
            Route::get('/dashboard', [QuestionModuleStatsController::class, 'getDashboardStats'])->name('dashboard'); // AJAX stats for dashboard
            Route::get('/{questionModule}/module', [QuestionModuleStatsController::class, 'showModuleStats'])->name('module'); // Specific module stats
        });
