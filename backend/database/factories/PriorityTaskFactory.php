<?php

namespace Database\Factories;

use App\Models\PriorityTask;
use Illuminate\Database\Eloquent\Factories\Factory;

class PriorityTaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PriorityTask::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dept_id' => $this->faker->randomDigitNotNull,
        'quarter_id' => $this->faker->randomDigitNotNull,
        'status' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
