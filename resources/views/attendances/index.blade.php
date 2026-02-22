@extends('layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between">
    <h1>Daily Attendance: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h1>
</div>

<section class="section">
    {{-- Filters --}}
    <div class="card mb-3 shadow-sm">
        <div class="card-body pt-3">
            <form method="GET" action="{{ route('attendances.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4"> {{-- Widened to col-md-4 to fit the Today button --}}
                    <label class="form-label fw-bold text-primary">View Specific Date</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-calendar3"></i></span>
                        {{-- Flatpickr will target this input --}}
                        <input type="text" name="date" id="datePicker" class="form-control border-start-0 bg-white" value="{{ $date }}" placeholder="Select Date" readonly>
                        {{-- NEW: Today Button --}}
                        <button class="btn btn-outline-primary" type="button" id="btn-today">Today</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Filter Department</label>
                    <select name="department" class="form-select" onchange="this.form.submit()">
                        <option value="">All Departments</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if ($calendarEntry)
        <div class="alert alert-warning shadow-sm border-start border-4 border-warning">
            <i class="bi bi-calendar-x me-2"></i>
            <strong>Holiday: {{ $calendarEntry->name }}</strong> ({{ ucfirst(str_replace('_', ' ', $calendarEntry->type)) }}). 
            Today is a designated close day. marking is disabled.
        </div>
    @else
        <form id="attendanceForm" action="{{ route('attendances.bulk-store') }}" method="POST">
            @csrf
            <input type="hidden" name="date" value="{{ $date }}">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pt-3">
                        <h5 class="card-title">Employee Call Sheet</h5>
                        <div>
                            {{-- Remove All Records Button --}}
                            <button type="submit" name="delete_all" value="1" class="btn btn-outline-danger me-2" onclick="return confirm('Are you sure you want to delete all attendance records for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}?');">
                                <i class="bi bi-trash"></i> Remove All Records
                            </button>
                            
                            <button type="button" class="btn btn-outline-success me-2" onclick="markAllPresent()">
                                <i class="bi bi-check-all"></i> Mark All Present
                            </button>
                            <button type="submit" id="save-all-btn" class="btn btn-primary shadow-sm dimmed">
                                <i class="bi bi-save"></i> Save All Records
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="attendanceTable" class="table table-hover align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee Details</th>
                                    <th>Department</th>
                                    <th class="text-center">Status Selection</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $emp)
                                    @php
                                        $isOnLeave = in_array($emp->id, $onLeaveIds);
                                        $currentStatus = $existingAttendance[$emp->id] ?? 'absent';
                                    @endphp
                                    <tr class="{{ $isOnLeave ? 'opacity-50 bg-light' : '' }}">
                                        <td>
                                            <strong>{{ $emp->name }}</strong><br>
                                            <small class="text-muted">{{ $emp->employee_id }}</small>
                                        </td>
                                        <td>{{ $emp->department }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                
                                                @if ($isOnLeave)
                                                    {{-- HIDDEN INPUT: Automatically sends "leaves" to DB --}}
                                                    <input type="hidden" name="attendance[{{ $emp->id }}]" value="leaves">
                                                    
                                                    {{-- REPLACES RADIO BUTTONS --}}
                                                    <span class="badge bg-warning text-dark me-3 py-2 px-3">
                                                        <i class="bi bi-umbrella me-1"></i> ON LEAVES
                                                    </span>
                                                    
                                                    {{-- GO TO LEAVES PAGE --}}
                                                    <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-outline-dark me-3" target="_blank">
                                                        <i class="bi bi-box-arrow-up-right"></i> Details
                                                    </a>
                                                @else
                                                    {{-- STANDARD RADIO BUTTONS --}}
                                                    <div class="btn-group shadow-sm me-3" role="group">
                                                        <input type="radio" class="btn-check att-radio" name="attendance[{{ $emp->id }}]" id="pres_{{ $emp->id }}" value="present" {{ $currentStatus == 'present' ? 'checked' : '' }} autocomplete="off">
                                                        <label class="btn btn-outline-success btn-sm px-3" for="pres_{{ $emp->id }}">Present</label>

                                                        <input type="radio" class="btn-check att-radio" name="attendance[{{ $emp->id }}]" id="duty_{{ $emp->id }}" value="on_duty" {{ $currentStatus == 'on_duty' ? 'checked' : '' }} autocomplete="off">
                                                        <label class="btn btn-outline-info btn-sm px-3" for="duty_{{ $emp->id }}">On Duty</label>

                                                        <input type="radio" class="btn-check att-radio" name="attendance[{{ $emp->id }}]" id="abs_{{ $emp->id }}" value="absent" {{ $currentStatus == 'absent' ? 'checked' : '' }} autocomplete="off">
                                                        <label class="btn btn-outline-danger btn-sm px-3" for="abs_{{ $emp->id }}">Absent</label>
                                                    </div>
                                                @endif
                                                
                                                <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#attendanceModal" data-employee-id="{{ $emp->id }}" data-employee-name="{{ $emp->name }}">
                                                    <i class="bi bi-calendar3 me-1"></i> View
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    @endif
</section>

<div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title text-primary" id="attendanceModalLabel">
                    <i class="bi bi-person-bounding-box me-2"></i> Attendance History: <span id="modalEmployeeName" class="fw-bold text-dark"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="d-flex justify-content-center mb-3">
                    <span class="me-4"><span class="d-inline-block rounded-circle" style="width: 14px; height: 14px; background: #28a745;"></span> Present</span>
                    <span class="me-4"><span class="d-inline-block rounded-circle" style="width: 14px; height: 14px; background: #0d6efd;"></span> On Duty</span>
                    <span class="me-4"><span class="d-inline-block rounded-circle" style="width: 14px; height: 14px; background: #ffc107;"></span> Leaves</span>
                    <span><span class="d-inline-block rounded-circle" style="width: 14px; height: 14px; background: #dc3545;"></span> Absent</span>
                </div>
                <div id="employee-calendar"></div>
            </div>
        </div>
    </div>
</div>

<style>
    #save-all-btn.dimmed {
        opacity: 0.5;
        pointer-events: none;
        transition: all 0.3s ease;
    }
    #save-all-btn.unsaved-changes {
        opacity: 1 !important;
        pointer-events: auto !important;
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(13, 110, 253, 0.6);
    }

    /* --- Flatpickr Styling Fixes --- */
    .flatpickr-input[readonly] {
        background-color: #fff !important;
        cursor: pointer;
    }

    /* --- Calendar Styling Overrides --- */
    .fc-day-sat .fc-daygrid-day-number, 
    .fc-day-sun .fc-daygrid-day-number {
        color: #dc3545 !important;
        font-weight: bold;
    }

    .fc-day.working-weekend .fc-daygrid-day-number {
        color: #212529 !important;
    }

    .fc-daygrid-day-top {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .holiday-text {
        color: #dc3545;
        font-size: 0.75rem;
        font-weight: 700;
        padding-left: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fc-daygrid-event-harness {
        background: transparent !important;
    }
    .fc-h-event {
        background-color: transparent !important;
        border: none !important;
    }

    .attendance-circle {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        margin: 5px auto;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .fc-day-today {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }
</style>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function() {

    // ==========================================
    // Flatpickr Initialization
    // ==========================================
    // 1. Initialize Flatpickr and save the instance to a variable
    const fp = flatpickr("#datePicker", {
        dateFormat: "Y-m-d", // Value sent to the server
        altInput: true,
        altFormat: "d M Y",  // Value shown to the user (e.g., 22 Feb 2026)
        defaultDate: "{{ $date }}",
        onChange: function(selectedDates, dateStr, instance) {
            // Automatically submit the form when a new date is picked
            instance.element.closest('form').submit();
        }
    });

    // 2. Add click event to the "Today" button
    $('#btn-today').on('click', function() {
        // Set date to 'today' (new Date()), and pass 'true' to trigger the onChange event above
        fp.setDate(new Date(), true);
    });

    const table = $('#attendanceTable').DataTable({
        "pageLength": 25,
        "lengthMenu": [10, 25, 50, 100],
        "columnDefs": [ { "orderable": false, "targets": 2 } ]
    });

    const saveBtn = $('#save-all-btn');

    $(document).on('change', '.att-radio', function() {
        saveBtn.addClass('unsaved-changes').removeClass('dimmed');
    });

    window.markAllPresent = function() {
        table.$('.att-radio[value="present"]').each(function() {
            if (!$(this).is(':checked') && !$(this).is(':hidden')) {
                $(this).prop('checked', true);
                saveBtn.addClass('unsaved-changes').removeClass('dimmed');
            }
        });
    };

    $('#attendanceForm').on('submit', function(e) {
        let form = this;
        // Append checked radios OR hidden inputs indicating leave
        table.$('input:checked, input[type="hidden"]').each(function() {
            if(!$.contains(document, this)) {
                $(form).append(
                    $('<input>').attr('type', 'hidden').attr('name', this.name).val(this.value)
                );
            }
        });
    });

    // ==========================================
    // FullCalendar Custom Implementation
    // ==========================================
    let calendar = null;
    const attendanceModal = document.getElementById('attendanceModal');
    const calendarEl = document.getElementById('employee-calendar');

    attendanceModal.addEventListener('shown.bs.modal', function (event) {
        const button = event.relatedTarget;
        const employeeId = button.getAttribute('data-employee-id');
        const employeeName = button.getAttribute('data-employee-name');
        
        document.getElementById('modalEmployeeName').textContent = employeeName;

        if (calendar) {
            calendar.destroy();
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 600,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            events: `/attendances/employee-events/${employeeId}`,
            
            eventDidMount: function(info) {
                const event = info.event;
                const props = event.extendedProps;
                const el = info.el;

                if (props.type === 'attendance') {
                    el.innerHTML = `<div class="d-flex justify-content-center" title="${event.title}">
                                        <div class="attendance-circle" style="background-color: ${event.backgroundColor};"></div>
                                    </div>`;

                } else if (props.type === 'holiday' || props.type === 'close_exception') {
                    const dateStr = event.startStr;
                    const dayCellTop = calendarEl.querySelector(`.fc-day[data-date="${dateStr}"] .fc-daygrid-day-top`);
                    
                    if (dayCellTop && !dayCellTop.querySelector('.holiday-text')) {
                        const holidayLabel = document.createElement('span');
                        holidayLabel.className = 'holiday-text';
                        holidayLabel.innerText = event.title;
                        dayCellTop.prepend(holidayLabel);
                    }
                    el.style.display = 'none';

                } else if (props.type === 'open_exception') {
                    const dateStr = event.startStr;
                    const dayCell = calendarEl.querySelector(`.fc-day[data-date="${dateStr}"]`);
                    
                    if (dayCell) {
                        dayCell.classList.add('working-weekend');
                    }
                    el.style.display = 'none';
                }
            }
        });

        calendar.render();
    });

    attendanceModal.addEventListener('shown.bs.modal', function () {
        if (calendar) {
            calendar.updateSize();
        }
    });
});
</script>
@endsection