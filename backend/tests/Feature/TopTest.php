<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

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
        $response = $this->get('/');

        $response->assertViewIs('top');
        $response->assertSee('クイズ逆引き事典');
        $response->assertSee('問題を検索');
        $response->assertSee('最近追加された問題の答え');
        $response->assertStatus(200);
    }
}
