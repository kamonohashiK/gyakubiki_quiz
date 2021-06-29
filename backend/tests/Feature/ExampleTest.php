<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_トップページが正しく表示される()
    {
        $response = $this->get('/');

        $response->assertViewIs('top');
        $response->assertSee('top');
        $response->assertStatus(200);
    }

    public function test_問題一覧ページが正しく表示される()
    {
        $response = $this->get('/questions?answer=hoge');

        $response->assertViewIs('questions');
        $response->assertSee('hogeが答えになる問題');
        $response->assertStatus(200);
    }

    public function test_クエリによってquestionsで表示する文字列が変わる()
    {
        $response = $this->get('/questions?answer=fuga');
        $response->assertViewIs('questions');
        $response->assertSee('fugaが答えになる問題');

        $response = $this->get('/questions?answer=piyo');
        $response->assertViewIs('questions');
        $response->assertSee('piyoが答えになる問題');
    }
}
