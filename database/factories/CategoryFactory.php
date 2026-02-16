<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * The name of the model that this factory creates.
     *
     * @var class-string<\App\Models\Category>
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_name' => fake()->words(2, true),
            'category_description' => fake()->optional()->sentence(12),
            'category_image' => fake()->optional()->imageUrl(640, 480, 'categories', true),
            'category_status' => fake()->boolean(80),
            // If you want non-current timestamps, add them explicitly:
            // 'created_at' => fake()->dateTimeBetween('-30 days', '-1 days'),
            // 'updated_at' => fake()->dateTimeBetween('-1 days', 'now'),
       ];
    }
}
