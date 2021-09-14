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
        $body = file_get_contents(__DIR__ . '/../fixtures/test.html');

        if ($body === false) {
            throw new \Exception('Ошибка обработки файла фикстуры');
        }

        Http::fake(fn($request) => Http::response($body, 200));

        $response = $this->post(route('urls.checks.store', ['id' => $this->id]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', ['url_id' => $this->id, 'status_code' => 200]);
    }

    public function testCheckStoreWithInvalidId(): void
    {
        $invalidId = 999;
        $response = $this->post(route('urls.checks.store', ['id' => $invalidId]));
        $response->assertNotFound();
    }
}
