<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Tag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'excerpt' => $this->faker->paragraph(),
            'body' => $this->faker->paragraphs(5, true),
            'status' => $this->faker->randomElement(['draft', 'published']),
            'published_at' => now()->subDays(rand(0, 10)),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'language' => 'en',
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory()->create()->id,
        ];
    }
}
