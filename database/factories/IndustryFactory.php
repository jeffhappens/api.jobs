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
        $industries = [
            'Accommodation',
            'Accounting',
            'Agriculture',
            'Business & Finance',
            'Construction',
            'Distribution', 
            'Electronics',
            'Health Care',
            'Human Resources',
            'Information Technology',
            'Insurance',
            'Law',
            'Leisure and Hospitality',
            'Real Estate',
            'Robotics',
            'Technology',
            'Sales',
            'Software',
            'Telecommunications',
            'Trade',


        ];
        $industry = array_rand($industries);
        $slug = Str::slug($industry);
        return [
            'label' => $industry,
            'slug' => $slug
        ];
    }
}
