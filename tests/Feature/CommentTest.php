<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Comment;
use Tests\TestCase;

class CommentTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_comment()
    {
        $this->actingAs($this->user)
            ->postJson(route('comments.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('comments', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_comment()
    {
        $comment = Comment::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('comments.update', $comment->id), [
                'name' => 'Updated comment',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'name' => 'Updated comment',
        ]);
    }

    /** @test */
    public function show_comment()
    {
        $comment = Comment::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('comments.show', $comment->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $comment->name,
                ]
            ]);
    }

    /** @test */
    public function list_comment()
    {
        $comments = Comment::factory()->count(2)->create()->map(function ($comment) {
            return $comment->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('comments.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $comments->toArray()
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
    public function delete_comment()
    {
        $comment = Comment::factory()->create([
            'name' => 'Comment for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('comments.update', $comment->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
            'name' => 'Comment for delete',
        ]);
    }
}
