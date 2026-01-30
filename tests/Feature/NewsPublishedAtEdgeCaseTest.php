<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\News;
use App\Models\User;

class NewsPublishedAtEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_renders_when_published_at_is_string()
    {
        // create published news with published_at as a string
        $news = News::create([
            'title' => 'String Date News',
            'slug' => 'string-date-news',
            'excerpt' => 'Excerpt',
            'body' => 'Body',
            'status' => 'published',
            'published_at' => now()->format('Y-m-d H:i:s'),
            'user_id' => User::factory()->create()->id,
        ]);

        $res = $this->get(route('home'));
        $res->assertStatus(200);
        $res->assertSeeText('String Date News');
        $res->assertSee(now()->format('M d, Y'));
    }

    public function test_show_renders_when_published_at_is_string()
    {
        $news = News::create([
            'title' => 'Show String Date',
            'slug' => 'show-string-date',
            'excerpt' => 'Excerpt',
            'body' => 'Body',
            'status' => 'published',
            'published_at' => now()->format('Y-m-d H:i:s'),
            'user_id' => User::factory()->create()->id,
        ]);

        $res = $this->get(route('news.show', $news->slug));
        $res->assertStatus(200);
        $res->assertSeeText('Show String Date');
        $res->assertSee(now()->format('M d, Y'));
    }
}
