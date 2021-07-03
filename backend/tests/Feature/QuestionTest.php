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
    }

    public function test_問題一覧ページが正しく表示される()
    {
        $response = $this->get('/questions?answer=hoge');

        $response->assertViewIs('questions.index');
        $response->assertSee('クイズ逆引き事典');
        $response->assertSee('問題を検索');
        $response->assertSee('hoge');
        $response->assertSee('問題を作る');
        //TODO: 問題のリストが正しく表示されるかを試すテストを書きたい
        $response->assertStatus(200);
    }

    public function test_クエリによってquestionsで表示する文字列が変わる()
    {
        $response = $this->get('/questions?answer=fuga');
        $response->assertViewIs('questions.index');
        $response->assertSee('fuga');

        $response = $this->get('/questions?answer=piyo');
        $response->assertViewIs('questions.index');
        $response->assertSee('piyo');
    }

    public function test_クエリがない場合はトップページにリダイレクト()
    {
        $response = $this->get('/questions');
        $response->assertRedirect('/');
    }

    public function test_問題詳細ページが正しく表示される()
    {
        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $q = $a->questions()->create(['content' => 'test', 'user_id' => 1]);

        $response = $this->get('/questions/' . $q->id);
        $response->assertViewIs('questions.show');
        $response->assertSee($q->content);
        $response->assertDontSee('編集');
        $response->assertSee('コメント一覧');
        $response->assertStatus(200);
        //TODO: コメント投稿フォームが正しく表示されるかのテストを書きたい
    }

    public function test_作問者が問題詳細ページに行くと編集・削除リンクが表示される()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $q = $a->questions()->create(['content' => 'test', 'user_id' => 1]);

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
        $response = $this->get('/new-question?answer=hoge');
        $response->assertRedirect('/login');
    }

    public function test_問題作成ページが正しく表示される()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/new-question?answer=hoge');
        $response->assertViewIs('questions.form');
        $response->assertSee('hoge');
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
            'answer' => 'hoge',
            'question' => 'fuga',
        ]);

        $this->assertDatabaseHas('answers', ['name' => 'hoge', 'user_id' => $user->id]);
        $this->assertDatabaseHas('questions', ['content' => 'fuga', 'user_id' => $user->id]);
        $response->assertRedirect('/questions?answer=hoge')->assertSessionHas('success');
    }

    public function test_問題編集ページを表示する()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $q = $a->questions()->create(['content' => 'test', 'user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->get('/edit-question/' . $q->id);
        $response->assertViewIs('questions.form');
        $response->assertStatus(200);
    }

    public function test_問題編集ページは作問者以外はリダイレクトする()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $q = $a->questions()->create(['content' => 'test', 'user_id' => $user->id + 1]);

        $response = $this->actingAs($user)
            ->get('/edit-question/' . $q->id);
        $response->assertRedirect('/questions/' . $q->id);
    }

    public function test_問題編集ページでpostした際、データが正常に保存される()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $q = $a->questions()->create(['content' => 'test', 'user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->post('/edit-question/' . $q->id, [
                'answer' => 'test',
                'question' => 'fuga',
            ]);
        $this->assertDatabaseHas('answers', ['name' => 'test', 'user_id' => 1]);
        $this->assertDatabaseHas('questions', ['content' => 'fuga', 'user_id' => $user->id]);
        $this->assertDatabaseMissing('questions', ['content' => 'test', 'user_id' => $user->id]);
        $response->assertRedirect('/questions/' . $q->id)->assertSessionHas('success');
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
