<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class TopController extends Controller
{
    public function top()
    {
        $questions = Question::orderByDesc('updated_at')
            ->limit(10)
            ->get();
        $count = Question::countQuestions();
        return view('top', compact('questions', 'count'));
    }
}
