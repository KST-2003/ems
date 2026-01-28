<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">


        {{-- Employee Management Dropdown --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->is('employees*', 'attendances*', 'leaves*', 'leave-types*', 'recruitments*') ? '' : 'collapsed' }}"
                href="#employee-nav" data-bs-toggle="collapse"
                aria-expanded="{{ request()->is('employees*', 'attendances*', 'leaves*', 'leave-types*', 'recruitments*') ? 'true' : 'false' }}">
                <i class="bi bi-person-badge"></i>
                <span>Employee Management</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="employee-nav"
                class="nav-content collapse {{ request()->is('employees*', 'attendances*', 'leaves*', 'leave-types*', 'recruitments*') ? 'show' : '' }}"
                data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('employees.index') }}"
                        class="{{ request()->routeIs('employees.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Employees</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('calendar.index') }}"
                        class="{{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Calendar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('attendances.index') }}"
                        class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Daily Attendance</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('leaves.index') }}"
                        class="{{ request()->routeIs('leaves.index') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Leaves Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('leave-allocations.index') }}"
                        class="{{ request()->routeIs('leave-allocations.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Leave Allocations</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('leave-types.index') }}"
                        class="{{ request()->routeIs('leave-types.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Leave Types</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('recruitments.index') }}"
                        class="{{ request()->routeIs('recruitments.*') ? 'active' : '' }}">
                        <i class="bi bi-circle"></i><span>Recruitments</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Reports Section - FIXED --}}
        <li>
            <a class="nav-link {{ request()->routeIs('leaves.reports') || request()->is('reports*') ? '' : 'collapsed' }}"
                href="{{ route('leaves.reports') }}">
                {{-- <i class="bi bi-bar-chart"></i> --}}
                <span>Reports (BOD Summary)</span>
            </a>
        </li>

        {{-- Security Section - FIXED --}}
        @role('Super Admin')
            <li>
                <a href="{{ route('admin.security') }}" target="_self"
                    class="nav-link {{ request()->routeIs('admin.security') ? '' : 'collapsed' }}">
                    <i class="bi bi-shield-lock"></i>
                    <span>Security & Roles</span>
                </a>
            </li>
        @endrole
        {{-- Sign Out --}}
        <li class="nav-item">
            <a class="nav-link collapsed" style="cursor: pointer;"
                onclick="event.preventDefault(); document.getElementById('logout-user').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
            </a>
            <form id="logout-user" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</aside>
