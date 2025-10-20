<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamAttemptController extends Controller
{
    public function index()
    {
        return ExamAttempt::with('exam')->where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id' => 'required|exists:exams,id',
        ]);

        $attempt = ExamAttempt::create([
            'user_id' => Auth::id(),
            'exam_id' => $data['exam_id'],
            'started_at' => now(),
        ]);

        return response()->json($attempt, 201);
    }

    public function show(ExamAttempt $examAttempt)
    {
        $this->authorizeUser($examAttempt);
        return $examAttempt->load('answers.question');
    }

    public function update(Request $request, ExamAttempt $examAttempt)
    {
        $this->authorizeUser($examAttempt);

        $data = $request->validate([
            'finished_at' => 'nullable|date',
            'score' => 'nullable|numeric|min:0|max:100',
        ]);

        $examAttempt->update($data);
        return response()->json($examAttempt);
    }

    public function destroy(ExamAttempt $examAttempt)
    {
        $this->authorizeUser($examAttempt);
        $examAttempt->delete();
        return response()->noContent();
    }

    private function authorizeUser(ExamAttempt $attempt)
    {
        if ($attempt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
    }
}
