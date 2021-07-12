<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Question;
use App\Models\Answer;

class AnswerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_LIKE検索した答えが返ってくる()
    {
        Answer::insert([
            ['name' => 'アナゴ', 'user_id' => 1],
            ['name' => 'アナと雪の女王', 'user_id' => 1],
            ['name' => 'ミッキーマウス', 'user_id' => 1],
        ]);
        
        $this->assertEquals(Answer::likeSearch('アナ')->count(), 2);
        $this->assertEquals(Answer::likeSearch('マウス')->count(), 1);
        $this->assertEquals(Answer::likeSearch('ドナルド')->count(), 0);
    }
}
