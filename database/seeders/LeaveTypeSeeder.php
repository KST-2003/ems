<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveTypeSeeder extends Seeder
{
    public function run()
    {
        $leaves = [
            [
                'name' => 'Compassionate Leave (ရှောင်တခင် ခွင့်)',
                'default_days' => 10,
                'description' => 'For family or personal emergencies (maximum 10 days per month)',
            ],
            [
                'name' => 'Service Leave (လုပ်သက်ခွင့်)',
                'default_days' => null,
                'description' => 'Leave granted based on years of service',
            ],
            [
                'name' => 'Sick Leave (ဆေးလက်မှတ်ခွင့်)',
                'default_days' => null,
                'description' => 'Requires medical certificate from registered doctor',
            ],
            [
                'name' => 'Unpaid Leave (လစာမဲ့ခွင့်)',
                'default_days' => null,
                'description' => 'Leave without salary payment',
            ],
            [
                'name' => 'Maternity Leave (မီးဖွားခွင့်)',
                'default_days' => 180,
                'description' => 'For female employees before and after childbirth (6 months)',
            ],
            [
                'name' => 'Quarantine Leave (ကူးစက်ရောဂါကာကွယ်ခွင့်)',
                'default_days' => 30,
                'description' => 'For isolation due to infectious diseases',
            ],
            [
                'name' => 'Annual Leave (အားလပ်ရက်ရှည်ခံစားခွင့်)',
                'default_days' => null,
                'description' => 'Paid vacation leave based on company policy',
            ],
            [
                'name' => 'Leave for Ill Retiree Care (နာမကျန်းပင်စင်ဝန်ထမ်း၏ခံစားခွင့်)',
                'default_days' => null,
                'description' => 'To care for an ill retired family member',
            ],
            [
                'name' => 'Special Disability Leave (အထူးမသန်စွမ်းခွင့်)',
                'default_days' => null,
                'description' => 'For employees with special disabilities',
            ],
            [
                'name' => 'Hospitalization Leave (ဆေးရုံခွင့်)',
                'default_days' => null,
                'description' => 'For extended medical treatment requiring hospitalization',
            ],
            [
                'name' => 'Seafarer\'s Sick Leave (သင်္ဘောသားနာမကျန်းခွင့်)',
                'default_days' => null,
                'description' => 'Special sick leave for seafaring employees',
            ],
            [
                'name' => 'Study Leave (ပညာလေ့လာဆည်းပူးခွင့်)',
                'default_days' => null,
                'description' => 'For educational purposes and skill development',
            ],
        ];

        foreach ($leaves as $leave) {
            DB::table('leave_types')->insert([
                'name' => $leave['name'],
                'default_days' => $leave['default_days'],
                'description' => $leave['description'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
