<?php

/**
 * Exam module â€” admin routes (quizzes, question bank, pools, modules, grading, analytics)
 *
 * Merge inside your admin/student/api route group in the target project.
 * See INSTALL.md for integration steps.
 */



        // ========== Quizzes Routes ==========

        // Quizzes Management
        Route::resource('quizzes', \App\Http\Controllers\Admin\QuizController::class);
        Route::post('quizzes/{id}/toggle-publish', [\App\Http\Controllers\Admin\QuizController::class, 'togglePublish'])->name('quizzes.toggle-publish');
        Route::get('quizzes/course/{courseId}/lessons', [\App\Http\Controllers\Admin\QuizController::class, 'getLessons'])->name('quizzes.get-lessons');
        Route::post('quizzes/{id}/recalculate-score', [\App\Http\Controllers\Admin\QuizController::class, 'recalculateScore'])->name('quizzes.recalculate-score');

        // Quiz Questions Management
        Route::get('quizzes/{id}/manage-questions', [\App\Http\Controllers\Admin\QuizController::class, 'manageQuestions'])->name('quizzes.manage-questions');
        Route::get('quizzes/{id}/import-questions', [\App\Http\Controllers\Admin\QuizController::class, 'importQuestions'])->name('quizzes.import-questions');
        Route::post('quizzes/{id}/add-question', [\App\Http\Controllers\Admin\QuizController::class, 'addQuestion'])->name('quizzes.add-question');
        Route::delete('quizzes/{id}/remove-question/{questionId}', [\App\Http\Controllers\Admin\QuizController::class, 'removeQuestion'])->name('quizzes.remove-question');
        Route::post('quizzes/{id}/remove-multiple-questions', [\App\Http\Controllers\Admin\QuizController::class, 'removeMultipleQuestions'])->name('quizzes.remove-multiple-questions');
        Route::post('quizzes/{id}/reorder-questions', [\App\Http\Controllers\Admin\QuizController::class, 'reorderQuestions'])->name('quizzes.reorder-questions');

        // Question Bank Management
        Route::get('question-bank/create/{type}', [\App\Http\Controllers\Admin\QuestionBankController::class, 'createByType'])->name('question-bank.create.type');
        Route::resource('question-bank', \App\Http\Controllers\Admin\QuestionBankController::class);
        Route::post('question-bank/{id}/duplicate', [\App\Http\Controllers\Admin\QuestionBankController::class, 'duplicate'])->name('question-bank.duplicate');
        Route::get('question-bank/{id}/preview', [\App\Http\Controllers\Admin\QuestionBankController::class, 'preview'])->name('question-bank.preview');
        Route::get('question-bank/course/{courseId}/questions', [\App\Http\Controllers\Admin\QuestionBankController::class, 'getQuestionsByCourse'])->name('question-bank.by-course');
        Route::get('question-bank/type/{typeId}/questions', [\App\Http\Controllers\Admin\QuestionBankController::class, 'getQuestionsByType'])->name('question-bank.by-type');
        Route::post('question-bank/bulk-action', [\App\Http\Controllers\Admin\QuestionBankController::class, 'bulkAction'])->name('question-bank.bulk-action');
        Route::post('question-bank/delete-multiple', [\App\Http\Controllers\Admin\QuestionBankController::class, 'destroyMultiple'])->name('question-bank.delete-multiple');

        // Excel Import/Export
        Route::get('question-bank/import/excel', [\App\Http\Controllers\Admin\QuestionBankController::class, 'showImportForm'])->name('question-bank.import.excel');
        Route::post('question-bank/import/preview', [\App\Http\Controllers\Admin\QuestionBankController::class, 'previewImport'])->name('question-bank.import.preview');
        Route::post('question-bank/import/process', [\App\Http\Controllers\Admin\QuestionBankController::class, 'processImport'])->name('question-bank.import.process');
        Route::get('question-bank/export/template', [\App\Http\Controllers\Admin\QuestionBankController::class, 'downloadTemplate'])->name('question-bank.export.template');

        // Type-specific Import (Excel + JSON)
        Route::prefix('question-bank/import/type')->name('question-bank.import.type.')->group(function () {
            Route::get('{format}', [\App\Http\Controllers\Admin\QuestionBankTypeImportController::class, 'selectType'])->name('select');
            Route::get('{format}/{type}', [\App\Http\Controllers\Admin\QuestionBankTypeImportController::class, 'showImportForm'])->name('show');
            Route::get('{format}/{type}/template', [\App\Http\Controllers\Admin\QuestionBankTypeImportController::class, 'downloadTemplate'])->name('template');
            Route::post('{format}/{type}/preview', [\App\Http\Controllers\Admin\QuestionBankTypeImportController::class, 'previewImport'])->name('preview');
            Route::post('{format}/{type}/process', [\App\Http\Controllers\Admin\QuestionBankTypeImportController::class, 'processImport'])->name('process');
        });

        // Question Pools Management
        Route::resource('question-pools', \App\Http\Controllers\Admin\QuestionPoolController::class);
        Route::post('question-pools/{id}/duplicate', [\App\Http\Controllers\Admin\QuestionPoolController::class, 'duplicate'])->name('question-pools.duplicate');
        Route::post('question-pools/{id}/add-question', [\App\Http\Controllers\Admin\QuestionPoolController::class, 'addQuestion'])->name('question-pools.add-question');
        Route::delete('question-pools/{id}/remove-question/{itemId}', [\App\Http\Controllers\Admin\QuestionPoolController::class, 'removeQuestion'])->name('question-pools.remove-question');
        Route::post('question-pools/{id}/update-order', [\App\Http\Controllers\Admin\QuestionPoolController::class, 'updateOrder'])->name('question-pools.update-order');
        Route::post('question-pools/{id}/generate-questions', [\App\Http\Controllers\Admin\QuestionPoolController::class, 'generateQuestions'])->name('question-pools.generate-questions');
        Route::get('question-pools/{id}/statistics', [\App\Http\Controllers\Admin\QuestionPoolController::class, 'getStatistics'])->name('question-pools.statistics');

        // Quiz Grading
        Route::prefix('grading')->name('grading.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\QuizGradingController::class, 'index'])->name('index');
            Route::get('/{attemptId}/report', [\App\Http\Controllers\Admin\QuizGradingController::class, 'attemptReport'])->name('attempt-report');
            Route::get('/{attemptId}', [\App\Http\Controllers\Admin\QuizGradingController::class, 'show'])->name('show');
            Route::post('/responses/{responseId}/grade', [\App\Http\Controllers\Admin\QuizGradingController::class, 'gradeResponse'])->name('grade-response');
            Route::post('/bulk-grade', [\App\Http\Controllers\Admin\QuizGradingController::class, 'gradeBulk'])->name('bulk-grade');
            Route::post('/{attemptId}/complete', [\App\Http\Controllers\Admin\QuizGradingController::class, 'completeGrading'])->name('complete');
            Route::post('/{attemptId}/regrade', [\App\Http\Controllers\Admin\QuizGradingController::class, 'regradeAttempt'])->name('regrade');
            Route::get('/quiz/{quizId}/stats', [\App\Http\Controllers\Admin\QuizGradingController::class, 'getQuizStats'])->name('quiz-stats');
            Route::post('/export-report', [\App\Http\Controllers\Admin\QuizGradingController::class, 'exportReport'])->name('export-report');
        });

        // Quiz Analytics
        Route::prefix('quiz-analytics')->name('quiz-analytics.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\QuizAnalyticsController::class, 'index'])->name('index');
            Route::get('/quiz/{quizId}', [\App\Http\Controllers\Admin\QuizAnalyticsController::class, 'quiz'])->name('quiz');
            Route::get('/student/{studentId}', [\App\Http\Controllers\Admin\QuizAnalyticsController::class, 'student'])->name('student');
            Route::get('/course/{courseId}', [\App\Http\Controllers\Admin\QuizAnalyticsController::class, 'course'])->name('course');
            Route::post('/compare', [\App\Http\Controllers\Admin\QuizAnalyticsController::class, 'compare'])->name('compare');
            Route::post('/export', [\App\Http\Controllers\Admin\QuizAnalyticsController::class, 'export'])->name('export');
        });

        // ========== Question Modules Routes ==========

        // Question Modules Management
        Route::resource('question-modules', \App\Http\Controllers\Admin\QuestionModuleController::class);
        Route::get('question-modules/{id}/manage-questions', [\App\Http\Controllers\Admin\QuestionModuleController::class, 'manageQuestions'])->name('question-modules.manage-questions');
        Route::get('question-modules/{id}/import-questions', [\App\Http\Controllers\Admin\QuestionModuleController::class, 'importQuestions'])->name('question-modules.import-questions');
        Route::post('question-modules/{id}/add-question', [\App\Http\Controllers\Admin\QuestionModuleController::class, 'addQuestion'])->name('question-modules.add-question');
        Route::delete('question-modules/{id}/remove-question/{questionId}', [\App\Http\Controllers\Admin\QuestionModuleController::class, 'removeQuestion'])->name('question-modules.remove-question');
        Route::put('question-modules/{id}/update-question-settings/{questionId}', [\App\Http\Controllers\Admin\QuestionModuleController::class, 'updateQuestionSettings'])->name('question-modules.update-question-settings');
        Route::post('question-modules/{id}/reorder-questions', [\App\Http\Controllers\Admin\QuestionModuleController::class, 'reorderQuestions'])->name('question-modules.reorder-questions');
        Route::post('question-modules/{id}/toggle-publish', [\App\Http\Controllers\Admin\QuestionModuleController::class, 'togglePublish'])->name('question-modules.toggle-publish');
        Route::post('question-modules/{id}/toggle-visibility', [\App\Http\Controllers\Admin\QuestionModuleController::class, 'toggleVisibility'])->name('question-modules.toggle-visibility');

        // Question Module Grading
        Route::prefix('question-module-grading')->name('admin.question-module-grading.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\QuestionModuleGradingController::class, 'index'])->name('index');
            Route::get('/{attemptId}', [\App\Http\Controllers\Admin\QuestionModuleGradingController::class, 'show'])->name('show');
            Route::post('/responses/{responseId}/grade', [\App\Http\Controllers\Admin\QuestionModuleGradingController::class, 'gradeResponse'])->name('grade-response');
            Route::post('/bulk-grade', [\App\Http\Controllers\Admin\QuestionModuleGradingController::class, 'gradeBulk'])->name('bulk-grade');
            Route::post('/{attemptId}/complete', [\App\Http\Controllers\Admin\QuestionModuleGradingController::class, 'completeGrading'])->name('complete');
        });
