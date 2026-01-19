@extends('layouts.app')

@section('content')
<div class="pagetitle d-flex justify-content-between">
    <h1>Daily Attendance: {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h1>
</div>

<section class="section">
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
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($holiday)
        <div class="alert alert-info shadow-sm">
            <i class="bi bi-info-circle me-1"></i>
            <strong>Holiday: {{ $holiday->name }}</strong>. Today is a designated close day.
        </div>
    @else
        <form action="{{ route('attendances.bulk-store') }}" method="POST">
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
                            <button type="submit" class="btn btn-primary shadow-sm">Save All Records</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee Details</th>
                                    <th>Department</th>
                                    <th class="text-center">Status Selection</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $emp)
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
                                            @if($isOnLeave)
                                                <span class="badge bg-info text-dark">ON APPROVED LEAVE</span>
                                            @else
                                                <div class="btn-group shadow-sm" role="group">
                                                    <input type="radio" class="btn-check" name="attendance[{{ $emp->id }}]" id="pres_{{ $emp->id }}" value="present" {{ $currentStatus == 'present' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-success btn-sm px-3" for="pres_{{ $emp->id }}">Present</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $emp->id }}]" id="duty_{{ $emp->id }}" value="on_duty" {{ $currentStatus == 'on_duty' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-info btn-sm px-3" for="duty_{{ $emp->id }}">On Duty</label>

                                                    <input type="radio" class="btn-check" name="attendance[{{ $emp->id }}]" id="abs_{{ $emp->id }}" value="absent" {{ $currentStatus == 'absent' ? 'checked' : '' }}>
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
@endsection

@section('scripts')
<script>
function markAllPresent() {
    const presentRadios = document.querySelectorAll('input[type="radio"][value="present"]');
    presentRadios.forEach(radio => {
        if (!radio.disabled) {
            radio.checked = true;
        }
    });
}
</script>
@endsection