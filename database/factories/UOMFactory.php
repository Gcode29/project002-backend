<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UOMFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'short_name' => $this->faker->word,
            'long_name' => $this->faker->word,
        ];
    }
}
