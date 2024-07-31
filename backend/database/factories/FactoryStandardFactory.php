<?php

namespace Database\Factories;

use App\Models\FactoryStandard;
use Illuminate\Database\Eloquent\Factories\Factory;

class FactoryStandardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FactoryStandard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'year' => $this->faker->word,
        'type' => $this->faker->word,
        'cost_center' => $this->faker->word,
        'gl_code' => $this->faker->word,
        'gl_text' => $this->faker->word,
        'cost_amount' => $this->faker->word,
        'cost_center_id' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
