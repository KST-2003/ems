@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center mb-4">
            <h3 class="fw-bold mb-0 text-dark">
                <i class="bi bi-shield-lock-fill me-2 text-primary"></i>
                Security & Access Control
            </h3>
        </div>

        <ul class="nav nav-pills mb-4 bg-white p-2 rounded shadow-sm" id="securityTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active px-4" id="sessions-tab" data-bs-toggle="tab" data-bs-target="#sessions"
                    type="button" role="tab">
                    <i class="bi bi-display me-2"></i>Active Sessions
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button"
                    role="tab">
                    <i class="bi bi-people me-2"></i>User Management
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-4" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs" type="button"
                    role="tab">
                    <i class="bi bi-journal-text me-2"></i>System Activity
                </button>
            </li>
        </ul>

        <div class="tab-content mt-2" id="securityTabsContent">

            <div class="tab-pane fade show active" id="sessions" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-danger text-white p-3 border-0">
                        <h5 class="mb-0 fs-6 fw-bold">Active Devices & Sessions</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">User</th>
                                        <th>IP Address</th>
                                        <th>Browser</th>
                                        <th>Last Activity</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activeSessions as $session)
                                        <tr>
                                            <td class="ps-4"><strong>{{ $session->user_name }}</strong></td>
                                            <td><code>{{ $session->ip_address }}</code></td>
                                            <td>
                                                @php
                                                    $agent = $session->user_agent;

                                                    if (
                                                        strpos($agent, 'Edg') !== false ||
                                                        strpos($agent, 'Edge') !== false
                                                    ) {
                                                        // Microsoft Edge must be checked first because it also contains 'Chrome'
                                                        $browserIcon = '<i class="bi bi-browser-edge text-info"></i>';
                                                        $browserName = 'Microsoft Edge';
                                                    } elseif (strpos($agent, 'Chrome') !== false) {
                                                        $browserIcon =
                                                            '<i class="bi bi-browser-chrome text-warning"></i>';
                                                        $browserName = 'Google Chrome';
                                                    } elseif (strpos($agent, 'Firefox') !== false) {
                                                        $browserIcon =
                                                            '<i class="bi bi-browser-firefox text-danger"></i>';
                                                        $browserName = 'Mozilla Firefox';
                                                    } elseif (strpos($agent, 'Safari') !== false) {
                                                        $browserIcon = '<i class="bi bi-compass text-primary"></i>';
                                                        $browserName = 'Safari';
                                                    } elseif (
                                                        strpos($agent, 'OPR') !== false ||
                                                        strpos($agent, 'Opera') !== false
                                                    ) {
                                                        $browserIcon =
                                                            '<i class="bi bi-browser-opera text-danger"></i>';
                                                        $browserName = 'Opera';
                                                    } else {
                                                        $browserIcon = '<i class="bi bi-laptop"></i>';
                                                        $browserName = 'Other Browser';
                                                    }
                                                @endphp

                                                {!! $browserIcon !!} {{ $browserName }}
                                                
                                            </td>
                                            <td>{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                            </td>
                                            <td class="text-end pe-4">
                                                @if ($session->id !== session()->getId())
                                                    <form action="{{ route('admin.logout-session', $session->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button
                                                            class="btn btn-sm btn-outline-danger rounded-pill px-3">Force
                                                            Logout</button>
                                                    </form>
                                                @else
                                                    <span
                                                        class="badge bg-success-light text-success border border-success px-3 py-2 rounded-pill">Current
                                                        Device</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="users" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-primary text-white p-3 border-0">
                        <h5 class="mb-0 fs-6 fw-bold">User Account Management</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Name</th>
                                        <th>Email</th>
                                        <th>Current Role</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="ps-4">{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <td>
                                                    <select name="role"
                                                        class="form-select form-select-sm border-0 bg-light rounded-pill">
                                                        <option value="Level 1"
                                                            {{ $user->hasRole('Level 1') ? 'selected' : '' }}>Level 1
                                                            (Viewer)</option>
                                                        <option value="Level 2"
                                                            {{ $user->hasRole('Level 2') ? 'selected' : '' }}>Level 2 (Data
                                                            Entry)</option>
                                                        <option value="Level 3"
                                                            {{ $user->hasRole('Level 3') ? 'selected' : '' }}>Level 3
                                                            (Editor)</option>
                                                        <option value="Super Admin"
                                                            {{ $user->hasRole('Super Admin') ? 'selected' : '' }}>Super
                                                            Admin</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="is_active"
                                                            {{ $user->is_active ? 'checked' : '' }}>
                                                        <label
                                                            class="small text-muted ms-1">{{ $user->is_active ? 'Active' : 'Banned' }}</label>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-primary px-3 rounded-pill">Save
                                                        Changes</button>
                                                </td>
                                            </form>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="logs" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-dark text-white p-3 border-0">
                        <h5 class="mb-0 fs-6 fw-bold">System Activity Audit Logs</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Time</th>
                                        <th>Admin/User</th>
                                        <th>Action</th>
                                        <th class="text-end pe-4">IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (\App\Models\SystemLog::with('user')->latest()->take(20)->get() as $log)
                                        <tr>
                                            <td class="ps-4"><span class="text-muted"><i class="bi bi-clock me-1"></i>
                                                    {{ $log->created_at->format('M d, H:i:s') }}</span></td>
                                            <td><strong>{{ $log->user->name ?? 'System' }}</strong></td>
                                            <td><span
                                                    class="badge bg-info-light text-info border border-info rounded-pill px-3">{{ $log->action }}</span>
                                            </td>
                                            <td class="text-end pe-4"><code>{{ $log->ip_address }}</code></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">No logs yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-success-light {
            background-color: rgba(25, 135, 84, 0.1);
        }

        .bg-info-light {
            background-color: rgba(13, 202, 240, 0.1);
        }
    </style>
@endsection
