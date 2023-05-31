<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' =>  $this->faker->name(),
            'price' =>  $this->faker->numberBetween($min = 150, $max = 100),
            'description' =>  $this->faker->paragraph(),
            'item_quantity' =>  $this->faker->numberBetween($min = 2, $max = 5),
            'image' =>  $this->faker->imageUrl($width = 200, $height = 200)
        ];
    }
}
