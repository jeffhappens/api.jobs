<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            'uuid' => Str::uuid(),
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password`
        ]);

        $industries = [
            'Administrative & Business Operations',
            'Architecture & Engineering',
            'Cleaning & Grouds Maintenance',
            'Community & Human Services',
            'Construction & Extraction',
            'Education & Intruction',
            'Farming, Fishing & Forestry', 
            'Finance & Accounting',
            'Food & Beverage',
            'Healthcare',
            'Legal',
            'Manufacturing & Utilities',
            'Marketing, Advertising & Public Relations',
            'Media, Arts & Design',
            'Military & Intelligence',
            'Personal Service',
            'Protective & Security',
            'Repair, Maintenance & Installation',
            'Sales, Retail & Customer Support',
            'Science & Research',
            'Supply CHain & Logistics',
            'Technology',
            'Transportation',
            'Travel, Attractions & Events',
        ];
        foreach($industries as $key => $value) {
            \App\Models\Industry::insert([
                'label' => $value,
                'slug' => Str::slug($value),
                'created_at' => now(),
                'updated_at' => now()

            ]);
        }

        \App\Models\Company::factory(25)->create();
        \App\Models\Listing::factory(50)->create();


    }
}
