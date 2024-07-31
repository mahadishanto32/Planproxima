<?php

namespace Database\Factories;

use App\Models\monthly_report_file;
use Illuminate\Database\Eloquent\Factories\Factory;

class monthly_report_fileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = monthly_report_file::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'report_id' => $this->faker->randomDigitNotNull,
        'file_name' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
