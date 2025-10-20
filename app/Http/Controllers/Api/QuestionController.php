<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // GET /api/questions
    public function index()
    {
        return Question::with(['media', 'options'])->get();
    }

    // POST /api/questions
    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'text' => 'required|string',
            'type' => 'required|in:multiple_choice,written,true_false,matching,audio_response',
            'correct_answer' => 'nullable|string',
        ]);

        $question = Question::create($data);

        return response()->json($question, 201);
    }

    // GET /api/questions/{question}
    public function show(Question $question)
    {
        return $question->load(['media', 'options']);
    }

    // PUT/PATCH /api/questions/{question}
    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'text' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:multiple_choice,written,true_false,matching,audio_response',
            'correct_answer' => 'nullable|string',
        ]);

        $question->update($data);

        return response()->json($question);
    }

    // DELETE /api/questions/{question}
    public function destroy(Question $question)
    {
        $question->delete();
        return response()->noContent();
    }
}
