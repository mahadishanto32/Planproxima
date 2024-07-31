<?php

namespace Database\Factories;

use App\Models\DailyScheduleItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyScheduleItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DailyScheduleItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'daily_schedules_id' => $this->faker->randomDigitNotNull,
        'schedule_type_id' => $this->faker->randomDigitNotNull,
        'schedule_details' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
