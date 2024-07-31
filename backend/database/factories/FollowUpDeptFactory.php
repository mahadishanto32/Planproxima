<?php

namespace Database\Factories;

use App\Models\FollowUpDept;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowUpDeptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FollowUpDept::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dept_id' => $this->faker->randomDigitNotNull,
        'activity_id' => $this->faker->randomDigitNotNull,
        'users' => $this->faker->word,
        'users_id' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
