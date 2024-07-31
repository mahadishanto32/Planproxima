<?php

namespace Database\Factories;

use App\Models\follow_up;
use Illuminate\Database\Eloquent\Factories\Factory;

class follow_upFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = follow_up::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date' => $this->faker->word,
        'details' => $this->faker->word,
        'dept_id' => $this->faker->word,
        'firstremind' => $this->faker->word,
        'secondremind' => $this->faker->word,
        'user_id' => $this->faker->randomDigitNotNull,
        'status' => $this->faker->randomDigitNotNull,
        'active' => $this->faker->randomDigitNotNull,
        'dmdactive' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
