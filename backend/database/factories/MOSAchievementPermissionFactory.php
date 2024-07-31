<?php

namespace Database\Factories;

use App\Models\MOSAchievementPermission;
use Illuminate\Database\Eloquent\Factories\Factory;

class MOSAchievementPermissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MOSAchievementPermission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->randomDigitNotNull,
        'role_id' => $this->faker->randomDigitNotNull,
        'mos_id' => $this->faker->randomDigitNotNull,
        'jan' => $this->faker->randomDigitNotNull,
        'feb' => $this->faker->randomDigitNotNull,
        'mar' => $this->faker->randomDigitNotNull,
        'apr' => $this->faker->randomDigitNotNull,
        'may' => $this->faker->randomDigitNotNull,
        'jun' => $this->faker->randomDigitNotNull,
        'jul' => $this->faker->randomDigitNotNull,
        'aug' => $this->faker->randomDigitNotNull,
        'sep' => $this->faker->randomDigitNotNull,
        'oct' => $this->faker->randomDigitNotNull,
        'nov' => $this->faker->randomDigitNotNull,
        'dec' => $this->faker->randomDigitNotNull,
        'dept_id' => $this->faker->randomDigitNotNull,
        'year' => $this->faker->randomDigitNotNull,
        'type' => $this->faker->word,
        'start_date' => $this->faker->word,
        'end_date' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
