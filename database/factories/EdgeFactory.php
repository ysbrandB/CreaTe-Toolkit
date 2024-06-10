<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\Edge;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class EdgeFactory extends Factory
{
    protected $model = Edge::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'from_attribute_id' => Attribute::factory(),
            'to_attribute_id' => Attribute::factory(),
        ];
    }
}
