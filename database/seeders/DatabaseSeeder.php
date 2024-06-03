<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeType;
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

        AttributeType::factory(10)->has(
            Attribute::factory()->count(5)
        )->create();

        Item::factory()->count(10)->sequence(fn (Sequence $sequence) => ['name' => 'Name '.$sequence->index])->create();


        Processor::create([
            'name' => 'Laptop',
            'description' => 'A portable computer',
        ]);




    }
}
