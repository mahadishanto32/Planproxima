<?php

namespace Database\Factories;

use App\Models\UserManualFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserManualFileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserManualFile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_manual_id' => $this->faker->randomDigitNotNull,
        'file_name' => $this->faker->word,
        'order_by' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
