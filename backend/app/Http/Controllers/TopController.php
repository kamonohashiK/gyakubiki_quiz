<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class TopController extends Controller
{
    public function top()
    {
        $answers = Answer::orderBy('updated_at')->get();
        return view('top', compact('answers'));
    }
}
