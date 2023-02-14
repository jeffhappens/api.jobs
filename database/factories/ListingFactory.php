<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => fake()->company(),
            'company_id' => \App\Models\Company::all()->random()->id,
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'description' => fake()->sentence( fake()->numberBetween(50, 150) ),
            'apply_link' => fake()->url()
        ];
    }
}
