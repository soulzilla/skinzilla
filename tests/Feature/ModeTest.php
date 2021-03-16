<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mode;
use Tests\TestCase;

class ModeTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_mode()
    {
        $this->actingAs($this->user)
            ->postJson(route('modes.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('modes', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_mode()
    {
        $mode = Mode::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('modes.update', $mode->id), [
                'name' => 'Updated mode',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('modes', [
            'id' => $mode->id,
            'name' => 'Updated mode',
        ]);
    }

    /** @test */
    public function show_mode()
    {
        $mode = Mode::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('modes.show', $mode->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $mode->name,
                ]
            ]);
    }

    /** @test */
    public function list_mode()
    {
        $modes = Mode::factory()->count(2)->create()->map(function ($mode) {
            return $mode->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('modes.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $modes->toArray()
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
    public function delete_mode()
    {
        $mode = Mode::factory()->create([
            'name' => 'Mode for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('modes.update', $mode->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('modes', [
            'id' => $mode->id,
            'name' => 'Mode for delete',
        ]);
    }
}
