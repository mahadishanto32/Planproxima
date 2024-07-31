<?php

namespace Database\Factories;

use App\Models\ProductionFeedback;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductionFeedbackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductionFeedback::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'factory_id' => $this->faker->randomDigitNotNull,
        'summary_group_id' => $this->faker->randomDigitNotNull,
        'production_type' => $this->faker->word,
        'section' => $this->faker->randomDigitNotNull,
        'section_name' => $this->faker->word,
        'comments' => $this->faker->word,
        'type' => $this->faker->word,
        'start_date' => $this->faker->word,
        'end_date' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
