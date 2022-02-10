<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'supplier_id' => Supplier::factory(),
            'dr_number' => $this->faker->randomNumber(6),
            'received_by' => User::factory(),
        ];
    }
}
