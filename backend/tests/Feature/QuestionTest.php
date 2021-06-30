<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Answer;

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

    //TODO: 以下が実際のDBのデータに依存しているのでテストだけで試せるような仕組みにしたい
    public function test_問題詳細ページが正しく表示される()
    {
        $a = Answer::create(['name' => 'test']);
        $q = $a->questions()->create(['content' => 'test']);

        $response = $this->get('/questions/' . $q->id);
        $response->assertViewIs('questions.show');
        $response->assertSee($q->content);
        $response->assertSee('コメント一覧');
        $response->assertStatus(200);
    }

    public function test_存在しない問題のidが入力されると404ページに行く()
    {
        $response = $this->get('/questions/2');
        $response->assertStatus(404);
    }
}
