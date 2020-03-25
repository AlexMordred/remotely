<?php

namespace Tests\Unit;

use App\Services\RpcResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RpcResponseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_an_invalid_json_request()
    {
        $request = 'invalid-json';

        $response = $this->call('post', route('news.crud'), [], [], [], [], $request)
            ->assertStatus(200);

        $this->assertEquals(RpcResponse::ERROR_PARSE_ERROR, $response->json()['error']);
        $this->assertNull($response->json()['result']);
    }

    /** @test */
    public function test_a_malformed_request()
    {
        $request = [
            'method' => 'store',
            'id' => 1,
        ];

        $response = $this->postJson(route('news.crud'), $request)
            ->assertStatus(200);

        $this->assertEquals(RpcResponse::ERROR_INVALID_REQUEST, $response->json()['error']);
        $this->assertNull($response->json()['result']);
    }
}
