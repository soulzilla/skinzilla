<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Box;
use Tests\TestCase;

class BoxTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_box()
    {
        $this->actingAs($this->user)
            ->postJson(route('boxes.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('boxes', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_box()
    {
        $box = Box::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('boxes.update', $box->id), [
                'name' => 'Updated box',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('boxes', [
            'id' => $box->id,
            'name' => 'Updated box',
        ]);
    }

    /** @test */
    public function show_box()
    {
        $box = Box::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('boxes.show', $box->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $box->name,
                ]
            ]);
    }

    /** @test */
    public function list_box()
    {
        $boxes = Box::factory()->count(2)->create()->map(function ($box) {
            return $box->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('boxes.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $boxes->toArray()
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
    public function delete_box()
    {
        $box = Box::factory()->create([
            'name' => 'Box for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('boxes.update', $box->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('boxes', [
            'id' => $box->id,
            'name' => 'Box for delete',
        ]);
    }
}
