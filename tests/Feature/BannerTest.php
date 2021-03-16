<?php

namespace Tests\Feature;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Banner;
use Tests\TestCase;

class BannerTest extends TestCase
{
    /** @var User */
    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function create_banner()
    {
        $this->actingAs($this->user)
            ->postJson(route('banners.store'), [
                'name' => 'Lorem'
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('banners', [
            'name' => 'Lorem',
        ]);
    }

    /** @test */
    public function update_banner()
    {
        $banner = Banner::factory()->create();

        $this->actingAs($this->user)
            ->putJson(route('banners.update', $banner->id), [
                'name' => 'Updated banner',
            ])
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseHas('banners', [
            'id' => $banner->id,
            'name' => 'Updated banner',
        ]);
    }

    /** @test */
    public function show_banner()
    {
        $banner = Banner::factory()->create();

        $this->actingAs($this->user)
            ->getJson(route('banners.show', $banner->id))
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    'name' => $banner->name,
                ]
            ]);
    }

    /** @test */
    public function list_banner()
    {
        $banners = Banner::factory()->count(2)->create()->map(function ($banner) {
            return $banner->only(['id', 'name']);
        });

        $this->actingAs($this->user)
            ->getJson(route('banners.index'))
            ->assertSuccessful()
            ->assertJson([
                 'data' => $banners->toArray()
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
    public function delete_banner()
    {
        $banner = Banner::factory()->create([
            'name' => 'Banner for delete',
        ]);

        $this->actingAs($this->user)
            ->deleteJson(route('banners.update', $banner->id))
            ->assertSuccessful()
            ->assertJson(['type' => Controller::RESPONSE_TYPE_SUCCESS]);

        $this->assertDatabaseMissing('banners', [
            'id' => $banner->id,
            'name' => 'Banner for delete',
        ]);
    }
}
