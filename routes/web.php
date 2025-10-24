<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionMediaController;
use App\Http\Controllers\QuestionOptionController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Exams CRUD
    Route::resource('exams', ExamController::class)->name("index","examPage");

    // Nested routes (optional)
    Route::resource('questions', QuestionController::class);
    Route::resource('question-media', QuestionMediaController::class);
    Route::resource('question-options', QuestionOptionController::class);
});

require __DIR__.'/auth.php';
