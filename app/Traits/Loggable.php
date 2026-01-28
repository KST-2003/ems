<?php

namespace App\Traits;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;

trait Loggable
{
    public static function logAction($action, $details = null)
    {
        // Check if there is a logged-in user
        $userId = Auth::id();

        // If seeding (running from terminal), Auth::id() is null.
        // We can skip logging for seeders, or assign it to the first user (Super Admin).
        if (!$userId) {
            // Option A: Skip logging during seeding
            if (app()->runningInConsole()) {
                return; 
            }
            // Option B: Fallback to first user (if you want to see logs for seeds)
            // $userId = 1; 
        }

        SystemLog::create([
            'user_id'    => $userId,
            'action'     => $action . ($details ? " | " . $details : ""),
            'ip_address' => request()->ip() ?? '127.0.0.1',
            'user_agent' => request()->userAgent() ?? 'Terminal/Seeder',
        ]);
    }
}