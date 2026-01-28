<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $lastActivity = session('last_activity_timestamp');
            $currentTime = time();

            // If more than 30 seconds have passed since the last heartbeat
            if ($lastActivity && ($currentTime - $lastActivity > 30)) {
                auth()->logout();
                session()->invalidate();
                session()->regenerateToken();
                return redirect()->route('login')->with('error', 'Security Timeout: Tab closed or inactive.');
            }

            // Update the timestamp to current time
            session(['last_activity_timestamp' => $currentTime]);
        }

        $response = $next($request);

        // Maintain strict headers to prevent back-button access to sensitive Template C data
        return $response->header('Cache-Control', 'no-cache, no-store, max-age=0, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
