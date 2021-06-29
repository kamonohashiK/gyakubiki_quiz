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
        $response = $this->get('/questions');

        $response->assertViewIs('questions');
        $response->assertSee('questions');
        $response->assertStatus(200);
    }
}
