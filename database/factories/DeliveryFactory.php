<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Delivery;
use App\Models\Transaction;

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
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Delivery $delivery) {
            $delivery->transactions()->saveMany(
                Transaction::factory()->count(3)->make()
            );
        });
    }
}
