<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'invoice' => $this->faker->randomNumber(6),
            'or_number' => $this->faker->randomNumber(6),
            'amount' => $this->faker->randomFloat(2, 0.3, 100),
        ];
    }
}
