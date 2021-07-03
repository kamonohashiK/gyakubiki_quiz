<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Answer;
use App\Models\Question;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    //TODO: 問題を複数表示する場合の表示順をdescにする
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

    public function new(Request $request)
    {
        //検索対象となる文字列が存在しない場合はトップにリダイレクト
        //TODO: indexとこことで処理を共通化
        if ($request->answer == null) {
            return redirect('/');
        } else {
            $query = $request->answer;
        }

        $edit = false;
        $content = '';
        return view('questions.form', compact('query', 'edit', 'content'));
    }

    public function create(QuestionRequest $request)
    {
        //TODO: 例外処理
        $user = Auth::user();

        $answer = Answer::where('name', $request->answer)->first();
        if ($answer != null) {
            $a = $answer->update();
        } else {
            $a = Answer::create(['name' => $request->answer, 'user_id' => $user->id]);
            Log::info("ユーザーID：{$user->id}が解答：{$a->id}を投稿({$request->answer})");
            $answer = $a;
        }

        if ($a) {
            $q = $answer->questions()->create(['content' => $request->question, 'user_id' => $user->id]);
            if ($q) {
                Log::info("ユーザーID：{$user->id}が問題：{$q->id}を投稿({$request->question})");
                return redirect(route('questions.index', ['answer' => $request->answer]))->with('success', '問題を追加しました。');
            }
        }
    }

    public function edit(Question $question)
    {
        $user = Auth::user();

        if ($user->id != $question->user_id) {
            return redirect(route('questions.show', $question));
        }

        $query = $question->answer->name;
        $edit = true;
        $content = $question->content;
        return view('questions.form', compact('query', 'edit', 'content'));
    }

    public function update(Question $question, QuestionRequest $request)
    {
        $user = Auth::user();

        if ($user->id != $question->user_id) {
            return redirect(route('questions.show', $question));
        }

        //TODO: 例外処理
        $q = $question->update(['content' => $request->question]);
        if ($q) {
            Log::info("ユーザーID：{$user->id}が問題：{$question->id}を編集({$request->question})");
            return redirect(route('questions.show', $question))->with('success', '問題を編集しました。');
        }
    }

    public function delete(Question $question)
    {
        $user = Auth::user();

        if ($user->id != $question->user_id) {
            return redirect(route('questions.show', $question));
        }

        //TODO: 例外処理
        if ($question->delete()) {
            Log::info("ユーザーID：{$user->id}が問題：{$question->id}を削除");
            return redirect(route('questions.index', ['answer' => $question->answer->name]))->with('success', '問題を削除しました。');
        }
    }
}
