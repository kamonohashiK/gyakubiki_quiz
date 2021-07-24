<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Answer;
use App\Models\User;

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
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $a = Answer::create(['name' => 'test', 'user_id' => $user1->id]);
        $a->questions()->createMany([
            ['content' => 'test', 'user_id' => $user1->id],
            ['content' => 'test', 'user_id' => $user2->id],
        ]);

        $response = $this->get('/');

        $response->assertViewIs('top');
        $response->assertSeeText('クイズ逆引き事典');
        $response->assertSee('登録問題数');
        $response->assertSee('2問');
        $response->assertSee('検索ワード');
        $response->assertSee('最近追加された問題');
        $response->assertStatus(200);
    }
}
