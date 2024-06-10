<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeType;
use App\Models\Edge;
use App\Models\Item;
use App\Models\Processor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create(
            [
                'name' => 'Admin',
                'email' => 'ysbrand@example.com',
                'password' =>  Hash::make('password'),
            ]
        );

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $processors = AttributeType::create(
            [
                'title' => 'Controllers',
                'description' => 'All the controllers you can use to interact with the sensors and actuators',
                'color' => 'blue',
            ]
        );

        Attribute::create(
            [
                'attribute_type_id' => $processors->id,
                'title' => 'Arduino',
                'description' => 'A microcontroller that can be used to interact with sensors and actuators',
            ]
        );

        Attribute::create(
            [
                'attribute_type_id' => $processors->id,
                'title' => 'ESP8266',
                'description' => 'The ESP8266 is a low-cost Wi-Fi microchip with full TCP/IP stack and microcontroller capability produced by Espressif Systems',
            ]
        );

        AttributeType::factory(10)->has(
            Attribute::factory()->count(5)
        )->create();

        $items = Item::factory(10)->create();
        $attributes = Attribute::all();
        foreach ($items as $item) {
            $item->attributes()->attach(
                $attributes->random(3)
            );
        }

        for($i = 0; $i < sizeof($items) - 1; $i++) {
            Edge::create([
                'from_item_id' => $items->get($i)->id,
                'to_item_id' => $items->get($i+1)->id,
                'belongsto_item_id' => $items->first()->id,
            ]);
        }
    }
}
