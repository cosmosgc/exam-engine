<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QuestionMedia;
use Illuminate\Http\Request;

class QuestionMediaController extends Controller
{
    public function index()
    {
        return QuestionMedia::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'type' => 'required|in:image,audio,video',
            'src' => 'required|string',
        ]);

        $media = QuestionMedia::create($data);
        return response()->json($media, 201);
    }

    public function show(QuestionMedia $questionMedia)
    {
        return $questionMedia;
    }

    public function update(Request $request, QuestionMedia $questionMedia)
    {
        $data = $request->validate([
            'type' => 'sometimes|required|in:image,audio,video',
            'src' => 'sometimes|required|string',
        ]);

        $questionMedia->update($data);
        return response()->json($questionMedia);
    }

    public function destroy(QuestionMedia $questionMedia)
    {
        $questionMedia->delete();
        return response()->noContent();
    }
}
