<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Answer;
use App\Models\User;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();
        $this->artisan('migrate');
        
        $this->VALID_ANSWER = 'かも屋';
        $this->VALID_QUESTION = 'このアプリの開発者は誰でしょう？';
    }

    public function test_問題一覧ページが正しく表示される()
    {
        $response = $this->get('/questions?answer=' . $this->VALID_ANSWER);

        $response->assertViewIs('questions.index');
        $response->assertSee('クイズ逆引き事典');
        $response->assertSee('検索ワード');
        $response->assertSeeText($this->VALID_ANSWER . 'が答えになる問題');
        $response->assertSee('問題を作る');
        $response->assertSessionHas('query', $this->VALID_ANSWER);
        //TODO: 問題のリストが正しく表示されるかを試すテストを書きたい
        $response->assertStatus(200);
    }

    public function test_likeクエリがある場合、問題一覧ページが正しく表示される()
    {
        $response = $this->get('/questions?answer=' . $this->VALID_ANSWER . '&like=1');
        $response->assertSeeText($this->VALID_ANSWER . 'が答えに含まれる問題');
        $response->assertDontSee('問題を作る');
        $response->assertSessionHas('query', $this->VALID_ANSWER);
        $response->assertSessionHas('like', 1);
        $response->assertStatus(200);
    }

    public function test_questionクエリがある場合、問題文に特定のワードが含まれる問題が表示される()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => $this->VALID_ANSWER, 'user_id' => 1]);
        $q1 = $a->questions()->create(['content' => $this->VALID_QUESTION, 'user_id' => $user->id]);
        $q2 = $a->questions()->create(['content' => 'かも屋の出身地はどこでしょう？', 'user_id' => $user->id]);

        $response = $this->get('/questions?answer=' . $this->VALID_ANSWER . '&question=1');
        $response->assertSeeText($this->VALID_ANSWER . 'が問題文に含まれる問題');

        $response->assertDontSee($this->VALID_QUESTION);
        $response->assertSee('かも屋の出身地はどこでしょう？');

        $response->assertSessionHas('query', $this->VALID_ANSWER);
        $response->assertSessionHas('like', 1);
        $response->assertSessionHas('question', 1);
        $response->assertStatus(200);
    }

    public function test_クエリによってquestionsで表示する文字列が変わる()
    {
        $q1 = 'fuga';
        $q2 = 'piyo';

        $response = $this->get('/questions?answer=' . $q1);
        $response->assertViewIs('questions.index');
        $response->assertSee($q1);

        $response = $this->get('/questions?answer=' . $q2);
        $response->assertViewIs('questions.index');
        $response->assertSee($q2);
    }

    public function test_クエリがない場合はトップページにリダイレクト()
    {
        $response = $this->get('/questions');
        $response->assertRedirect('/');
    }

    public function test_問題詳細ページが正しく表示される()
    {
        $a = Answer::create(['name' => $this->VALID_ANSWER, 'user_id' => 1]);
        $q = $a->questions()->create(['content' => $this->VALID_QUESTION, 'user_id' => 1]);

        $response = $this->get('/questions/' . $q->id);
        $response->assertViewIs('questions.show');
        $response->assertSee($this->VALID_ANSWER);
        $response->assertSee($this->VALID_QUESTION);
        $response->assertSee('問題を作る');
        $response->assertDontSee('編集');
        $response->assertDontSee('削除');
        $response->assertSee('コメント');
        $response->assertStatus(200);
        //TODO: コメント投稿フォームが正しく表示されるかのテストを書きたい
    }

    public function test_作問者が問題詳細ページに行くと編集・削除リンクが表示される()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => $this->VALID_ANSWER, 'user_id' => 1]);
        $q = $a->questions()->create(['content' => $this->VALID_QUESTION, 'user_id' => 1]);

        $response = $response = $this
            ->actingAs($user)
            ->get('/questions/' . $q->id);
        $response->assertSee('編集');
        $response->assertSee('削除');
    }

    public function test_存在しない問題のidが入力されると404ページに行く()
    {
        $response = $this->get('/questions/1');
        $response->assertStatus(404);
    }

    public function test_問題作成ページではログインを要求する()
    {
        $response = $this->get('/new-question?answer=' . $this->VALID_ANSWER);
        $response->assertRedirect('/login');
    }

    public function test_問題作成ページが正しく表示される()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/new-question?answer=' . $this->VALID_ANSWER);
        $response->assertViewIs('questions.form');
        $response->assertSee($this->VALID_ANSWER);
        $response->assertStatus(200);
    }

    public function test_問題作成ページでクエリがない場合はトップページにリダイレクト()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/new-question');
        $response->assertRedirect('/');
    }

    public function test_問題作成ページでpostした際、データが正常に保存される()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post('/new-question', [
            'answer' => $this->VALID_ANSWER,
            'question' => $this->VALID_QUESTION,
        ]);

        $this->assertDatabaseHas('answers', ['name' => $this->VALID_ANSWER, 'user_id' => $user->id]);
        $this->assertDatabaseHas('questions', ['content' => $this->VALID_QUESTION, 'user_id' => $user->id]);
        $response->assertRedirect('/questions?answer=' . urlencode($this->VALID_ANSWER))->assertSessionHas('success');
    }

    public function test_問題作成ページでquestionが空白の場合、データは保存されない()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from('/new-question')
            ->post('/new-question', [
            'answer' => $this->VALID_ANSWER,
            'question' => '',
        ]);
        $this->assertDatabaseMissing('questions', ['content' => '', 'user_id' => $user->id]);
        $response->assertRedirect('/new-question')->assertSessionHas('errors');
    }

    public function test_問題投稿時、questionが10文字以下の場合、データは保存されない()
    {
        $user = User::factory()->create();
        $invalid_question = str_repeat('あ', 9);

        $response = $this->actingAs($user)
            ->from('/new-question')
            ->post('/new-question', [
            'answer' => $this->VALID_ANSWER,
            'question' => $invalid_question,
        ]);
        $this->assertDatabaseMissing('questions', ['content' => 'あ', 'user_id' => $user->id]);
        $response->assertRedirect('/new-question')->assertSessionHas('errors');
    }

    public function test_問題投稿時、questionが100文字以上の場合、データは保存されない()
    {
        $user = User::factory()->create();
        $invalid_question = str_repeat('あ', 101);

        $response = $this->actingAs($user)
            ->from('/new-question')
            ->post('/new-question', [
            'answer' => $this->VALID_ANSWER,
            'question' => $invalid_question,
        ]);
        $this->assertDatabaseMissing('questions', ['content' => $invalid_question, 'user_id' => $user->id]);
        $response->assertRedirect('/new-question')->assertSessionHas('errors');
    }

    public function test_問題投稿時、answerが20文字以上の場合、データは保存されない()
    {
        $user = User::factory()->create();
        $invalid_answer = str_repeat('あ', 21);

        $response = $this->actingAs($user)
            ->from('/new-question')
            ->post('/new-question', [
            'answer' => $invalid_answer,
            'question' => $this->VALID_QUESTION,
        ]);
        $this->assertDatabaseMissing('questions', ['content' => $this->VALID_QUESTION, 'user_id' => $user->id]);
        $response->assertRedirect('/new-question')->assertSessionHas('errors');
    }

    public function test_問題編集ページを表示する()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => $this->VALID_ANSWER, 'user_id' => 1]);
        $q = $a->questions()->create(['content' => $this->VALID_QUESTION, 'user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get('/edit-question/' . $q->id);
        $response->assertViewIs('questions.form');
        $response->assertStatus(200);
    }

    public function test_問題編集ページは作問者以外はリダイレクトする()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => $this->VALID_ANSWER, 'user_id' => 1]);
        $q = $a->questions()->create(['content' => $this->VALID_QUESTION, 'user_id' => $user->id + 1]);

        $response = $this->actingAs($user)
            ->get('/edit-question/' . $q->id);
        $response->assertRedirect('/questions/' . $q->id);
    }

    public function test_問題編集ページでpostした際、データが正常に保存される()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => $this->VALID_ANSWER, 'user_id' => 1]);
        $q = $a->questions()->create(['content' => $this->VALID_QUESTION, 'user_id' => $user->id]);

        $new_question = 'カモノハシをモチーフにしたTwitterアイコンを使用している、このアプリの作者は誰でしょう？';

        $response = $this->actingAs($user)
            ->post('/edit-question/' . $q->id, [
                'answer' => $this->VALID_ANSWER,
                'question' => $new_question,
            ]);
        $this->assertDatabaseHas('answers', ['name' => $this->VALID_ANSWER, 'user_id' => 1]);
        $this->assertDatabaseHas('questions', ['content' => $new_question, 'user_id' => $user->id]);
        $this->assertDatabaseMissing('questions', ['content' => $this->VALID_QUESTION, 'user_id' => $user->id]);
        $response->assertRedirect('/questions/' . $q->id)->assertSessionHas('success');
    }

    public function test_問題編集時に問題文が空白の場合、データは保存されない()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => $this->VALID_ANSWER, 'user_id' => 1]);
        $q = $a->questions()->create(['content' => $this->VALID_QUESTION, 'user_id' => $user->id]);

        $new_question = '';

        $response = $this->actingAs($user)
            ->from('/edit-question/' . $q->id)
            ->post('/edit-question/' . $q->id, [
                'answer' => $this->VALID_ANSWER,
                'question' => $new_question,
            ]);
        $this->assertDatabaseMissing('questions', ['content' => $new_question, 'user_id' => $user->id]);
        $response->assertRedirect('/edit-question/' . $q->id)->assertSessionHas('errors');
    }

    public function test_問題編集時に問題文が10文字以下の場合、データは保存されない()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => $this->VALID_ANSWER, 'user_id' => 1]);
        $q = $a->questions()->create(['content' => $this->VALID_QUESTION, 'user_id' => $user->id]);

        $new_question = str_repeat('あ', 9);

        $response = $this->actingAs($user)
            ->from('/edit-question/' . $q->id)
            ->post('/edit-question/' . $q->id, [
                'answer' => $this->VALID_ANSWER,
                'question' => $new_question,
            ]);
        $this->assertDatabaseMissing('questions', ['content' => $new_question, 'user_id' => $user->id]);
        $response->assertRedirect('/edit-question/' . $q->id)->assertSessionHas('errors');
    }

    public function test_問題編集時に問題文が100文字以上の場合、データは保存されない()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => $this->VALID_ANSWER, 'user_id' => 1]);
        $q = $a->questions()->create(['content' => $this->VALID_QUESTION, 'user_id' => $user->id]);

        $new_question = str_repeat('あ', 101);

        $response = $this->actingAs($user)
            ->from('/edit-question/' . $q->id)
            ->post('/edit-question/' . $q->id, [
                'answer' => $this->VALID_ANSWER,
                'question' => $new_question,
            ]);
        $this->assertDatabaseMissing('questions', ['content' => $new_question, 'user_id' => $user->id]);
        $response->assertRedirect('/edit-question/' . $q->id)->assertSessionHas('errors');
    }

    public function test_問題削除が正常に行われる()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $q = $a->questions()->create(['content' => 'test', 'user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->delete('/delete-question/' . $q->id);
        $this->assertSoftDeleted('questions', ['content' => 'test', 'user_id' => $user->id]);
    }

    public function test_作問者以外が問題削除しようとした場合リダイレクト()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $q = $a->questions()->create(['content' => 'test', 'user_id' => $user->id + 1]);

        $response = $this->actingAs($user)
            ->delete('/delete-question/' . $q->id);
        $response->assertRedirect('/questions/' . $q->id);
    }
}
