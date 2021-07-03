<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function create(Question $question, CommentRequest $request)
    {
        $user = Auth::user();
        $c = $question->comments()->create([
            'user_id' => $user->id,
            'content' => $request->comment,
        ]);

        try {
            if ($c) {
                Log::info("ユーザーID：{$user->id}が問題：{$question->id}にコメントを投稿({$request->comment})");
                return redirect(route('questions.show', $question))->with('success', 'コメントを投稿しました。');
            }
        } catch (\Exception $e) {
            Log::error("コメント投稿時エラー: {$e}");
            return redirect(route('questions.show', $question));
        }
    }
}
