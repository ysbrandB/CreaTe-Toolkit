<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Attribute;
use App\Models\AttributeType;
use App\Models\Item;
use App\Models\Question;
use App\Models\User;
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
                'password' => Hash::make('password'),
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

        $types = AttributeType::create(
            [
                'title' => 'Type',
                'description' => 'The type an item can be',
                'color' => 'red',
            ]
        );

        Attribute::create(
            [
                'attribute_type_id' => $types->id,
                'title' => 'Sensor',
                'description' => 'A sensor is a device that detects events or changes in quantities and provides a corresponding output, generally as an electrical or optical signal',
            ]
        );

        Attribute::create(
            [
                'attribute_type_id' => $types->id,
                'title' => 'Actuator',
                'description' => 'An actuator is a component of a machine that is responsible for moving and controlling a mechanism or system',
            ]
        );

        Attribute::create(
            [
                'attribute_type_id' => $types->id,
                'title' => 'Programming Language',
                'description' => 'A programming language is a formal language comprising a set of instructions that produce various kinds of output',
            ]
        );

        Attribute::create(
            [
                'attribute_type_id' => $types->id,
                'title' => 'Development Board',
                'description' => 'A development board is a printed circuit board containing a microprocessor and the minimal support logic needed for a computer engineer to become a user of a microprocessor',
            ]
        );

        Attribute::create(
            [
                'attribute_type_id' => $types->id,
                'title' => 'Supporting technologies',
                'description' => 'All the items that are used to make the actuators and sensors work together',
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

        $python = Item::factory()->create([
            'title' => 'Python',
        ]);

        $python->attributes()->attach(
            Attribute::where('title', 'Programming Language')->first()
        );

        $items = Item::factory(10)->create();
        for ($i = 2; $i < 11 - 3; $i += 3) {
            $item = $items[$i];
            $item->json_items = [$i, $i + 1, $i + 2];
            $item->save();
        }

        foreach ($items as $item) {
            $item->attributes()->attach(
                Attribute::all()->random(3)
            );
        }

        Question::factory(5)->has(
            Answer::factory()->count(3)
        )->create();
        $attributes = Attribute::all();
        $answers = Answer::all();
        foreach ($answers as $answer) {
            $answer->attributes()->attach(
                $attributes->random(3)
            );
        }
    }
}
