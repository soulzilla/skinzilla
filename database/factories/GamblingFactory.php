<?php

namespace Database\Factories;

use App\Models\Gambling;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GamblingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Gambling::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
