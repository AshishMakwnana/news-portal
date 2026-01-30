<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\Tag;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $news = News::factory()->count(10)->create();

        // Attach tags randomly
        $tags = Tag::all();
        if ($tags->count()) {
            foreach ($news as $n) {
                $n->tags()->attach($tags->random(rand(0,3))->pluck('id')->toArray());
            }
        }
    }
}
