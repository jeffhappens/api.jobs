<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\State;
use App\Models\JobType;
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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'uuid' => Str::uuid(),
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password`
        // ]);

        $states = [
            'AL'=>'ALABAMA',
            'AK'=>'ALASKA',
            'AS'=>'AMERICAN SAMOA',
            'AZ'=>'ARIZONA',
            'AR'=>'ARKANSAS',
            'CA'=>'CALIFORNIA',
            'CO'=>'COLORADO',
            'CT'=>'CONNECTICUT',
            'DE'=>'DELAWARE',
            'DC'=>'DISTRICT OF COLUMBIA',
            'FM'=>'FEDERATED STATES OF MICRONESIA',
            'FL'=>'FLORIDA',
            'GA'=>'GEORGIA',
            'GU'=>'GUAM GU',
            'HI'=>'HAWAII',
            'ID'=>'IDAHO',
            'IL'=>'ILLINOIS',
            'IN'=>'INDIANA',
            'IA'=>'IOWA',
            'KS'=>'KANSAS',
            'KY'=>'KENTUCKY',
            'LA'=>'LOUISIANA',
            'ME'=>'MAINE',
            'MH'=>'MARSHALL ISLANDS',
            'MD'=>'MARYLAND',
            'MA'=>'MASSACHUSETTS',
            'MI'=>'MICHIGAN',
            'MN'=>'MINNESOTA',
            'MS'=>'MISSISSIPPI',
            'MO'=>'MISSOURI',
            'MT'=>'MONTANA',
            'NE'=>'NEBRASKA',
            'NV'=>'NEVADA',
            'NH'=>'NEW HAMPSHIRE',
            'NJ'=>'NEW JERSEY',
            'NM'=>'NEW MEXICO',
            'NY'=>'NEW YORK',
            'NC'=>'NORTH CAROLINA',
            'ND'=>'NORTH DAKOTA',
            'MP'=>'NORTHERN MARIANA ISLANDS',
            'OH'=>'OHIO',
            'OK'=>'OKLAHOMA',
            'OR'=>'OREGON',
            'PW'=>'PALAU',
            'PA'=>'PENNSYLVANIA',
            'PR'=>'PUERTO RICO',
            'RI'=>'RHODE ISLAND',
            'SC'=>'SOUTH CAROLINA',
            'SD'=>'SOUTH DAKOTA',
            'TN'=>'TENNESSEE',
            'TX'=>'TEXAS',
            'UT'=>'UTAH',
            'VT'=>'VERMONT',
            'VI'=>'VIRGIN ISLANDS',
            'VA'=>'VIRGINIA',
            'WA'=>'WASHINGTON',
            'WV'=>'WEST VIRGINIA',
            'WI'=>'WISCONSIN',
            'WY'=>'WYOMING',
            'AE'=>'ARMED FORCES AFRICA \ CANADA \ EUROPE \ MIDDLE EAST',
            'AA'=>'ARMED FORCES AMERICA (EXCEPT CANADA)',
            'AP'=>'ARMED FORCES PACIFIC'
        ];
        foreach($states as $key => $value) {
            State::insert([
                'full_name' => $value,
                'abbr' => $key,
            ]);
        }


        $types = [
            '1' => 'Full Time',
            '2' => 'Part Time',
            '3' => 'Contract'
        ];
        foreach($types as $key => $value) {
            JobType::insert([
                'type_id' => $key,
                'label' => $value
            ]);

        }

        $industries = [
            'Administrative & Business Operations',
            'Architecture & Engineering',
            'Cleaning & Grouds Maintenance',
            'Community & Human Services',
            'Construction & Extraction',
            'Education & Instruction',
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
            'Supply Chain & Logistics',
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

        // \App\Models\Company::factory(25)->create();
        // \App\Models\Listing::factory(50)->create();


    }
}
