<?php

/**
 * Exam module — admin routes (quizzes, question bank, pools, modules, grading, analytics)
 *
 * Loaded inside admin middleware + /admin prefix (without admin. route name prefix).
 */

use App\Http\Controllers\Admin\QuestionBankController;
use App\Http\Controllers\Admin\QuestionBankTypeImportController;
use App\Http\Controllers\Admin\QuestionModuleController;
use App\Http\Controllers\Admin\QuestionModuleGradingController;
use App\Http\Controllers\Admin\QuestionPoolController;
use App\Http\Controllers\Admin\QuizAnalyticsController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\QuizGradingController;
use Illuminate\Support\Facades\Route;

// ========== Quizzes Routes ==========

Route::resource('quizzes', QuizController::class);
Route::post('quizzes/{id}/toggle-publish', [QuizController::class, 'togglePublish'])->name('quizzes.toggle-publish');
Route::get('quizzes/course/{courseId}/lessons', [QuizController::class, 'getLessons'])->name('quizzes.get-lessons');
Route::post('quizzes/{id}/recalculate-score', [QuizController::class, 'recalculateScore'])->name('quizzes.recalculate-score');

Route::get('quizzes/{id}/manage-questions', [QuizController::class, 'manageQuestions'])->name('quizzes.manage-questions');
Route::get('quizzes/{id}/import-questions', [QuizController::class, 'importQuestions'])->name('quizzes.import-questions');
Route::post('quizzes/{id}/add-question', [QuizController::class, 'addQuestion'])->name('quizzes.add-question');
Route::delete('quizzes/{id}/remove-question/{questionId}', [QuizController::class, 'removeQuestion'])->name('quizzes.remove-question');
Route::post('quizzes/{id}/remove-multiple-questions', [QuizController::class, 'removeMultipleQuestions'])->name('quizzes.remove-multiple-questions');
Route::post('quizzes/{id}/reorder-questions', [QuizController::class, 'reorderQuestions'])->name('quizzes.reorder-questions');

// Question Bank Management
Route::get('question-bank/create/{type}', [QuestionBankController::class, 'createByType'])->name('question-bank.create.type');
Route::resource('question-bank', QuestionBankController::class);
Route::post('question-bank/{id}/duplicate', [QuestionBankController::class, 'duplicate'])->name('question-bank.duplicate');
Route::get('question-bank/{id}/preview', [QuestionBankController::class, 'preview'])->name('question-bank.preview');
Route::get('question-bank/course/{courseId}/questions', [QuestionBankController::class, 'getQuestionsByCourse'])->name('question-bank.by-course');
Route::get('question-bank/type/{typeId}/questions', [QuestionBankController::class, 'getQuestionsByType'])->name('question-bank.by-type');
Route::post('question-bank/bulk-action', [QuestionBankController::class, 'bulkAction'])->name('question-bank.bulk-action');
Route::post('question-bank/delete-multiple', [QuestionBankController::class, 'destroyMultiple'])->name('question-bank.delete-multiple');

Route::get('question-bank/import/excel', [QuestionBankController::class, 'showImportForm'])->name('question-bank.import.excel');
Route::post('question-bank/import/preview', [QuestionBankController::class, 'previewImport'])->name('question-bank.import.preview');
Route::post('question-bank/import/process', [QuestionBankController::class, 'processImport'])->name('question-bank.import.process');
Route::get('question-bank/export/template', [QuestionBankController::class, 'downloadTemplate'])->name('question-bank.export.template');

Route::prefix('question-bank/import/type')->name('question-bank.import.type.')->group(function () {
    Route::get('{format}', [QuestionBankTypeImportController::class, 'selectType'])->name('select');
    Route::get('{format}/{type}', [QuestionBankTypeImportController::class, 'showImportForm'])->name('show');
    Route::get('{format}/{type}/template', [QuestionBankTypeImportController::class, 'downloadTemplate'])->name('template');
    Route::post('{format}/{type}/preview', [QuestionBankTypeImportController::class, 'previewImport'])->name('preview');
    Route::post('{format}/{type}/process', [QuestionBankTypeImportController::class, 'processImport'])->name('process');
});

// Question Pools Management
Route::resource('question-pools', QuestionPoolController::class);
Route::post('question-pools/{id}/duplicate', [QuestionPoolController::class, 'duplicate'])->name('question-pools.duplicate');
Route::post('question-pools/{id}/add-question', [QuestionPoolController::class, 'addQuestion'])->name('question-pools.add-question');
Route::delete('question-pools/{id}/remove-question/{itemId}', [QuestionPoolController::class, 'removeQuestion'])->name('question-pools.remove-question');
Route::post('question-pools/{id}/update-order', [QuestionPoolController::class, 'updateOrder'])->name('question-pools.update-order');
Route::post('question-pools/{id}/generate-questions', [QuestionPoolController::class, 'generateQuestions'])->name('question-pools.generate-questions');
Route::get('question-pools/{id}/statistics', [QuestionPoolController::class, 'getStatistics'])->name('question-pools.statistics');

// Quiz Grading
Route::prefix('grading')->name('grading.')->group(function () {
    Route::get('/', [QuizGradingController::class, 'index'])->name('index');
    Route::get('/{attemptId}/report', [QuizGradingController::class, 'attemptReport'])->name('attempt-report');
    Route::get('/{attemptId}', [QuizGradingController::class, 'show'])->name('show');
    Route::post('/responses/{responseId}/grade', [QuizGradingController::class, 'gradeResponse'])->name('grade-response');
    Route::post('/bulk-grade', [QuizGradingController::class, 'gradeBulk'])->name('bulk-grade');
    Route::post('/{attemptId}/complete', [QuizGradingController::class, 'completeGrading'])->name('complete');
    Route::post('/{attemptId}/regrade', [QuizGradingController::class, 'regradeAttempt'])->name('regrade');
    Route::get('/quiz/{quizId}/stats', [QuizGradingController::class, 'getQuizStats'])->name('quiz-stats');
    Route::post('/export-report', [QuizGradingController::class, 'exportReport'])->name('export-report');
});

// Quiz Analytics
Route::prefix('quiz-analytics')->name('quiz-analytics.')->group(function () {
    Route::get('/', [QuizAnalyticsController::class, 'index'])->name('index');
    Route::get('/quiz/{quizId}', [QuizAnalyticsController::class, 'quiz'])->name('quiz');
    Route::get('/student/{studentId}', [QuizAnalyticsController::class, 'student'])->name('student');
    Route::get('/course/{courseId}', [QuizAnalyticsController::class, 'course'])->name('course');
    Route::post('/compare', [QuizAnalyticsController::class, 'compare'])->name('compare');
    Route::post('/export', [QuizAnalyticsController::class, 'export'])->name('export');
});

// ========== Question Modules Routes ==========

Route::resource('question-modules', QuestionModuleController::class);
Route::get('question-modules/{id}/manage-questions', [QuestionModuleController::class, 'manageQuestions'])->name('question-modules.manage-questions');
Route::get('question-modules/{id}/import-questions', [QuestionModuleController::class, 'importQuestions'])->name('question-modules.import-questions');
Route::post('question-modules/{id}/add-question', [QuestionModuleController::class, 'addQuestion'])->name('question-modules.add-question');
Route::delete('question-modules/{id}/remove-question/{questionId}', [QuestionModuleController::class, 'removeQuestion'])->name('question-modules.remove-question');
Route::put('question-modules/{id}/update-question-settings/{questionId}', [QuestionModuleController::class, 'updateQuestionSettings'])->name('question-modules.update-question-settings');
Route::post('question-modules/{id}/reorder-questions', [QuestionModuleController::class, 'reorderQuestions'])->name('question-modules.reorder-questions');
Route::post('question-modules/{id}/toggle-publish', [QuestionModuleController::class, 'togglePublish'])->name('question-modules.toggle-publish');
Route::post('question-modules/{id}/toggle-visibility', [QuestionModuleController::class, 'toggleVisibility'])->name('question-modules.toggle-visibility');

Route::prefix('question-module-grading')->name('admin.question-module-grading.')->group(function () {
    Route::get('/', [QuestionModuleGradingController::class, 'index'])->name('index');
    Route::get('/{attemptId}', [QuestionModuleGradingController::class, 'show'])->name('show');
    Route::post('/responses/{responseId}/grade', [QuestionModuleGradingController::class, 'gradeResponse'])->name('grade-response');
    Route::post('/bulk-grade', [QuestionModuleGradingController::class, 'gradeBulk'])->name('bulk-grade');
    Route::post('/{attemptId}/complete', [QuestionModuleGradingController::class, 'completeGrading'])->name('complete');
});
