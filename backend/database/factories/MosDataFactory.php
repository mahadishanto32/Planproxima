<?php

namespace Database\Factories;

use App\Models\MosData;
use Illuminate\Database\Eloquent\Factories\Factory;

class MosDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MosData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'mos_id' => $this->faker->randomDigitNotNull,
        'type' => $this->faker->randomDigitNotNull,
        'january' => $this->faker->randomDigitNotNull,
        'february' => $this->faker->randomDigitNotNull,
        'march' => $this->faker->randomDigitNotNull,
        'april' => $this->faker->randomDigitNotNull,
        'may' => $this->faker->randomDigitNotNull,
        'june' => $this->faker->randomDigitNotNull,
        'july' => $this->faker->randomDigitNotNull,
        'august' => $this->faker->randomDigitNotNull,
        'september' => $this->faker->randomDigitNotNull,
        'october' => $this->faker->randomDigitNotNull,
        'november' => $this->faker->randomDigitNotNull,
        'december' => $this->faker->randomDigitNotNull,
        'dept_id' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
