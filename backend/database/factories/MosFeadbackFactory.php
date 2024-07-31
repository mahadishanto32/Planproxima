<?php

namespace Database\Factories;

use App\Models\MosFeadback;
use Illuminate\Database\Eloquent\Factories\Factory;

class MosFeadbackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MosFeadback::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'mos_id' => $this->faker->word,
        'user_id' => $this->faker->word,
        'msg' => $this->faker->word,
        'month' => $this->faker->word,
        'status' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
