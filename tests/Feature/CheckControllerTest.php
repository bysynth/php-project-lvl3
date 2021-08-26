<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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
            'name' => 'https://www.google.com',
            'created_at' => $now,
            'updated_at' => $now
        ];
        $this->id = DB::table('urls')->insertGetId($urlData);
    }

    public function testStore(): void
    {
        $response = $this->post(route('checks.store', ['id' => $this->id]));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('url_checks', ['url_id' => $this->id]);
    }

    public function testStoreWithInvalidId(): void
    {
        $invalidId = 999;
        $response = $this->post(route('checks.store', ['id' => $invalidId]));
        $response->assertNotFound();
    }
}
