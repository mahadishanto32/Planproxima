<?php

namespace Database\Factories;

use App\Models\buyerEnquiryColumn;
use Illuminate\Database\Eloquent\Factories\Factory;

class buyerEnquiryColumnFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = buyerEnquiryColumn::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'buyer_enquiry_id' => $this->faker->word,
        'column_name' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
