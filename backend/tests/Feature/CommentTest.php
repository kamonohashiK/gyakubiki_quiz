<?php

namespace Tests\Feature;

use App\Models\Answer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_非ログイン時はコメント投稿フォームを表示しない()
    {
        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $q = $a->questions()->create(['content' => 'test', 'user_id' => 1]);

        $response = $this->get('/questions/' . $q->id);
        $response->assertSee('コメントを投稿するにはログインしてください。');
    }

    public function test_ログイン時はコメント投稿フォームを表示する()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $q = $a->questions()->create(['content' => 'test', 'user_id' => 1]);

        $response = $this->actingAs($user)
            ->get('/questions/' . $q->id);
        $response->assertDontSee('コメントを投稿するにはログインしてください。');
        $response->assertSee('コメントする');
    }

    public function test_コメントが正しく投稿される()
    {
        $user = User::factory()->create();

        $a = Answer::create(['name' => 'test', 'user_id' => 1]);
        $q = $a->questions()->create(['content' => 'test', 'user_id' => 1]);

        $response = $this->actingAs($user)
            ->post('/new-comment/' . $q->id, [
                'comment' => '新しいコメント',
            ]);
        $this->assertDatabaseHas('comments', [
            'content' => '新しいコメント',
            'user_id' => $user->id,
            'question_id' => $q->id,
        ]);
        $response->assertRedirect('/questions/' . $q->id)->assertSessionHas('success');
    }
}
