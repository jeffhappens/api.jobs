<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    public $industry = ['Business/Finance','Agriculture', 'Information Technology','Software Development','Hospitality','Recreation','Automotive'];
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->company(),
            'user_id' => \App\Models\User::all()->random()->uuid,
            'industry_id' => \App\Models\Industry::all()->random()->id,
            'address' => fake()->address(),
            'logo' => 'placeholder.png'
        ];
    }
}
