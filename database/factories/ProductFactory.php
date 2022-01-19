<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;
use App\Models\UOM;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'u_o_m_id' => UOM::factory(),
            'code' => $this->faker->unique()->ean8,
            'name' => $this->faker->unique()->word,
            'description' => $this->faker->text,
        ];
    }
}
