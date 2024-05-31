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
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph,
            'photo' => '8jx6c14ZVjAPDlkAFBucLNhOQxfYiuIAuUNgOJ3j.png',
            'is_actuator' => $this->faker->boolean,
            'pros' => $this->faker->paragraph,
            'cons' => $this->faker->paragraph,
            'hardware_considerations' => $this->faker->paragraph,
            'software_considerations' => $this->faker->paragraph,
            'example_code' => $this->faker->paragraph,
            'wiring_photo' => '8jx6c14ZVjAPDlkAFBucLNhOQxfYiuIAuUNgOJ3j.png',
            'wiring_instructions' => $this->faker->paragraph,
        ];
    }
}
