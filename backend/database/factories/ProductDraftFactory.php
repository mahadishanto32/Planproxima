<?php

namespace Database\Factories;

use App\Models\ProductDraft;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductDraftFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductDraft::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'plant' => $this->faker->randomDigitNotNull,
        'product_group' => $this->faker->word,
        'wastage_group' => $this->faker->word,
        'material_code' => $this->faker->word,
        'description' => $this->faker->word,
        'material_group' => $this->faker->word,
        'material_type' => $this->faker->word,
        'base_unit_of_measure' => $this->faker->word,
        'product_type' => $this->faker->word,
        'error_note' => $this->faker->text,
        'status' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
