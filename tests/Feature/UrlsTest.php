<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UrlsTest extends TestCase
{
    use RefreshDatabase;

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

    public function testIndex(): void
    {
        $response = $this->get(route('index'));
        $response->assertOk();
    }

    public function testUrlStore(): void
    {
        $urlData = [
            'name' => 'https://www.yandex.ru'
        ];
        $response = $this->post(route('urls.store'), ['url' => $urlData]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('urls', $urlData);
    }

    public function testUrlStoreWithNotUniqUrl(): void
    {
        $urlData = [
            'name' => 'https://www.google.com'
        ];
        $response = $this->post(route('urls.store'), ['url' => $urlData]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('urls.show', ['id' => $this->id]));
        $this->assertDatabaseHas('urls', $urlData);
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

    public function testUrlsIndex(): void
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testUrlShow(): void
    {
        $response = $this->get(route('urls.show', ['id' => $this->id]));
        $response->assertOk();
    }

    public function testUrlShowWithInvalidId(): void
    {
        $invalidId = 999;
        $response = $this->get(route('urls.show', ['id' => $invalidId]));
        $response->assertNotFound();
    }
}
