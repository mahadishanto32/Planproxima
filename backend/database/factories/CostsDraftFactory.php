<?php

namespace Database\Factories;

use App\Models\CostsDraft;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostsDraftFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CostsDraft::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'factory_code' => $this->faker->word,
        'cost' => $this->faker->word,
        'remarks' => $this->faker->word,
        'cost_center' => $this->faker->word,
        'error_note' => $this->faker->word,
        'gl_code' => $this->faker->word,
        'data' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
