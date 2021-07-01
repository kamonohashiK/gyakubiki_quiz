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

    public function new(Request $request)
    {
        //検索対象となる文字列が存在しない場合はトップにリダイレクト
        //TODO: indexとこことで処理を共通化
        if ($request->answer == null) {
            return redirect('/');
        } else {
            $query = $request->answer;
        }

        return view('questions.new', compact('query'));
    }

    public function create(Request $request)
    {
        //TODO: バリデーション
        //TODO: 例外処理
        //TODO: ログ出力
        $a = Answer::updateOrCreate(['name' => $request->answer]); //TODO: update時はuser_idを書き換えないよう修正
        if ($a) {
            $q = $a->questions()->create(['content' => $request->question]);
            if ($q) {
                return redirect(route('questions.index', ['answer' => $request->answer]))->with('success', '問題を追加しました。');
            }
        }
    }
}
