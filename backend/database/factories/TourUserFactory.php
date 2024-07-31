<?php

namespace Database\Factories;

use App\Models\TourUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class TourUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TourUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomDigitNotNull,
        'employee_id' => $this->faker->randomDigitNotNull,
        'designation' => $this->faker->word,
        'business_type' => $this->faker->randomDigitNotNull,
        'head_of_sales' => $this->faker->randomDigitNotNull,
        'division_head' => $this->faker->randomDigitNotNull,
        'sm' => $this->faker->randomDigitNotNull,
        'dsm' => $this->faker->randomDigitNotNull,
        'asm' => $this->faker->randomDigitNotNull,
        'adsm' => $this->faker->randomDigitNotNull,
        'rsm' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
