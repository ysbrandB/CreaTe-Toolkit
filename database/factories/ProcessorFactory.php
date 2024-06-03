<?php

namespace Database\Factories;

use App\Models\Processor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProcessorFactory extends Factory
{
    protected $model = Processor::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'title' => $this->faker->name(),
            'item_id' => $this->faker->randomNumber(),
        ];
    }
}
