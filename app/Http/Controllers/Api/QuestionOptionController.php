<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuestionOption;
use Illuminate\Http\Request;

class QuestionOptionController extends Controller
{
    public function index()
    {
        return QuestionOption::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'label' => 'required|string|max:1',
            'text' => 'required|string',
        ]);

        $option = QuestionOption::create($data);
        return response()->json($option, 201);
    }

    public function show(QuestionOption $questionOption)
    {
        return $questionOption;
    }

    public function update(Request $request, QuestionOption $questionOption)
    {
        $data = $request->validate([
            'label' => 'sometimes|required|string|max:1',
            'text' => 'sometimes|required|string',
        ]);

        $questionOption->update($data);
        return response()->json($questionOption);
    }

    public function destroy(QuestionOption $questionOption)
    {
        $questionOption->delete();
        return response()->noContent();
    }
}
