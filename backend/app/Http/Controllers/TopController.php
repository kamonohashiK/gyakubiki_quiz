<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class TopController extends Controller
{
    public function top()
    {
        $answers = Answer::orderBy('updated_at')->get();
        $count = Question::countQuestions();
        return view('top', compact('answers', 'count'));
    }
}
