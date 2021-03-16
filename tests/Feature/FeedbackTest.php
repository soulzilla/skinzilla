<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Feedback;
use Tests\TestCase;

class FeedbackTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_feedback()
    {
        $this->actingAs($this->user)
            ->postJson(route('feedback.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('feedback', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_feedback()
    {
        $feedback = Feedback::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('feedback.update', $feedback->id), [
                'name' => 'Updated feedback',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('feedback', [
            'id' => $feedback->id,
            'name' => 'Updated feedback',
        ]);
    }

    /** @test */
    public function show_feedback()
    {
        $feedback = Feedback::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('feedback.show', $feedback->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $feedback->name,
                ]
            ]);
    }

    /** @test */
    public function list_feedback()
    {
        $feedback = Feedback::factory()->count(2)->create()->map(function ($feedback) {
            return $feedback->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('feedback.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $feedback->toArray()
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
    public function delete_feedback()
    {
        $feedback = Feedback::factory()->create([
            'name' => 'Feedback for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('feedback.update', $feedback->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('feedback', [
            'id' => $feedback->id,
            'name' => 'Feedback for delete',
        ]);
    }
}
