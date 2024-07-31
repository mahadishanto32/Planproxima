<?php

namespace Database\Factories;

use App\Models\FactoryCapacity;
use Illuminate\Database\Eloquent\Factories\Factory;

class FactoryCapacityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FactoryCapacity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'jan' => $this->faker->word,
        'feb' => $this->faker->word,
        'mar' => $this->faker->word,
        'apr' => $this->faker->word,
        'may' => $this->faker->word,
        'jun' => $this->faker->word,
        'jul' => $this->faker->word,
        'aug' => $this->faker->word,
        'sep' => $this->faker->word,
        'oct' => $this->faker->word,
        'nov' => $this->faker->word,
        'dec' => $this->faker->word,
        'summary_group_id' => $this->faker->randomDigitNotNull,
        'year' => $this->faker->word,
        'type' => $this->faker->word,
        'created_by' => $this->faker->randomDigitNotNull,
        'updated_by' => $this->faker->randomDigitNotNull,
        'total_capacity' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
