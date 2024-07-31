<?php

namespace Database\Factories;

use App\Models\BuyerEnquiryList;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuyerEnquiryListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BuyerEnquiryList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company' => $this->faker->word,
        'productType' => $this->faker->word,
        'country' => $this->faker->word,
        'designation' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
