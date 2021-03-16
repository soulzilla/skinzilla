<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Market;
use Tests\TestCase;

class MarketTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_market()
    {
        $this->actingAs($this->user)
            ->postJson(route('markets.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('markets', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_market()
    {
        $market = Market::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('markets.update', $market->id), [
                'name' => 'Updated market',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('markets', [
            'id' => $market->id,
            'name' => 'Updated market',
        ]);
    }

    /** @test */
    public function show_market()
    {
        $market = Market::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('markets.show', $market->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $market->name,
                ]
            ]);
    }

    /** @test */
    public function list_market()
    {
        $markets = Market::factory()->count(2)->create()->map(function ($market) {
            return $market->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('markets.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $markets->toArray()
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name']
                ],
                'links',
                'meta'
            ]);
    }

    /** @test */
    public function delete_market()
    {
        $market = Market::factory()->create([
            'name' => 'Market for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('markets.update', $market->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('markets', [
            'id' => $market->id,
            'name' => 'Market for delete',
        ]);
    }
}
