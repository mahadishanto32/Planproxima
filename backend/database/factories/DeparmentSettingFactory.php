<?php

namespace Database\Factories;

use App\Models\DeparmentSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeparmentSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DeparmentSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dept_id' => $this->faker->word,
        'jan' => $this->faker->word,
        'feb' => $this->faker->word,
        'mar' => $this->faker->word,
        'apr' => $this->faker->word,
        'may' => $this->faker->word,
        'jun' => $this->faker->word,
        'jul' => $this->faker->word,
        'aug' => $this->faker->word,
        'sep' => $this->faker->word,
        'oct' => $this->faker->word,
        'nov' => $this->faker->word,
        'dec' => $this->faker->word,
        'type' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
