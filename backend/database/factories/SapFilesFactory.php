<?php

namespace Database\Factories;

use App\Models\SapFiles;
use Illuminate\Database\Eloquent\Factories\Factory;

class SapFilesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SapFiles::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'file_name' => $this->faker->word,
        'comp_code' => $this->faker->word,
        'note' => $this->faker->text,
        'date' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
