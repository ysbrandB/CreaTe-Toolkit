<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Item;

class ItemUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_a_new_item(): void
    {
        // Form data
        $formData = [
            'title' => 'New Title',
            'description' => 'New Description',
            'card_description' => 'New Card Description',
            'wiring_instructions' => 'New Wiring Instructions',
            'pros' => 'New Pros',
            'cons' => 'New Cons',
            'hardware_considerations' => 'New Hardware Considerations',
            'software_considerations' => 'New Software Considerations',
            'example_code' => 'New Example Code',
            'edges' => [],
            'attributes' => [],
            'photo' => '',
            'wiring_photo' => '',
        ];

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('items.store'), $formData);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(302);

        // Fetch the newly created item
        $item = Item::where('title', 'New Title')->first();

        // Assertions
        $this->assertNotNull($item);
        $this->assertEquals('New Title', $item->title);
        $this->assertEquals('New Description', $item->description);
        $this->assertEquals('New Card Description', $item->card_description);
        $this->assertEquals('New Wiring Instructions', $item->wiring_instructions);
        $this->assertEquals('New Pros', $item->pros);
        $this->assertEquals('New Cons', $item->cons);
        $this->assertEquals('New Hardware Considerations', $item->hardware_considerations);
        $this->assertEquals('New Software Considerations', $item->software_considerations);
        $this->assertEquals('New Example Code', $item->example_code);
        $this->assertEquals('', $item->wiring_photo_url);
        $this->assertEquals('', $item->photo_url);
        $this->assertEquals([], $item->attributes()->get()->toArray());
    }

    public function test_it_updates_an_existing_item(): void
    {
        // Create an item
        $item = Item::factory()->create();

        // Form data
        $formData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'card_description' => 'Updated Card Description',
            'wiring_instructions' => 'Updated Wiring Instructions',
            'pros' => 'Updated Pros',
            'cons' => 'Updated Cons',
            'hardware_considerations' => 'Updated Hardware Considerations',
            'software_considerations' => 'Updated Software Considerations',
            'example_code' => 'Updated Example Code',
            'edges' => [1, 2, 3],
            'photo' => UploadedFile::fake()->image('photo.jpg'),
            'wiring_photo' => UploadedFile::fake()->image('wiring_photo.jpg'),
        ];

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->put(route('items.update', $item->id), $formData);

        $response
            ->assertSessionHasNoErrors()
            ->assertStatus(302);

        // Fetch the updated item
        $item->refresh();

        // Assertions
        $this->assertEquals('Updated Title', $item->title);
        $this->assertEquals('Updated Description', $item->description);
        $this->assertEquals('Updated Card Description', $item->card_description);
        $this->assertEquals('Updated Wiring Instructions', $item->wiring_instructions);
        $this->assertEquals('Updated Pros', $item->pros);
        $this->assertEquals('Updated Cons', $item->cons);
        $this->assertEquals('Updated Hardware Considerations', $item->hardware_considerations);
        $this->assertEquals('Updated Software Considerations', $item->software_considerations);
        $this->assertEquals('Updated Example Code', $item->example_code);
        $this->assertNotEquals('', $item->wiring_photo_url);
        $this->assertNotEquals('', $item->photo_url);
        $this->assertEquals([1, 2, 3], $item->json_items);
    }
}
