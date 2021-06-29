<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $answer = $request->answer;
        return view('questions', compact('answer'));
    }
}
