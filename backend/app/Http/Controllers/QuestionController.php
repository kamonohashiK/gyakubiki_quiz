<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        //検索対象となる文字列が存在しない場合はトップにリダイレクト
        if ($request->answer == null) {
            return redirect('/');
        } else {
            $query = $request->answer;
            $answer = Answer::where('name', $query)->first();
        }

        return view('questions.index', compact('query', 'answer'));
    }

    public function show(Question $question)
    {
        return view('questions.show', compact('question'));
    }
}
