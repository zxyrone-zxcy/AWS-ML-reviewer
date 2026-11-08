<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

// Public, read-only site: no login, no database.
Route::get('/', [QuizController::class, 'home'])->name('home');
Route::get('/quiz', [QuizController::class, 'quiz'])->name('quiz');
Route::get('/about', [QuizController::class, 'about'])->name('about');

// Question bank consumed by public/js/quiz.js
Route::get('/api/questions', [QuizController::class, 'questions'])->name('questions');
