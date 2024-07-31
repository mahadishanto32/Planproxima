<?php

namespace Database\Factories;

use App\Models\Wing;
use Illuminate\Database\Eloquent\Factories\Factory;

class WingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dept_id' => $this->faker->randomDigitNotNull,
        'wing_title' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
