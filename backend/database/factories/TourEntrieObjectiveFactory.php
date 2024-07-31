<?php

namespace Database\Factories;

use App\Models\TourEntrieObjective;
use Illuminate\Database\Eloquent\Factories\Factory;

class TourEntrieObjectiveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TourEntrieObjective::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tour_entrie_id' => $this->faker->randomDigitNotNull,
        'objective' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
