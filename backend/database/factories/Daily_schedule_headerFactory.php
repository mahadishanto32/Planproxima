<?php

namespace Database\Factories;

use App\Models\Daily_schedule_header;
use Illuminate\Database\Eloquent\Factories\Factory;

class Daily_schedule_headerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Daily_schedule_header::class;

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
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
