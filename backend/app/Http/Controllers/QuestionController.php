<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        //検索対象となる文字列が存在しない場合はトップにリダイレクト
        if ($request->answer == null) {
            return redirect('/');
        } else {
            $answer = $request->answer;
        }

        return view('questions', compact('answer'));
    }

    public function show($question_id)
    {
        return view('questions.show', compact('question_id'));
    }
}
