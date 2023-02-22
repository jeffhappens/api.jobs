<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Industry>
 */
class IndustryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $industries = ['Accommodation', 'Agriculture', 'Business & Finance', 'Construction', 'Health Care', 'Information Technology', 'Leisure and Hospitality', 'Telecommunications'];
        $industry = array_rand($industries);
        $slug = Str::slug($industry);
        return [
            'label' => $industry,
            'slug' => $slug
        ];
    }
}
