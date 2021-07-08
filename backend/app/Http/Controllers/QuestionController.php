<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Answer;
use App\Models\Question;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        //検索対象となる文字列が存在しない場合はトップにリダイレクト
        if ($request->answer == null) {
            return redirect('/');
        } else if ($request->like) {
            $query = $request->answer;
            $like = true;
            $answer = Answer::likeSearch($query)->get();
            $questions = [];
            foreach ($answer as $a) {
                foreach ($a->questions as $q) {
                    array_push($questions, $q);
                }
            }
        } else {
            $query = $request->answer;
            $like = false;
            $answer = Answer::where('name', $query)->first();
            if ($answer) {
                $questions = $answer->questions;
            } else {
                $questions = [];
            }
        }
        $suffix = $like ? 'が答えに含まれる問題' : 'が答えになる問題';

        return view('questions.index', compact('query', 'answer', 'like', 'questions', 'suffix'));
    }

    public function show(Question $question)
    {
        return view('questions.show', compact('question'));
    }

    public function new(Request $request)
    {
        //検索対象となる文字列が存在しない場合はトップにリダイレクト
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
        $user = Auth::user();

        DB::beginTransaction();
        try {
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
                    DB::commit();
                    Log::info("ユーザーID：{$user->id}が問題：{$q->id}を投稿({$request->question})");
                    return redirect(route('questions.index', ['answer' => $request->answer]))->with('success', '問題を追加しました。');
                }
            }
            throw new Exception();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("問題投稿時エラー: {$e}");
            return redirect(route('questions.new', $request->answer))->with('errors', '問題投稿中にエラーが発生しました。');
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

        try {
            $q = $question->update(['content' => $request->question]);
            if ($q) {
                Log::info("ユーザーID：{$user->id}が問題：{$question->id}を編集({$request->question})");
                return redirect(route('questions.show', $question))->with('success', '問題を編集しました。');
            }
        } catch (\Exception $e) {
            Log::error("問題編集時エラー: {$e}");
            return redirect(route('questions.show', $question));
        }
    }

    public function delete(Question $question)
    {
        $user = Auth::user();

        if ($user->id != $question->user_id) {
            return redirect(route('questions.show', $question));
        }

        try {
            if ($question->delete()) {
                Log::info("ユーザーID：{$user->id}が問題：{$question->id}を削除");
                return redirect(route('questions.index', ['answer' => $question->answer->name]))->with('success', '問題を削除しました。');
            }
            throw new Exception();
        } catch (\Exception $e) {
            Log::error("問題削除時エラー: {$e}");
            return redirect(route('questions.show', $question));
        }
    }
}
