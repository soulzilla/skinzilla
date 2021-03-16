<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Review;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_review()
    {
        $this->actingAs($this->user)
            ->postJson(route('reviews.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('reviews', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_review()
    {
        $review = Review::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('reviews.update', $review->id), [
                'name' => 'Updated review',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'name' => 'Updated review',
        ]);
    }

    /** @test */
    public function show_review()
    {
        $review = Review::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('reviews.show', $review->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $review->name,
                ]
            ]);
    }

    /** @test */
    public function list_review()
    {
        $reviews = Review::factory()->count(2)->create()->map(function ($review) {
            return $review->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('reviews.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $reviews->toArray()
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
    public function delete_review()
    {
        $review = Review::factory()->create([
            'name' => 'Review for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('reviews.update', $review->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('reviews', [
            'id' => $review->id,
            'name' => 'Review for delete',
        ]);
    }
}
