<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Media;

class MediaListTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_list_returns_paginated_data()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        // create 25 media files
        for ($i = 1; $i <= 25; $i++) {
            $filename = "media/file{$i}.jpg";
            $thumb = "media/thumbnails/file{$i}.jpg";
            Storage::disk('public')->put($filename, 'content-' . $i);
            Storage::disk('public')->put($thumb, 'thumb-' . $i);

            Media::create([
                'user_id' => $user->id,
                'filename' => $filename,
                'thumbnail' => $thumb,
                'disk' => 'public',
                'mime_type' => 'image/jpeg',
                'size' => 123,
            ]);
        }

        $res = $this->getJson(route('admin.media.list'));

        $res->assertStatus(200)
            ->assertJsonStructure(['data', 'current_page', 'last_page', 'per_page', 'total']);

        $this->assertCount(20, $res->json('data'));
        $this->assertEquals(1, $res->json('current_page'));
        $this->assertEquals(2, $res->json('last_page'));
        $this->assertEquals(20, $res->json('per_page'));
        $this->assertEquals(25, $res->json('total'));

        $first = $res->json('data.0');
        $this->assertArrayHasKey('id', $first);
        $this->assertArrayHasKey('url', $first);
        $this->assertArrayHasKey('thumbnail', $first);
        $this->assertArrayHasKey('alt', $first);
        $this->assertNotEmpty($first['url']);
        $this->assertNotEmpty($first['thumbnail']);
    }

    public function test_media_list_requires_authentication()
    {
        // not authenticated
        $res = $this->get(route('admin.media.list'));
        $res->assertRedirect(route('login'));
    }
}
