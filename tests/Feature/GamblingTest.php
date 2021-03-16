<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Gambling;
use Tests\TestCase;

class GamblingTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_gambling()
    {
        $this->actingAs($this->user)
            ->postJson(route('gamblings.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('gamblings', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_gambling()
    {
        $gambling = Gambling::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('gamblings.update', $gambling->id), [
                'name' => 'Updated gambling',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('gamblings', [
            'id' => $gambling->id,
            'name' => 'Updated gambling',
        ]);
    }

    /** @test */
    public function show_gambling()
    {
        $gambling = Gambling::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('gamblings.show', $gambling->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $gambling->name,
                ]
            ]);
    }

    /** @test */
    public function list_gambling()
    {
        $gamblings = Gambling::factory()->count(2)->create()->map(function ($gambling) {
            return $gambling->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('gamblings.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $gamblings->toArray()
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
    public function delete_gambling()
    {
        $gambling = Gambling::factory()->create([
            'name' => 'Gambling for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('gamblings.update', $gambling->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('gamblings', [
            'id' => $gambling->id,
            'name' => 'Gambling for delete',
        ]);
    }
}
