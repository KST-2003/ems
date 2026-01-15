<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        {{-- <li class="nav-item">
            <a class="nav-link" href="">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link collapsed" href="#employee-nav" data-bs-toggle="collapse" aria-expanded="false">
                <i class="bi bi-menu-button-wide"></i>
                <span>Employee Management</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="employee-nav" class="nav-content collapse">
                <li><a href="{{ route('employees.index') }}"><i class="bi bi-circle"></i><span>Employees</span></a></li>
                {{-- <li><a href="{{ route('emp_exp.index') }}"><i class="bi bi-circle"></i><span>Employees Work Experiences</span></a></li> --}}
                {{-- <li><a href="{{ route('attendances.index') }}"><i class="bi bi-circle"></i><span>Attendance</span></a> --}}
                </li>
                <li><a href="{{ route('leaves.index') }}"><i class="bi bi-circle"></i><span>Leaves / Attendance </span></a></li>
                {{-- <li><a href="{{ route('leaves.calendar') }}"><i class="bi bi-circle"></i><span>Leaves</span></a></li> --}}
                <li><a href="{{ route('leave-types.index') }}"><i class="bi bi-circle"></i><span>Leaves Type</span></a></li>
                <li><a href="{{ route('recruitments.index') }}"><i
                            class="bi bi-circle"></i><span>Recruitments</span></a></li>
            </ul>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="">
                <i class="bi bi-bar-chart"></i>
                <span>Reports</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="">
                <i class="bi bi-wrench"></i>
                <span>Security</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed"
                onclick="event.preventDefault(); document.getElementById('logout-user').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <form id="logout-user" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <span>Sign Out</span>
            </a>
        </li>
    </ul>
</aside>
