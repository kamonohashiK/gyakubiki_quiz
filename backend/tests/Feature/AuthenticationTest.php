<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() :void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_ログインページが正しく表示される()
    {
        $response = $this->get('/login');

        $response->assertViewIs('auth.login');
        $response->assertSee('メールアドレス');
        $response->assertSee('パスワード');
        $response->assertSee('新規登録');
        $response->assertStatus(200);
    }
}
