<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PageLoadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     */
    public function test_unauthorized_dashboard(): void
    {
        $testResponse = $this->get('/');

        $testResponse->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_authorized_dashboard(): void
    {
        $user = User::factory()->create();
        $testResponse = $this->actingAs($user)->get('/');

        $testResponse->assertStatus(200);
    }

    /**
     * A basic feature test example.
     */
    public function test_unauthorized_dashboard_with_initial_selected(): void
    {
        $items = Item::factory()->count(10)->create();
        $user = User::factory()->create();
        $testResponse = $this->withSession(['selected' => [1, 2]])->actingAs($user)->get('/');
        $testResponse->assertStatus(200);
        $testResponse->assertSee($items->pluck('title')->toArray());
    }

    /**
     * A basic feature test example.
     */
    public function test_authorized_dashboard_with_initial_selected(): void
    {
        $items = Item::factory()->count(10)->create();
        $user = User::factory()->create();
        $testResponse = $this->withSession(['selected' => [2, 3]])->actingAs($user)->get('/');
        $testResponse->assertStatus(200);
        $testResponse->assertSee([$items[1]->title, $items[2]->title]);
    }
}
