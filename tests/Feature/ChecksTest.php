<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChecksTest extends TestCase
{
    private int $id;

    public function setUp(): void
    {
        parent::setUp();
        $now = now();
        $urlData = [
            'name' => 'https://www.google.com',
            'created_at' => $now,
            'updated_at' => $now
        ];
        $this->id = DB::table('urls')->insertGetId($urlData);
    }

    public function testCheckStore(): void
    {
        $body = <<<BODY
            <h1>Test</h1>
            <meta name="keywords" content="test, phpunit">
            <meta name="description" content="hello from test"
        BODY;

        $checkData = [
            'url_id' => $this->id,
            'status_code' => '200',
            'h1' => 'Test',
            'keywords' => 'test, phpunit',
            'description' => 'hello from test'
        ];

        Http::fake(fn($request) => Http::response($body, 200));

        $response = $this->post(route('checks.store', ['id' => $this->id]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', $checkData);
    }

    public function testCheckStoreWithInvalidId(): void
    {
        $invalidId = 999;
        $response = $this->post(route('checks.store', ['id' => $invalidId]));
        $response->assertNotFound();
    }
}
