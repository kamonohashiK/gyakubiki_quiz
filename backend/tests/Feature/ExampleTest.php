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
        $response->assertStatus(200);
    }
}
