<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamAnswerController extends Controller
{
    public function index(Request $request)
    {
        $attemptId = $request->query('attempt_id');
        return ExamAnswer::with('question')
            ->when($attemptId, fn($q) => $q->where('attempt_id', $attemptId))
            ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attempt_id' => 'required|exists:exam_attempts,id',
            'question_id' => 'required|exists:questions,id',
            'answer_text' => 'nullable|string',
        ]);

        $attempt = ExamAttempt::findOrFail($data['attempt_id']);
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $answer = ExamAnswer::create($data);
        return response()->json($answer, 201);
    }

    public function show(ExamAnswer $examAnswer)
    {
        return $examAnswer->load('question');
    }

    public function update(Request $request, ExamAnswer $examAnswer)
    {
        $attempt = $examAnswer->attempt;
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $data = $request->validate([
            'answer_text' => 'nullable|string',
            'is_correct' => 'nullable|boolean',
        ]);

        $examAnswer->update($data);
        return response()->json($examAnswer);
    }

    public function destroy(ExamAnswer $examAnswer)
    {
        $attempt = $examAnswer->attempt;
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $examAnswer->delete();
        return response()->noContent();
    }
}
