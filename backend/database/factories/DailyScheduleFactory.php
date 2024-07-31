<?php

namespace Database\Factories;

use App\Models\DailySchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyScheduleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DailySchedule::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomDigitNotNull,
        'kra_id' => $this->faker->randomDigitNotNull,
        'kpi_id' => $this->faker->randomDigitNotNull,
        'mos_id' => $this->faker->randomDigitNotNull,
        'date' => $this->faker->word,
        'start_time' => $this->faker->word,
        'end_time' => $this->faker->word,
        'task' => $this->faker->text,
        'top_priority' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
