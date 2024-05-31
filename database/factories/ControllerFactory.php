<?php

namespace Database\Factories;

use App\Models\Controller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ControllerFactory extends Factory
{
    protected $model = Controller::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'Name' => $this->faker->name(),
            'item_id' => $this->faker->randomNumber(),
        ];
    }
}
