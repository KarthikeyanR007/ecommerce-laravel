<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_name' => $this->faker->words(3, true),
            'product_description' => $this->faker->paragraph(),
            'product_price' => $this->faker->randomFloat(2, 100, 10000),
            'product_discount' => $this->faker->numberBetween(0, 50), // %
            'product_stock' => $this->faker->numberBetween(0, 200),
            'product_image' => $this->faker->imageUrl(640, 480, 'products', true),
            'product_status' => $this->faker->randomElement([0, 1]), // 1 = active, 0 = inactive
            'category_id' => \App\Models\Category::factory(), // auto creates category
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
