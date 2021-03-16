<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_user()
    {
        $this->actingAs($this->user)
            ->postJson(route('users.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('users', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_user()
    {
        $user = User::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('users.update', $user->id), [
                'name' => 'Updated user',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated user',
        ]);
    }

    /** @test */
    public function show_user()
    {
        $user = User::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('users.show', $user->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $user->name,
                ]
            ]);
    }

    /** @test */
    public function list_user()
    {
        $users = User::factory()->count(2)->create()->map(function ($user) {
            return $user->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('users.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $users->toArray()
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
    public function delete_user()
    {
        $user = User::factory()->create([
            'name' => 'User for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('users.update', $user->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => 'User for delete',
        ]);
    }
}
