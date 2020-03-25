<?php

namespace Tests\Feature;

use App\News;
use App\Services\RpcResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NewsCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function anyone_can_create_news()
    {
        $this->assertEquals(0, News::count());

        $request = [
            'jsonrpc' => '2.0',
            'method' => 'store',
            'params' => [
                'title' => 'What Has Happened?',
                'snippet' => 'Let\'s find out!',
                'full_text' => 'So, this is what\'s happened...',
            ],
            'id' => 1,
        ];

        $response = $this->postJson(route('news.crud'), $request)
            ->assertStatus(200);

        $json = $response->json();
        $this->assertNull($json['error']);

        // Assert data was created
        $this->assertEquals(1, News::count());
        $news = News::first();

        $this->assertEquals($request['params'], [
            'title' => $news['title'],
            'snippet' => $news['snippet'],
            'full_text' => $news['full_text'],
        ]);

        // Assert the response
        $this->assertEquals($request['params'], [
            'title' => $json['result']['title'],
            'snippet' => $json['result']['snippet'],
            'full_text' => $json['result']['full_text'],
        ]);
    }

    /** @test */
    public function only_specific_request_methods_are_allowed()
    {
        $request = [
            'jsonrpc' => '2.0',
            'method' => 'fake-method',
            'params' => [
                'title' => 'What Has Happened?',
            ],
            'id' => 1,
        ];

        $response = $this->postJson(route('news.crud'), $request)
            ->assertStatus(200);

        $this->assertEquals(RpcResponse::ERROR_METHOD_NOT_FOUND, $response->json()['error']);
        $this->assertNull($response->json()['result']);
    }

    /** @test */
    public function data_validation_test()
    {
        $this->assertEquals(0, News::count());

        $request = [
            'jsonrpc' => '2.0',
            'method' => 'store',
            'params' => [
                'title' => 'What Has Happened?',
                'snippet' => 'Let\'s find out!',
                // The 'full_text' field is missing
            ],
            'id' => 1,
        ];

        $response = $this->postJson(route('news.crud'), $request)
            ->assertStatus(200);

        // Assert an error
        $this->assertEquals(RpcResponse::ERROR_INVALID_PARAMS, $response->json()['error']);
        $this->assertNull($response->json()['result']);

        // Assert data was not created
        $this->assertEquals(0, News::count());
    }

    /** @test */
    public function anyone_can_get_news_by_page_uid()
    {
        $news = factory(News::class)->create();

        $request = [
            'jsonrpc' => '2.0',
            'method' => 'show',
            'params' => [
                'page_uid' => $news->page_uid,
            ],
            'id' => 1,
        ];

        $response = $this->postJson(route('news.crud'), $request)
            ->assertStatus(200);

        $json = $response->json();
        $this->assertNull($json['error']);

        // Assert correct data returned
        $this->assertEquals($news->toArray(), $json['result']);
    }

    /** @test */
    public function show_non_existing_page_uid_()
    {
        $request = [
            'jsonrpc' => '2.0',
            'method' => 'show',
            'params' => [
                'page_uid' => 'fake-page-uid',
            ],
            'id' => 1,
        ];

        $this->postJson(route('news.crud'), $request)
            ->assertStatus(404);
    }
}
