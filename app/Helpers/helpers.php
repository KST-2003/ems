<?php

use Carbon\Carbon;

if (!function_exists('toMyanmarNum')) {
    function toMyanmarNum($number) {
        $english = range(0, 9);
        $myanmar = ['၀', '၁', '၂', '၃', '၄', '၅', '၆', '၇', '၈', '၉'];
        return str_replace($english, $myanmar, $number);
    }
}

if (!function_exists('toMyanmarDate')) {
    function toMyanmarDate($date) {
        // 1. Ensure $date is a Carbon object
        if (!$date instanceof Carbon) {
            $date = Carbon::parse($date);
        }

        // 2. Get the Burmese Month
        // We force locale('my') to ensure it's always in Burmese, 
        // even if the app language changes.
        $month = $date->locale('my')->translatedFormat('F');

        // 3. Convert Year and Day to Myanmar Numerals
        $year = toMyanmarNum($date->year);
        $day = toMyanmarNum($date->day);

        // 4. Return the standard format: "2025 Year, December Month, 26 Day"
        return "{$year} ခုနှစ်၊ {$month} လ၊ {$day} ရက်";
    }
}