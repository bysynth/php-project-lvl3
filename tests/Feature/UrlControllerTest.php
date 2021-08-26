<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class UrlControllerTest extends TestCase
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

    public function testCreate(): void
    {
        $response = $this->get(route('urls.create'));
        $response->assertOk();
    }

    public function testStore(): void
    {
        $url = 'https://www.yandex.ru';
        $response = $this->post(route('urls.store'), ['url'=> ['name' => $url]]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseCount('urls', 2);
    }

    public function testStoreWithInvalidUrl(): void
    {
        $url = 'invalid.url';
        $response = $this->post(route('urls.store'), ['url'=> ['name' => $url]]);
        $response->assertSessionHasErrors('url.name');
        $response->assertSessionHasInput('url.name');
        $response->assertRedirect();
    }

    public function testStoreWithNotUniqUrl(): void
    {
        $url = 'https://www.google.com';
        $response = $this->post(route('urls.store'), ['url'=> ['name' => $url]]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseCount('urls', 1);
    }

    public function testIndex(): void
    {
        $response = $this->get(route('urls.index'));
        $response->assertOk();
    }

    public function testShow(): void
    {
        $response = $this->get(route('urls.show', ['id' => $this->id]));
        $response->assertOk();
    }

    public function testShowWithInvalidId(): void
    {
        $invalidId = 999;
        $response = $this->get(route('urls.show', ['id' => $invalidId]));
        $response->assertNotFound();
    }
}
