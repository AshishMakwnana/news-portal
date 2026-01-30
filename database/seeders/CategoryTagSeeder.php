<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tag;

class CategoryTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Politics','Business','Technology','Sports','Entertainment'];
        foreach ($categories as $c) {
            Category::firstOrCreate(['name' => $c], ['slug' => \Illuminate\Support\Str::slug($c)]);
        }

        $tags = ['Breaking','Opinion','Analysis','Interview','Featured'];
        foreach ($tags as $t) {
            Tag::firstOrCreate(['name' => $t], ['slug' => \Illuminate\Support\Str::slug($t)]);
        }
    }
}
