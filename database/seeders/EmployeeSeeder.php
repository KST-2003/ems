<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // 5 Realistic Demo Employees
        $employees = [
            [
                'name' => 'U Kyaw Swar',
                'gender' => 'male',
                'department' => 'IT Department',
                'position' => 'Senior Developer',
                'nrc' => '12/Tamana(N)123456',
                'salary' => '1,500,000',
            ],
            [
                'name' => 'Daw May Thu',
                'gender' => 'female',
                'department' => 'HR Department',
                'position' => 'HR Manager',
                'nrc' => '12/Kamayut(N)098765',
                'salary' => '1,200,000',
            ],
            [
                'name' => 'Mg Aung Aung',
                'gender' => 'male',
                'department' => 'Sales',
                'position' => 'Sales Executive',
                'nrc' => '14/Pathein(N)112233',
                'salary' => '800,000',
            ],
            [
                'name' => 'Ma Hla Hla',
                'gender' => 'female',
                'department' => 'Finance',
                'position' => 'Accountant',
                'nrc' => '9/Mandalay(N)554433',
                'salary' => '950,000',
            ],
            [
                'name' => 'U Ba Mg',
                'gender' => 'male',
                'department' => 'Operations',
                'position' => 'Driver',
                'nrc' => '5/Sagaing(N)998877',
                'salary' => '400,000',
            ],
        ];

        foreach ($employees as $index => $emp) {
            DB::table('employees')->insert([
                'employee_id' => 'EMP-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT), // Generates EMP-001, EMP-002
                'name' => $emp['name'],
                'phone' => '09' . $faker->randomNumber(9, true),
                'gender' => $emp['gender'],
                'profile_image' => null, 
                
                // Date of Births
                'eng_dob' => $faker->date('Y-m-d', '2000-01-01'),
                'mm_dob' => null, // Optional Myanmar Date
                
                // Personal Details
                'nationality' => 'Myanmar',
                'religion' => 'Buddhism',
                'father_name' => 'U ' . $faker->firstNameMale,
                'mother_name' => 'Daw ' . $faker->firstNameFemale,
                'nrc' => $emp['nrc'],
                'blood_type' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                
                // Spouse Details (Randomly assign)
                'spouse_name' => rand(0, 1) ? $faker->name : null,
                'spouse_job' => rand(0, 1) ? 'Merchant' : null,
                'spouse_job_place' => null,
                
                // Address
                'current_address' => $faker->address,
                'permanent_address' => $faker->address,
                
                // Job Details
                'current_position' => $emp['position'],
                'salary' => $emp['salary'],
                'department' => $emp['department'],
                
                // Skills & Extras
                'lang_proficiency' => 'Burmese (Native), English (Basic)',
                'hobby' => 'Reading, Traveling',
                
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}