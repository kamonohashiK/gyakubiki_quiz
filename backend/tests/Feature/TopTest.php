<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Answer;

class TopTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_トップページが正しく表示される()
    {
        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $a->questions()->createMany([
            ['content' => 'test', 'user_id' => 1],
            ['content' => 'test', 'user_id' => 2],
        ]);

        $response = $this->get('/');

        $response->assertViewIs('top');
        $response->assertSee('クイズ逆引き事典');
        $response->assertSee('登録問題数');
        $response->assertSee('2問');
        $response->assertSee('問題を検索');
        $response->assertSee('最近追加された問題の答え');
        $response->assertStatus(200);
    }
}
