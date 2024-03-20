<?php

namespace Database\Factories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Discount::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month'); // Generate random start date within the next month
        $endDate = $this->faker->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' 23:59:59'); // Generate random end date on the same day as start date

        return [
            'value' => fake()->randomFloat(2, 0, 500),
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
