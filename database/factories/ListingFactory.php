<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
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
        $title = fake()->jobTitle();
        $slug = Str::slug($title);

        return [
            'uuid' => Str::uuid(),
            'author_uuid' => \App\Models\User::all()->random()->uuid,
            'title' => $title,
            'slug' => $slug,
            'type_id' => 1,
            'company_id' => \App\Models\Company::all()->random()->id,
            'industry_id' => \App\Models\Industry::all()->random()->id,
            'description' => fake()->sentence( fake()->numberBetween(50, 150) ),
            'apply_link' => fake()->url(),
            'expires_at' => Carbon::now()->addMonth()
        ];
    }
}
