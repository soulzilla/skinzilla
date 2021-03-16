<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Skin;
use Tests\TestCase;

class SkinTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_skin()
    {
        $this->actingAs($this->user)
            ->postJson(route('skins.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('skins', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_skin()
    {
        $skin = Skin::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('skins.update', $skin->id), [
                'name' => 'Updated skin',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('skins', [
            'id' => $skin->id,
            'name' => 'Updated skin',
        ]);
    }

    /** @test */
    public function show_skin()
    {
        $skin = Skin::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('skins.show', $skin->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $skin->name,
                ]
            ]);
    }

    /** @test */
    public function list_skin()
    {
        $skins = Skin::factory()->count(2)->create()->map(function ($skin) {
            return $skin->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('skins.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $skins->toArray()
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
    public function delete_skin()
    {
        $skin = Skin::factory()->create([
            'name' => 'Skin for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('skins.update', $skin->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('skins', [
            'id' => $skin->id,
            'name' => 'Skin for delete',
        ]);
    }
}
