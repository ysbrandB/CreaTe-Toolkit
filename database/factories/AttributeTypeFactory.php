<?php

namespace Database\Factories;

use App\Models\AttributeType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AttributeTypeFactory extends Factory
{
    protected $model = AttributeType::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->word(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
