@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.employee_leaves') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">{{ __('messages.employee_leaves') }}</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row mb-4">
            <div class="col-md-3">
                <select id="filter-department" class="form-select">
                    <option value="">{{ __('messages.all_departments') }}</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept }}">{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select id="filter-leave-type" class="form-select">
                    <option value="">{{ __('messages.all_leave_types') }}</option>
                    @foreach ($leaveTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('leaves.create') }}" class="btn btn-primary">
                    {{ __('messages.add_leave') }}
                </a>
            </div>
        </div>

        <ul class="nav nav-tabs mb-4" id="leaveTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="calendar-tab" data-bs-toggle="tab" data-bs-target="#calendarView">Calendar</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#tableView">Leaves Table</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendanceView">Attendance</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="calendarView">
                <div class="calendar-wrapper">
                    <div id="calendarLoading" class="calendar-loading hidden">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>

            <div class="tab-pane fade" id="tableView">
                <table id="leavesTable" class="table table-bordered table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Leave Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Duration (days)</th>
                            <th>Remaining Days</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="attendanceView">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $emp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $emp->name }}</td>
                                <td>{{ $emp->department ?? '-' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info view-attendance"
                                            data-id="{{ $emp->id }}"
                                            data-name="{{ $emp->name }}">
                                        View Attendance
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="modal fade" id="jumpMonthModal" tabindex="-1" aria-labelledby="jumpMonthModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jumpMonthModalLabel">Select Month & Year</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="jumpYear" class="form-label">Year</label>
                        <input type="number" class="form-control" id="jumpYear" min="2000" max="2035" value="2026">
                    </div>
                    <div class="mb-3">
                        <label for="jumpMonth" class="form-label">Month</label>
                        <select class="form-select" id="jumpMonth">
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12" selected>December</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="goToMonthBtn">Go</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attendanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attendance - <span id="modalEmployeeName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="calendar-wrapper">
                        <div id="attendanceLoading" class="calendar-loading hidden">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div id="attendanceCalendar"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>

    <style>
        /* IMPORTANT: Wrapper must be relative */
        .calendar-wrapper {
            position: relative;
            min-height: 200px; /* Prevents height collapse while loading */
        }

        /* Overlay sits on top of wrapper */
        .calendar-loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.85); /* Slightly clearer background */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50; /* Higher than events (usually z-index 1-10) */
            border-radius: 6px;
            transition: opacity 0.2s ease-in-out;
        }

        /* Utility class to hide the spinner */
        .calendar-loading.hidden {
            opacity: 0;
            pointer-events: none; /* Allows clicking buttons behind it when hidden */
            z-index: -1;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>

    <script>
        $(document).ready(function () {
            // === Main Calendar ===
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today jumpToMonth',
                    center: 'title',
                    right: ''
                },
                height: 'auto',
                customButtons: {
                    jumpToMonth: {
                        text: 'Select Month & Year',
                        click: function () {
                            var myModal = new bootstrap.Modal(document.getElementById('jumpMonthModal'));
                            myModal.show();
                        }
                    }
                },
                events: function (fetchInfo, successCallback, failureCallback) {
                    // Show Spinner
                    $('#calendarLoading').removeClass('hidden');

                    $.ajax({
                        url: '{{ route('leaves.calendar-events') }}',
                        data: {
                            start: fetchInfo.startStr,
                            end: fetchInfo.endStr,
                            department: $('#filter-department').val(),
                            leave_type_id: $('#filter-leave-type').val()
                        },
                        success: function (data) {
                            successCallback(data);
                            // Hide Spinner
                            $('#calendarLoading').addClass('hidden');
                        },
                        error: function (xhr) {
                            console.error('Calendar fetch error:', xhr);
                            failureCallback();
                            // Hide Spinner even on error
                            $('#calendarLoading').addClass('hidden');
                        }
                    });
                },
                eventClick: function (info) {
                    alert(info.event.title + (info.event.extendedProps.reason ? '\nReason: ' + info.event.extendedProps.reason : ''));
                }
            });

            // Render Logic (Tab Switching)
            let calendarInitialized = false;
            $('#calendar-tab').on('shown.bs.tab', function () {
                if (!calendarInitialized) {
                    calendar.render();
                    calendarInitialized = true;
                }
                setTimeout(() => calendar.updateSize(), 100);
            });

            if ($('#calendar-tab').hasClass('active')) {
                setTimeout(() => {
                    calendar.render();
                    calendar.updateSize();
                }, 100);
            }

            // Month/Year modal Go button
            document.getElementById('goToMonthBtn').addEventListener('click', function () {
                var year = parseInt(document.getElementById('jumpYear').value);
                var month = parseInt(document.getElementById('jumpMonth').value) - 1;
                if (!isNaN(year) && !isNaN(month)) {
                    calendar.gotoDate(new Date(year, month, 1));
                    bootstrap.Modal.getInstance(document.getElementById('jumpMonthModal')).hide();
                }
            });

            // Reload on filter change
            $('#filter-department, #filter-leave-type').change(function () {
                calendar.refetchEvents();
            });

            // === Leaves Table ===
            var table = $('#leavesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('leaves.datatable') }}',
                    data: function (d) {
                        d.department = $('#filter-department').val();
                        d.leave_type_id = $('#filter-leave-type').val();
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'employee_name' },
                    { data: 'leave_type_name' },
                    { data: 'start_date' },
                    { data: 'end_date' },
                    { data: 'duration' },
                    { data: 'remaining_days', name: 'remaining_days', orderable: false, searchable: false },
                    { data: 'actions', orderable: false, searchable: false }
                ],
                order: [[3, 'desc']]
            });

            $('#filter-department, #filter-leave-type').change(function () {
                table.ajax.reload();
            });

            // === Attendance Modal Calendar ===
            let attendanceCal = null;
            let currentAttendanceId = null;

            $('#attendanceModal').on('shown.bs.modal', function () {
                // Destroy if exists to prevent duplicates
                if (attendanceCal) {
                    attendanceCal.destroy();
                }

                attendanceCal = new FullCalendar.Calendar(document.getElementById('attendanceCalendar'), {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: ''
                    },
                    height: 'auto',
                    events: function (fetchInfo, successCallback, failureCallback) {
                        // Show Spinner
                        $('#attendanceLoading').removeClass('hidden');

                        $.ajax({
                            url: '{{ route('leaves.calendar-events') }}',
                            data: {
                                employee_id: currentAttendanceId,
                                start: fetchInfo.startStr,
                                end: fetchInfo.endStr
                            },
                            success: function (data) {
                                successCallback(data);
                                // Hide Spinner
                                $('#attendanceLoading').addClass('hidden');
                            },
                            error: function (xhr) {
                                console.error('Attendance calendar error:', xhr);
                                failureCallback();
                                // Hide Spinner
                                $('#attendanceLoading').addClass('hidden');
                            }
                        });
                    }
                });

                // Render immediately since the modal is already shown
                attendanceCal.render();
            });

            $(document).on('click', '.view-attendance', function () {
                currentAttendanceId = $(this).data('id');
                const name = $(this).data('name');
                $('#modalEmployeeName').text(name);
                $('#attendanceModal').modal('show');
            });
        });
    </script>
@endsection