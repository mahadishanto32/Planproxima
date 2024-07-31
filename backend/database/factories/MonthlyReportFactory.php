<?php

namespace Database\Factories;

use App\Models\MonthlyReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class MonthlyReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MonthlyReport::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dept_id' => $this->faker->randomDigitNotNull,
        'task_name' => $this->faker->word,
        'monthly_work' => $this->faker->text,
        'topforcurrentmonth' => $this->faker->text,
        'valueadd' => $this->faker->text,
        'reason' => $this->faker->text,
        'month' => $this->faker->word,
        'year' => $this->faker->word,
        'date' => $this->faker->word,
        'attach1' => $this->faker->word,
        'attach2' => $this->faker->word,
        'attach3' => $this->faker->word,
        'attach4' => $this->faker->word,
        'attach5' => $this->faker->word,
        'attach6' => $this->faker->word,
        'attach7' => $this->faker->word,
        'attach8' => $this->faker->word,
        'attach9' => $this->faker->word,
        'attach10' => $this->faker->word,
        'worktype' => $this->faker->randomDigitNotNull,
        'user_id' => $this->faker->randomDigitNotNull,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
