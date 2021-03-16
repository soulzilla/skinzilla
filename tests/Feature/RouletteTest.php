<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Roulette;
use Tests\TestCase;

class RouletteTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_roulette()
    {
        $this->actingAs($this->user)
            ->postJson(route('roulettes.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('roulettes', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_roulette()
    {
        $roulette = Roulette::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('roulettes.update', $roulette->id), [
                'name' => 'Updated roulette',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('roulettes', [
            'id' => $roulette->id,
            'name' => 'Updated roulette',
        ]);
    }

    /** @test */
    public function show_roulette()
    {
        $roulette = Roulette::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('roulettes.show', $roulette->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $roulette->name,
                ]
            ]);
    }

    /** @test */
    public function list_roulette()
    {
        $roulettes = Roulette::factory()->count(2)->create()->map(function ($roulette) {
            return $roulette->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('roulettes.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $roulettes->toArray()
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
    public function delete_roulette()
    {
        $roulette = Roulette::factory()->create([
            'name' => 'Roulette for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('roulettes.update', $roulette->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('roulettes', [
            'id' => $roulette->id,
            'name' => 'Roulette for delete',
        ]);
    }
}
