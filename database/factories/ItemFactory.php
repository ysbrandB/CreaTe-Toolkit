<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
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
            'title' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'card_description' => $this->faker->text,
            'photo' => 'Af2XzLDwCqixQw8LcnJm5mATRiBHkXIJCVVdnGYf.jpg',
            'is_actuator' => $this->faker->boolean,
            'pros' => $this->faker->paragraph,
            'cons' => $this->faker->paragraph,
            'hardware_considerations' => $this->faker->paragraph,
            'software_considerations' => $this->faker->paragraph,
            'example_code' => $this->faker->paragraph,
            'wiring_photo' => 'Af2XzLDwCqixQw8LcnJm5mATRiBHkXIJCVVdnGYf.jpg',
            'wiring_instructions' => $this->faker->paragraph,
            'json_items' => json_encode([1, 2, 3, 4, 5]),
        ];
    }
}
