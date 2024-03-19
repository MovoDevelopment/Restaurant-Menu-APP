<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $restaurant = User::query()->where('role', 'restaurant')->first();

        return [
            'user_id' => $restaurant->id,
            'name' => fake()->word(),
            'parent_id' => null,
            'path' => '',
        ];
    }
}
