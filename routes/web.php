<?php

use App\Http\Controllers\AttemptController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('quizzes.index');
});

// Admin / Creation Routes
Route::get('/quizzes/create', [QuizController::class, 'create'])->name('quizzes.create');
Route::post('/quizzes', [QuizController::class, 'store'])->name('quizzes.store');
Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');

// Manage Quiz Questions
Route::get('/quizzes/{quiz}/manage', [QuizController::class, 'manage'])->name('quizzes.manage');
Route::get('/quizzes/{quiz}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('questions.store');
Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

// Public Attempt Routes
Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
Route::post('/quizzes/{quiz}/attempts', [AttemptController::class, 'store'])->name('attempts.store');

// Topic Quiz Routes
Route::get('/topics/{topic}/take', [\App\Http\Controllers\TopicController::class, 'takeQuiz'])->name('topics.take');
Route::post('/topics/{topic}/attempts', [AttemptController::class, 'storeTopic'])->name('topics.attempts.store');

Route::get('/attempts/{attempt}', [AttemptController::class, 'show'])->name('attempts.show');
