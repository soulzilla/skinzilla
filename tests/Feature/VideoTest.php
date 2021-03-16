<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Video;
use Tests\TestCase;

class VideoTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_video()
    {
        $this->actingAs($this->user)
            ->postJson(route('videos.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('videos', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_video()
    {
        $video = Video::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('videos.update', $video->id), [
                'name' => 'Updated video',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'name' => 'Updated video',
        ]);
    }

    /** @test */
    public function show_video()
    {
        $video = Video::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('videos.show', $video->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $video->name,
                ]
            ]);
    }

    /** @test */
    public function list_video()
    {
        $videos = Video::factory()->count(2)->create()->map(function ($video) {
            return $video->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('videos.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $videos->toArray()
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
    public function delete_video()
    {
        $video = Video::factory()->create([
            'name' => 'Video for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('videos.update', $video->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('videos', [
            'id' => $video->id,
            'name' => 'Video for delete',
        ]);
    }
}
