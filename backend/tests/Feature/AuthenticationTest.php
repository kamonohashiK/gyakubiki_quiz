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

    public function test_サインアップページが正しく表示される()
    {
        $response = $this->get('/register');

        $response->assertViewIs('auth.register');
        $response->assertSee('ユーザー登録');
        $response->assertSee('ユーザー名');
        $response->assertSee('メールアドレス');
        $response->assertSee('パスワード');
        $response->assertSee('パスワード(確認)');
        $response->assertSee('新規登録');
        $response->assertStatus(200);
    }
}
