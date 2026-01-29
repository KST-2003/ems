<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $employees = [
            [
                'name' => 'U Kyaw Swar',
                'id' => 'EMP-001',
                'pos' => 'Senior Staff Officer',
                'dept' => 'IT Department',
                'nrc' => '12/TAMANA(N)123456',
                'dob_mm' => '၁၃၅၆ ခု၊ တန်ဆောင်မုန်းလဆန်း ၁ ရက်',
                'spouse' => 'Daw Hla Hla',
                'edu' => 'B.A (Public Administration)'
            ],
            [
                'name' => 'Daw Myint Myint Zu',
                'id' => 'EMP-002',
                'pos' => 'Assistant Director',
                'dept' => 'Administration',
                'nrc' => '9/MAHTALA(N)098765',
                'dob_mm' => '၁၃၄၅ ခု၊ ကဆုန်လပြည့်ကျော် ၅ ရက်',
                'spouse' => 'U Aung Ko',
                'edu' => 'M.A (Public Policy)'
            ],
            [
                'name' => 'U Zaw Win Tun',
                'id' => 'EMP-003',
                'pos' => 'Section Head',
                'dept' => 'Finance',
                'nrc' => '5/KABALA(N)112233',
                'dob_mm' => '၁၃၅၀ ခု၊ ဝါဆိုလဆန်း ၁၀ ရက်',
                'spouse' => 'Daw Thuzar',
                'edu' => 'B.Com'
            ],
            [
                'name' => 'Daw Phyu Phyu Thin',
                'id' => 'EMP-004',
                'pos' => 'Deputy Officer',
                'dept' => 'Human Resources',
                'nrc' => '7/THAYAWA(N)445566',
                'dob_mm' => '၁၃၆၀ ခု၊ တပို့တွဲလပြည့်နေ့',
                'spouse' => 'U Than Naing',
                'edu' => 'B.A (English)'
            ],
            [
                'name' => 'U Min Aung Htet',
                'id' => 'EMP-005',
                'pos' => 'Junior Clerk',
                'dept' => 'Operations',
                'nrc' => '13/TAYANA(N)778899',
                'dob_mm' => '၁၃၆၂ ခု၊ တန်ခူးလဆန်း ၃ ရက်',
                'spouse' => 'Daw Nu Nu',
                'edu' => 'B.Sc (Physics)'
            ],
        ];

        foreach ($employees as $emp) {
            // 1. Main Employee Table
            $eId = DB::table('employees')->insertGetId([
                'employee_id' => $emp['id'],
                'name' => $emp['name'],
                'nrc' => $emp['nrc'],
                'mm_dob' => $emp['dob_mm'],
                'eng_dob' => '1990-01-01', // Placeholder
                'current_position' => $emp['pos'],
                'department' => $emp['dept'],
                'gender' => str_contains($emp['name'], 'U ') ? 'male' : 'female',
                'nationality' => 'Myanmar',
                'religion' => 'Buddhism',
                'created_at' => now(),
            ]);

            // 2. Spouse Table (Fixed: No longer in main employees table)
            DB::table('employee_spouses')->insert([
                'employee_id' => $eId,
                'name' => $emp['spouse'],
                'job' => 'Government Staff',
                'created_at' => now(),
            ]);

            // 3. Personal Records (Template C specific)
            DB::table('employee_personal_records')->insert([
                'employee_id' => $eId,
                'hobbies' => 'Reading, Sports',
                'citizen_duties' => 'Active',
                'created_at' => now(),
            ]);

            // 4. Education Table
            DB::table('employee_education')->insert([
                'employee_id' => $eId,
                'type' => 'uni',
                'institution_name' => 'Yangon University',
                'degree_certificate' => $emp['edu'],
                'created_at' => now(),
            ]);
        }
    }
}