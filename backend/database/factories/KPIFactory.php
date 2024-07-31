<?php

namespace Database\Factories;

use App\Models\KPI;
use Illuminate\Database\Eloquent\Factories\Factory;

class KPIFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = KPI::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dept_id' => $this->faker->randomDigitNotNull,
        'kra_id' => $this->faker->randomDigitNotNull,
        'kpi_name' => $this->faker->word,
        'kpi_weight' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
