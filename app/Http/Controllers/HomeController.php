<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use App\Models\Report;

class HomeController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'examCount' => Exam::count(),
            'questionCount' => Question::count(),
            // 'studentCount' => User::where('role', 'student')->count(),
            // 'reportCount' => Report::count(),
        ]);
    }
}
