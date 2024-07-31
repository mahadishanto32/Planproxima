<?php

namespace Database\Factories;

use App\Models\MosDataLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class MosDataLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MosDataLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'mos_data_id' => $this->faker->randomDigitNotNull,
        'type' => $this->faker->word,
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
        'year' => $this->faker->word,
        'total' => $this->faker->randomDigitNotNull,
        'insert_type' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
