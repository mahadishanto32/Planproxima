<?php

namespace Database\Factories;

use App\Models\TourEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class TourEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TourEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomDigitNotNull,
        'point' => $this->faker->word,
        'route' => $this->faker->word,
        'objectives' => $this->faker->word,
        'issues' => $this->faker->word,
        'contactperson' => $this->faker->word,
        'hq' => $this->faker->word,
        'remarks' => $this->faker->text,
        'feedback' => $this->faker->text,
        'status' => $this->faker->randomDigitNotNull,
        'approval' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
