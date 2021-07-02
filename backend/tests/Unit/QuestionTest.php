<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Question;
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

    public function test_現在登録されている問題数を返す()
    {
        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $a->questions()->createMany([
            ['content' => 'test', 'user_id' => 1],
            ['content' => 'test', 'user_id' => 2],
        ]);
        $this->assertSame(Question::countQuestions(), 2);
    }
}
