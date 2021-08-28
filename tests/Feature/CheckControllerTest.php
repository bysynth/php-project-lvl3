<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckControllerTest extends TestCase
{
    use RefreshDatabase;

    private int $id;

    public function setUp(): void
    {
        parent::setUp();
        $now = now();
        $urlData = [
            'name' => 'https://www.google.com/notexists',
            'created_at' => $now,
            'updated_at' => $now
        ];
        $this->id = DB::table('urls')->insertGetId($urlData);
    }

    public function testStore(): void
    {
        $body = <<<BODY
            <h1>Test</h1>
            <meta name="keywords" content="test, phpunit">
            <meta name="description" content="hello from test"
        BODY;

        Http::fake(fn($request) => Http::response($body, 404));

        $response = $this->post(route('checks.store', ['id' => $this->id]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', ['url_id' => $this->id, 'status_code' => 404]);
    }

    public function testStoreWithInvalidId(): void
    {
        $invalidId = 999;
        $response = $this->post(route('checks.store', ['id' => $invalidId]));
        $response->assertNotFound();
    }
}
