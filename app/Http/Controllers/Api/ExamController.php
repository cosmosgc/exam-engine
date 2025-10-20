<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionMedia;
use App\Models\QuestionOption;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    // GET /api/exams
    public function index()
    {
        return Exam::with('questions.media', 'questions.options')->get();
    }

    // POST /api/exams
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $exam = Exam::create($data);

        return response()->json($exam, 201);
    }

    // GET /api/exams/{exam}
    public function show(Exam $exam)
    {
        return $exam->load('questions.media', 'questions.options');
    }

    // PUT/PATCH /api/exams/{exam}
    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $exam->update($data);

        return response()->json($exam);
    }

    // DELETE /api/exams/{exam}
    public function destroy(Exam $exam)
    {
        $exam->delete();
        return response()->noContent();
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,txt',
        ]);

        $json = json_decode(file_get_contents($request->file('file')->getRealPath()), true);

        if (!$json || !isset($json['title'], $json['questions'])) {
            return response()->json(['error' => 'Invalid JSON structure'], 400);
        }

        DB::beginTransaction();

        try {
            // Create the exam
            $exam = Exam::create([
                'title' => $json['title'],
                'description' => $json['description'] ?? null,
                'duration_minutes' => $json['duration'] ?? null,
            ]);

            // Loop through questions
            foreach ($json['questions'] as $q) {
                $question = Question::create([
                    'exam_id' => $exam->id,
                    'text' => $q['text'],
                    'question_type' => isset($q['options']) ? 'multiple_choice' : 'written',
                ]);

                // Media (optional)
                if (!empty($q['media'])) {
                    foreach ($q['media'] as $m) {
                        QuestionMedia::create([
                            'question_id' => $question->id,
                            'type' => $m['type'],
                            'src' => $m['src'],
                        ]);
                    }
                }

                // Options (if applicable)
                if (!empty($q['options'])) {
                    foreach ($q['options'] as $key => $value) {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'label' => $key,
                            'text' => $value,
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Exam imported successfully',
                'exam_id' => $exam->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
