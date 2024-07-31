<?php

namespace Database\Factories;

use App\Models\KRA;
use Illuminate\Database\Eloquent\Factories\Factory;

class KRAFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = KRA::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'kra_name' => $this->faker->word,
        'dept_id' => $this->faker->randomDigitNotNull,
        'year' => $this->faker->randomDigitNotNull,
        'kra_weight' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
