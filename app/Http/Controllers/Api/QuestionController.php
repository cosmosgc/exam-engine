<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

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
            'type' => 'sometimes|in:multiple_choice,written,true_false,matching,audio_response',
            'options' => 'array',
            'options.*.id' => 'nullable|integer|exists:question_options,id',
            'options.*.text' => 'required|string',
            'options.*.label' => 'required|string',
            'options.*.is_correct' => 'boolean'
        ]);

        // Default type if not provided
        $data['type'] = $data['type'] ?? 'multiple_choice';

        // Create the question
        $question = \App\Models\Question::create([
            'exam_id' => $data['exam_id'],
            'text' => $data['text'],
            'type' => $data['type'],
        ]);

        // 1️⃣ Handle options
        $correctAnswerText = null;

        if (isset($data['options'])) {
            foreach ($data['options'] as $opt) {
                $option = $question->options()->create([
                    'text' => $opt['text'],
                    'label' => $opt['label'],
                    'is_correct' => $opt['is_correct'] ?? false,
                ]);

                if (!empty($opt['is_correct']) && $opt['is_correct'] === true) {
                    $correctAnswerText = $opt['text'];
                }
            }
        }

        // 2️⃣ Update the question with the correct answer if exists
        if ($correctAnswerText !== null) {
            $question->update(['correct_answer' => $correctAnswerText]);
        }

        // 3️⃣ Return full question with options
        return response()->json(
            $question->load('options'),
            201
        );
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
            'options' => 'array',
            'options.*.id' => 'nullable|integer|exists:question_options,id',
            'options.*.text' => 'required|string',
            'options.*.label' => 'required|string',
            'options.*.is_correct' => 'boolean'
        ]);
        // 1️⃣ Sync question options and extract the correct one
        $correctAnswerText = null;

        if (isset($data['options'])) {
            $existingOptionIds = $question->options()->pluck('id')->toArray();
            $incomingOptionIds = collect($data['options'])->pluck('id')->filter()->toArray();

            // Delete removed options
            $toDelete = array_diff($existingOptionIds, $incomingOptionIds);
            if (!empty($toDelete)) {
                \App\Models\QuestionOption::destroy($toDelete);
            }

            // Update or create options
            foreach ($data['options'] as $opt) {
                if (!empty($opt['id']) || !isNull($opt['id'])) {
                    // Update existing option
                    \App\Models\QuestionOption::where('id', $opt['id'])->update([
                        'text' => $opt['text'],
                    ]);
                } else {
                    // Create new option
                    $question->options()->create([
                        'text' => $opt['text'],
                        'label' => $opt['label']
                    ]);
                }

                // If this option is correct, capture its text
                if (!empty($opt['is_correct']) && $opt['is_correct'] === true) {
                    $correctAnswerText = $opt['text'];
                }
            }
        }

        // 2️⃣ Update the question itself
        $question->update([
            'text' => $data['text'] ?? $question->text,
            'type' => $data['type'] ?? $question->type,
            'correct_answer' => $correctAnswerText ?? $question->correct_answer,
        ]);

        // 3️⃣ Return updated question with options
        return response()->json(
            $question->load('options')
        );
    }




    // DELETE /api/questions/{question}
    public function destroy(Question $question)
    {
        $question->delete();
        return response()->noContent();
    }

    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:questions,id',
            'order.*.order' => 'required|integer',
        ]);

        foreach ($data['order'] as $item) {
            Question::where('id', $item['id'])->update(['order' => $item['order']]);
        }

        return response()->json(['success' => true]);
    }

}
