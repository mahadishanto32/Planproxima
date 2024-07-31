<?php

namespace Database\Factories;

use App\Models\CostSummaryGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class CostSummaryGroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CostSummaryGroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group_name' => $this->faker->word,
        'summary_group_id' => $this->faker->randomDigitNotNull,
        'plant_id' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
