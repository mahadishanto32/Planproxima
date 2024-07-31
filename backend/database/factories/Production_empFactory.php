<?php

namespace Database\Factories;

use App\Models\Production_emp;
use Illuminate\Database\Eloquent\Factories\Factory;

class Production_empFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Production_emp::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'factory_id' => $this->faker->randomDigitNotNull,
        'product_id' => $this->faker->randomDigitNotNull,
        'week' => $this->faker->randomDigitNotNull,
        'month' => $this->faker->randomDigitNotNull,
        'year' => $this->faker->randomDigitNotNull,
        'number_of_join' => $this->faker->randomDigitNotNull,
        'number_of_resig' => $this->faker->randomDigitNotNull,
        'begining_emp' => $this->faker->randomDigitNotNull,
        'ending_emp' => $this->faker->randomDigitNotNull,
        'remarks' => $this->faker->word,
        'user_id' => $this->faker->randomDigitNotNull,
        'active' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
