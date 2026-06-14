<?php

/**
 * Exam module — AI question routes (merge inside Route::prefix('ai') group)
 */

use App\Http\Controllers\Admin\AIQuestionCreationController;
use App\Http\Controllers\Admin\AIQuestionGenerationController;
use App\Http\Controllers\Admin\AIQuestionSolvingController;
use Illuminate\Support\Facades\Route;

Route::get('question-creation/create', [AIQuestionCreationController::class, 'create'])->name('question-creation.create');
Route::post('question-creation', [AIQuestionCreationController::class, 'store'])->name('question-creation.store');

Route::resource('question-generations', AIQuestionGenerationController::class)->names([
    'index' => 'question-generations.index',
    'create' => 'question-generations.create',
    'store' => 'question-generations.store',
    'show' => 'question-generations.show',
]);

Route::post('question-generations/{generation}/process', [AIQuestionGenerationController::class, 'process'])->name('question-generations.process');
Route::post('question-generations/{generation}/save', [AIQuestionGenerationController::class, 'save'])->name('question-generations.save');
Route::post('question-generations/{generation}/save-selected', [AIQuestionGenerationController::class, 'saveSelected'])->name('question-generations.save-selected');
Route::post('question-generations/{generation}/regenerate', [AIQuestionGenerationController::class, 'regenerate'])->name('question-generations.regenerate');

Route::get('question-solutions', [AIQuestionSolvingController::class, 'index'])->name('question-solutions.index');
Route::post('question-solutions/solve/{question}', [AIQuestionSolvingController::class, 'solve'])->name('question-solutions.solve');
Route::post('question-solutions/solve-multiple', [AIQuestionSolvingController::class, 'solveMultiple'])->name('question-solutions.solve-multiple');
Route::post('question-solutions/{solution}/verify', [AIQuestionSolvingController::class, 'verify'])->name('question-solutions.verify');
Route::get('question-solutions/{solution}', [AIQuestionSolvingController::class, 'show'])->name('question-solutions.show');
