@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.employee_leaves') }} - {{ $employee->name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">{{ __('messages.employee_leaves') }}</a></li>
                <li class="breadcrumb-item active">{{ $employee->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <a href="{{ route('employees.show', $employee) }}" class="btn btn-primary">
                {{ __('messages.view_employee_details') }}
            </a>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('leaves.create') }}?employee_id={{ $employee->id }}" class="btn btn-success">
                {{ __('messages.add_leave') }}
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-3">
        <label for="leave_type" class="form-label">{{ __('messages.filter_by_leave_type') }}</label>
        <select id="leave_type" class="form-select w-25">
            <option value="">{{ __('messages.all_leave_types') }}</option>
            @foreach ($leaveTypes as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Table -->
    <table id="employee-leaves-table" class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('messages.leave_type') }}</th>
                <th>{{ __('messages.start_date') }} (D-M-Y)</th>
                <th>{{ __('messages.end_date') }} (D-M-Y)</th>
                <th>{{ __('messages.duration') }}</th>
                <th>{{ __('messages.reason') }}</th>
                <th>{{ __('messages.status') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- Calendar Section -->
    <div class="card mt-5">
        <div class="card-header">
            <h5 class="card-title">{{ __('messages.attendance_and_leaves_calendar') }}</h5>
        </div>
        <div class="card-body">
            <div id="employee-calendar"></div>
        </div>
    </div>

    <!-- Legend -->
    <div class="mt-3">
        <strong>{{ __('messages.legend') }}:</strong><br>
        <span style="color:#28a745">■ Present</span> &nbsp;
        <span style="color:#dc3545">■ Absent</span> &nbsp;
        <span style="color:#ff851b">■ Unplanned Absence</span> &nbsp;
        <span style="color:#3788d8">■ Leave (varies by type)</span>
    </div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // DataTables for leaves table
            var table = $('#employee-leaves-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("leaves.index") }}', // Adjust if you have employee-specific route
                    data: function (d) {
                        d.employee_id = {{ $employee->id }};
                        d.leave_type = $('#leave_type').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'leave_type_name' },
                    { data: 'start_date' },
                    { data: 'end_date' },
                    { data: 'duration' },
                    { data: 'reason' },
                    { data: 'status' },
                    { data: 'actions', orderable: false, searchable: false }
                ]
            });

            $('#leave_type').change(function() {
                table.ajax.reload();
            });

            // FullCalendar for individual employee
            var calendarEl = document.getElementById('employee-calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek'
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '{{ route('leaves.calendar-events') }}',
                        data: {
                            employee_id: {{ $employee->id }},
                            start: fetchInfo.startStr,
                            end: fetchInfo.endStr
                        },
                        success: function(data) {
                            successCallback(data);
                        },
                        error: function() {
                            failureCallback();
                        }
                    });
                },
                eventClick: function(info) {
                    if (info.event.extendedProps.reason) {
                        alert(info.event.title + '\nReason: ' + info.event.extendedProps.reason);
                    }
                }
            });
            calendar.render();
        });
    </script>
@endsection