<?php

namespace Database\Factories;

use App\Models\DailyScheduleHeader;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyScheduleHeaderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DailyScheduleHeader::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'headname' => $this->faker->word,
        'dept_id' => $this->faker->randomDigitNotNull,
        'active' => $this->faker->randomDigitNotNull,
        'serialno' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
