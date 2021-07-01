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
        $response->assertSee('hogeが答えになる問題');
        $response->assertSee('hogeが答えになる問題を作る');
        //TODO: 問題のリストが正しく表示されるかを試すテストを書きたい
        $response->assertStatus(200);
    }

    public function test_クエリによってquestionsで表示する文字列が変わる()
    {
        $response = $this->get('/questions?answer=fuga');
        $response->assertViewIs('questions.index');
        $response->assertSee('fugaが答えになる問題');

        $response = $this->get('/questions?answer=piyo');
        $response->assertViewIs('questions.index');
        $response->assertSee('piyoが答えになる問題');
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
        $response->assertSee('コメント一覧');
        $response->assertStatus(200);
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
        $response->assertViewIs('questions.new');
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
}
