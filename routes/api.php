<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    ExamController,
    QuestionController,
    QuestionMediaController,
    QuestionOptionController,
    ExamAttemptController,
    ExamAnswerController
};

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('exams', ExamController::class);
Route::apiResource('questions', QuestionController::class);
Route::apiResource('question-media', QuestionMediaController::class);
Route::apiResource('question-options', QuestionOptionController::class);
Route::apiResource('exam-attempts', ExamAttemptController::class);
Route::apiResource('exam-answers', ExamAnswerController::class);

Route::post('/questions/reorder', [QuestionController::class, 'reorder'])->name('api.questions.reorder');


Route::post('/import-exam', [ExamController::class, 'import']);