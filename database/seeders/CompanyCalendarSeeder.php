<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompanyCalendar;

class CompanyCalendarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $holidays = [
            ['date' => '2026-01-04', 'name' => 'လွတ်လပ်ရေးနေ့', 'type' => 'holiday'],
            ['date' => '2026-02-12', 'name' => 'ပြည်ထောင်စုနေ့', 'type' => 'holiday'],
            ['date' => '2026-03-02', 'name' => 'တောင်သူလယ်သမားနေ့', 'type' => 'holiday'],
            ['date' => '2026-03-27', 'name' => 'တပ်မတော်နေ့', 'type' => 'holiday'],
            ['date' => '2026-04-13', 'name' => 'သင်္ကြန်အကြိုနေ့', 'type' => 'holiday'],
            ['date' => '2026-04-14', 'name' => 'သင်္ကြန်အကျနေ့', 'type' => 'holiday'],
            ['date' => '2026-04-15', 'name' => 'သင်္ကြန်အကြတ်နေ့', 'type' => 'holiday'],
            ['date' => '2026-04-16', 'name' => 'သင်္ကြန်အတက်နေ့', 'type' => 'holiday'],
            ['date' => '2026-04-17', 'name' => 'မြန်မာနှစ်ဆန်းတစ်ရက်နေ့', 'type' => 'holiday'],
            ['date' => '2026-05-01', 'name' => 'အလုပ်သမားနေ့', 'type' => 'holiday'],
            ['date' => '2026-07-19', 'name' => 'အာဇာနည်နေ့', 'type' => 'holiday'],
            ['date' => '2026-11-24', 'name' => 'တန်ဆောင်တိုင်လပြည့်နေ့', 'type' => 'holiday'],
            ['date' => '2026-12-04', 'name' => 'အမျိုးသားနေ့', 'type' => 'holiday'],
            ['date' => '2026-12-25', 'name' => 'ခရစ္စမတ်နေ့', 'type' => 'holiday'],
        ];

        foreach ($holidays as $holiday) {
            // updateOrCreate prevents duplicate entries if you run the seeder multiple times
            CompanyCalendar::updateOrCreate(
                ['date' => $holiday['date']], 
                ['name' => $holiday['name'], 'type' => $holiday['type']]
            );
        }
    }
}