@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="pagetitle d-flex justify-content-between align-items-center mb-4">
        <h1>Leave & Absence Management</h1>
        <div>
            <a href="{{ route('leave-allocations.index') }}" class="btn btn-outline-primary shadow-sm">Individual Allocations</a>
            <a href="{{ route('leaves.create') }}" class="btn btn-primary shadow-sm">+ Add New Record</a>
        </div>
    </div>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-3 shadow-sm" id="leaveTabs" role="tablist" style="background: #fff; border-radius: 8px 8px 0 0;">
        <li class="nav-item">
            <button class="nav-link active" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendarView">Calendar View</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="employee-tab" data-bs-toggle="tab" data-bs-target="#employeeView">Employees Individual</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#tableView">Data Log</button>
        </li>
    </ul>

    <div class="tab-content">
        {{-- Tab 1: Global Calendar --}}
        <div class="tab-pane fade show active" id="calendarView">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select id="filter-dept-main" class="form-select shadow-sm">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept) <option value="{{ $dept }}">{{ $dept }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="main-calendar"></div>
                </div>
            </div>
        </div>

        {{-- Tab 2: Individual Employee View --}}
        <div class="tab-pane fade" id="employeeView">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="row mb-4">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Select Employee to View History</label>
                            <select id="individual-emp-selector" class="form-select shadow-sm">
                                <option value="">-- Search Employee --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="individual-calendar"></div>
                </div>
            </div>
        </div>

        {{-- Tab 3: Data Log --}}
        <div class="tab-pane fade" id="tableView">
            <div class="card shadow-sm p-3">
                <table id="leavesTable" class="table table-striped w-100">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Jump to Month Modal --}}
<div class="modal fade" id="jumpDateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Month & Year</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Year</label>
                        <select id="jumpYear" class="form-select">
                            @for ($y = 2024; $y <= 2030; $y++) <option value="{{ $y }}" {{ $y == 2026 ? 'selected' : '' }}>{{ $y }}</option> @endfor
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Month</label>
                        <select id="jumpMonth" class="form-select">
                            @foreach(['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'] as $k => $m)
                                <option value="{{ $k }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmJump" class="btn btn-primary">Go to Date</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

<script>
$(document).ready(function() {
    
    // Configuration common to both calendars
    const commonCalOptions = {
        initialView: 'dayGridMonth',
        displayEventTime: false, // FIX: Removes the "7a" time prefix
        dayMaxEvents: 3,         // FIX: Shows max 3 names, then "+more" button
        moreLinkClick: function(info) {
            // FIX: Custom Alert for See More
            let dateStr = info.date.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
            let list = info.allSegs.map(s => "• " + s.footprint.eventDef.title).join("\n");
            alert("Attendance/Leaves for " + dateStr + ":\n\n" + list);
            return "none"; 
        },
        eventClick: function(info) {
            alert("Employee: " + info.event.title + "\nDetails: " + (info.event.extendedProps.reason || 'No details provided.'));
        }
    };

    // 1. Global Calendar
    var mainCalEl = document.getElementById('main-calendar');
    var mainCalendar = new FullCalendar.Calendar(mainCalEl, {
        ...commonCalOptions,
        // SYNC: 'jumpDate' in toolbar must match customButtons key
        headerToolbar: { left: 'prev,next today jumpDate', center: 'title', right: '' }, 
        customButtons: {
            jumpDate: { 
                text: 'Jump to Date', 
                click: function() { $('#jumpDateModal').modal('show'); } 
            }
        },
        events: function(info, success, failure) {
            $.ajax({
                url: "{{ route('leaves.calendar-events') }}",
                data: { start: info.startStr, end: info.endStr, department: $('#filter-dept-main').val() },
                success: function(data) { success(data); },
                error: function() { failure(); }
            });
        }
    });

    // 2. Individual Employee Calendar
    var indCalEl = document.getElementById('individual-calendar');
    var indCalendar = new FullCalendar.Calendar(indCalEl, {
        ...commonCalOptions,
        headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
        events: []
    });

    // Handle Employee Selection
    $('#individual-emp-selector').change(function() {
        var empId = $(this).val();
        if(empId) {
            indCalendar.removeAllEventSources();
            indCalendar.addEventSource(`/attendances/employee-events/${empId}`);
        }
    });

    // Modal Jump Logic
    $('#confirmJump').on('click', function() {
        var targetDate = new Date($('#jumpYear').val(), $('#jumpMonth').val(), 1);
        mainCalendar.gotoDate(targetDate);
        $('#jumpDateModal').modal('hide');
    });

    // Tab Resize Fix
    $('#calendar-tab').on('shown.bs.tab', function() { mainCalendar.render(); mainCalendar.updateSize(); });
    $('#employee-tab').on('shown.bs.tab', function() { indCalendar.render(); indCalendar.updateSize(); });

    mainCalendar.render();

    // 3. DataTable
    $('#leavesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('leaves.datatable') }}",
        columns: [
            {data: 'employee_name', name: 'employees.name'},
            {data: 'leave_type_name', name: 'leave_types.name'},
            {data: 'start_date', name: 'start_date'},
            {data: 'end_date', name: 'end_date'},
            {data: 'duration', name: 'duration'},
            {data: 'status', name: 'status'},
            {data: 'actions', name: 'actions'}
        ]
    });

    // Filter refresh
    $('#filter-dept-main').change(function() {
        mainCalendar.refetchEvents();
    });
});
</script>
@endsection