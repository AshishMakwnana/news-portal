<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class MediaUploadTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function media_upload_generates_thumbnail_and_saves_record()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('photo.jpg', 600, 400);

        $response = $this->post(route('admin.media.upload'), [
            'upload' => $file,
        ]);

        $response->assertStatus(200)->assertJsonStructure(['url', 'thumbnail']);

        $filename = 'media/' . $file->hashName();
        $thumbnail = 'media/thumbnails/' . pathinfo($filename, PATHINFO_BASENAME);

        Storage::disk('public')->assertExists($filename);
        Storage::disk('public')->assertExists($thumbnail);

        $this->assertDatabaseHas('media', [
            'filename' => $filename,
            'thumbnail' => $thumbnail,
            'user_id' => $user->id,
        ]);
    }
}
