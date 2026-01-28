<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\SystemLog;

class SecurityController extends Controller
{
    public function index()
    {
        // 1. Fetch all users with their roles
        $users = User::with('roles')->get();

        // 2. Fetch active sessions and join with users
        $activeSessions = DB::table('sessions')
            ->leftJoin('users', 'sessions.user_id', '=', 'users.id')
            ->select('sessions.*', 'users.name as user_name')
            ->whereNotNull('user_id')
            ->get();

        return view('admin.security.index', compact('users', 'activeSessions'));
    }

    public function updateUser(Request $request, User $user)
    {
        // 1. Prevent Super Admin from deactivating themselves
        if ($user->id === auth()->id() && $request->is_active == 0) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        // 2. Update Basic Info
        $user->update([
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        // 3. Sync Roles (Level 1, Level 2, or Level 3)
        // Spatie's syncRoles will remove old roles and add the new one
        if ($request->has('role')) {
            $user->syncRoles($request->role);
        }

        return back()->with('success', 'User permissions updated successfully.');
    }
    public function logoutSession($sessionId)
    {
        $session = DB::table('sessions')->where('id', $sessionId)->first();
        $targetUser = User::find($session->user_id);

        // Log the action before deleting the session
        SystemLog::create([
            'user_id' => auth()->id(),
            'action' => 'Force Logout for User: ' . $targetUser->name,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        DB::table('sessions')->where('id', $sessionId)->delete();
        return back()->with('success', 'User device has been remotely logged out.');
    }
}
