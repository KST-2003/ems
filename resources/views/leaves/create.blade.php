@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Record New Leave</h1>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Entry Form</h5>
            <form action="{{ route('leaves.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select class="form-select select2" id="employee_id" name="employee_id" required>
                            <option value="">Select Employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }} ({{ $employee->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="leave_type_id" name="leave_type_id" required>
                            <option value="">Select Type</option>
                            @foreach ($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Current Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status" required onchange="toggleEndDate()">
                            <option value="ongoing">Ongoing (On Leave Now)</option>
                            <option value="done">Done (Returned)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Start Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="col-md-4" id="end_date_row" style="display: none;">
                        <label class="form-label">End Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="end_date" name="end_date">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Reason/Remark</label>
                    <textarea class="form-control" name="reason" rows="2"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Record</button>
            </form>
        </div>
    </div>
</section>

<script>
    function toggleEndDate() {
        var status = document.getElementById('status').value;
        var row = document.getElementById('end_date_row');
        var input = document.getElementById('end_date');
        if (status === 'done') {
            row.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            row.style.display = 'none';
            input.removeAttribute('required');
        }
    }
</script>
@endsection