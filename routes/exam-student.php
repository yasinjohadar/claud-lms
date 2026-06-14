<?php

/**
 * Exam module — student routes (quizzes + question modules)
 */

use App\Http\Controllers\Student\QuestionModuleAttemptController;
use App\Http\Controllers\Student\QuestionModuleStatsController;
use App\Http\Controllers\Student\QuizAttemptController;
use App\Http\Controllers\Student\QuizReviewController;
use Illuminate\Support\Facades\Route;

// ========== Quizzes Routes (Student) ==========

Route::prefix('quizzes/review')->name('student.quizzes.review.')->group(function () {
    Route::get('/', [QuizReviewController::class, 'index'])->name('index');
    Route::get('/{attemptId}', [QuizReviewController::class, 'show'])->name('show');
    Route::get('/analytics/overview', [QuizReviewController::class, 'analytics'])->name('analytics');
    Route::get('/quiz/{quizId}/compare', [QuizReviewController::class, 'compareAttempts'])->name('compare');
    Route::get('/quiz/{quizId}/history', [QuizReviewController::class, 'history'])->name('history');
    Route::get('/{attemptId}/question/{questionId}', [QuizReviewController::class, 'getQuestionReview'])->name('question');
    Route::get('/{attemptId}/download-report', [QuizReviewController::class, 'downloadReport'])->name('download-report');
});

Route::prefix('quizzes')->name('student.quizzes.')->group(function () {
    Route::get('/', [QuizAttemptController::class, 'index'])->name('index');
    Route::get('/{id}', [QuizAttemptController::class, 'show'])->name('show');
    Route::post('/{id}/start', [QuizAttemptController::class, 'start'])->name('start');
    Route::get('/attempt/{attemptId}/take', [QuizAttemptController::class, 'take'])->name('take');
    Route::post('/attempt/{attemptId}/save-answer', [QuizAttemptController::class, 'saveAnswer'])->name('save-answer');
    Route::post('/attempt/{attemptId}/mark-review/{questionId}', [QuizAttemptController::class, 'markForReview'])->name('mark-review');
    Route::post('/attempt/{attemptId}/submit', [QuizAttemptController::class, 'submit'])->name('submit');
    Route::post('/attempt/{attemptId}/mark-completed', [QuizAttemptController::class, 'markCompleted'])->name('mark-completed');
    Route::get('/attempt/{attemptId}/progress', [QuizAttemptController::class, 'getProgress'])->name('progress');
});

// ========== Question Modules Routes (Student) ==========

Route::prefix('question-modules')->name('student.question-module.')->group(function () {
    Route::get('/{questionModule}/start', [QuestionModuleAttemptController::class, 'start'])->name('start');
    Route::get('/attempts/{attempt}/take', [QuestionModuleAttemptController::class, 'take'])->name('take');
    Route::post('/attempts/{attempt}/save-answer', [QuestionModuleAttemptController::class, 'saveAnswer'])->name('save-answer');
    Route::post('/attempts/{attempt}/submit', [QuestionModuleAttemptController::class, 'submit'])->name('submit');
    Route::get('/attempts/{attempt}/result', [QuestionModuleAttemptController::class, 'result'])->name('result');
});

Route::prefix('question-modules/stats')->name('student.question-module.stats.')->group(function () {
    Route::get('/', [QuestionModuleStatsController::class, 'index'])->name('index');
    Route::get('/dashboard', [QuestionModuleStatsController::class, 'getDashboardStats'])->name('dashboard');
    Route::get('/{questionModule}/module', [QuestionModuleStatsController::class, 'showModuleStats'])->name('module');
});
