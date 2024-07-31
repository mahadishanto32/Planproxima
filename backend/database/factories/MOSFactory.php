<?php

namespace Database\Factories;

use App\Models\MOS;
use Illuminate\Database\Eloquent\Factories\Factory;

class MOSFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MOS::class;

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
        'kpi_id' => $this->faker->randomDigitNotNull,
        'mos_name' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
