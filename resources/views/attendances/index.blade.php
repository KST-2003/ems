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
                <div class="col-md-3">
                    <label class="form-label fw-bold text-primary">View Specific Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $date }}" onchange="this.form.submit()">
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

    {{-- FIX: Using $calendarEntry to match controller --}}
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
                            <button type="button" class="btn btn-outline-success me-2" onclick="markAllPresent()">
                                <i class="bi bi-check-all"></i> Mark All Present
                            </button>
                            {{-- Interactive Save Button --}}
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
                                            @if ($isOnLeave)
                                                <span class="badge bg-info text-dark">ON APPROVED LEAVE</span>
                                            @else
                                                <div class="btn-group shadow-sm" role="group">
                                                    <input type="radio" class="btn-check att-radio" name="attendance[{{ $emp->id }}]" id="pres_{{ $emp->id }}" value="present" {{ $currentStatus == 'present' ? 'checked' : '' }} autocomplete="off">
                                                    <label class="btn btn-outline-success btn-sm px-3" for="pres_{{ $emp->id }}">Present</label>

                                                    <input type="radio" class="btn-check att-radio" name="attendance[{{ $emp->id }}]" id="duty_{{ $emp->id }}" value="on_duty" {{ $currentStatus == 'on_duty' ? 'checked' : '' }} autocomplete="off">
                                                    <label class="btn btn-outline-info btn-sm px-3" for="duty_{{ $emp->id }}">On Duty</label>

                                                    <input type="radio" class="btn-check att-radio" name="attendance[{{ $emp->id }}]" id="abs_{{ $emp->id }}" value="absent" {{ $currentStatus == 'absent' ? 'checked' : '' }} autocomplete="off">
                                                    <label class="btn btn-outline-danger btn-sm px-3" for="abs_{{ $emp->id }}">Absent</label>
                                                </div>
                                            @endif
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

<style>
    /* Save Button UI Effects */
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
</style>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    // 1. Initialize DataTable with search and pagination
    const table = $('#attendanceTable').DataTable({
        "pageLength": 25,
        "lengthMenu": [10, 25, 50, 100],
        "columnDefs": [ { "orderable": false, "targets": 2 } ]
    });

    const saveBtn = $('#save-all-btn');

    // 2. Brighten button on change
    $(document).on('change', '.att-radio', function() {
        saveBtn.addClass('unsaved-changes').removeClass('dimmed');
    });

    // 3. Mark All Present Logic (Compatible with DataTables)
    window.markAllPresent = function() {
        // Access all rows in table, even hidden pages
        table.$('.att-radio[value="present"]').each(function() {
            if (!$(this).is(':checked')) {
                $(this).prop('checked', true);
                saveBtn.addClass('unsaved-changes').removeClass('dimmed');
            }
        });
    };

    // 4. Handle hidden pagination data during form submission
    $('#attendanceForm').on('submit', function(e) {
        let form = this;
        // Collect checked inputs from all pages before submitting
        table.$('input:checked').each(function() {
            if(!$.contains(document, this)) {
                $(form).append(
                    $('<input>').attr('type', 'hidden').attr('name', this.name).val(this.value)
                );
            }
        });
    });
});
</script>
@endsection