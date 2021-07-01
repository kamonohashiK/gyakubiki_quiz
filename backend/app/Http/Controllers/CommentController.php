<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function create(Question $question, Request $request)
    {
        $user = Auth::user();
        //TODO: バリデーション、例外処理等
        $c = $question->comments()->create([
            'user_id' => $user->id,
            'content' => $request->comment,
        ]);
        if ($c) {
            return redirect(route('questions.show', $question))->with('success', 'コメントを投稿しました。');
        }
    }
}
