@extends('layouts.app')

@section('css')
    @endsection

@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.add_leave') }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('leaves.index') }}">{{ __('messages.employee_leaves') }}</a></li>
                <li class="breadcrumb-item active">{{ __('messages.add_leave') }}</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ __('messages.add_leave') }}</h5>

                        <form action="{{ route('leaves.store') }}" method="POST">
                            @csrf

                            {{-- 1. Employee Selection --}}
                            <div class="row mb-3">
                                <label for="employee_id" class="col-sm-2 col-form-label">{{ __('messages.employee') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="employee_id" name="employee_id" required>
                                        <option value="">{{ __('messages.select_employee') }}</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">
                                                {{ $employee->name }} (ID: {{ $employee->employee_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- 2. Leave Type Selection --}}
                            <div class="row mb-3">
                                <label for="leave_type_id" class="col-sm-2 col-form-label">{{ __('messages.leave_type') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="leave_type_id" name="leave_type_id" required>
                                        <option value="">{{ __('messages.select_leave_type') }}</option>
                                        @foreach ($leaveTypes as $leaveType)
                                            <option value="{{ $leaveType->id }}">{{ $leaveType->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('leave_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- 3. Status Selection (Controls End Date Visibility) --}}
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" name="status" required onchange="toggleEndDate()">
                                        <option value="ongoing">Ongoing (On Leave Now)</option>
                                        <option value="done">Done (Returned/History)</option>
                                    </select>
                                    <small class="text-muted">Select "Ongoing" if the employee is currently away and hasn't returned yet.</small>
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- 4. Start Date --}}
                            <div class="row mb-3">
                                <label for="start_date" class="col-sm-2 col-form-label">{{ __('messages.start_date') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    @error('start_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- 5. End Date (Conditional) --}}
                            <div class="row mb-3" id="end_date_row" style="display: none;">
                                <label for="end_date" class="col-sm-2 col-form-label">{{ __('messages.end_date') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="date" class="form-control" id="end_date" name="end_date">
                                    @error('end_date') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- 6. Reason --}}
                            <div class="row mb-3">
                                <label for="reason" class="col-sm-2 col-form-label">{{ __('messages.reason') }}</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="reason" name="reason" rows="3"></textarea>
                                    @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Buttons --}}
                            <div class="row mb-3">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">{{ __('messages.submit') }}</button>
                                    <a href="{{ route('leaves.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Function to hide/show End Date based on Status
    function toggleEndDate() {
        var status = document.getElementById('status').value;
        var endDateRow = document.getElementById('end_date_row');
        var endDateInput = document.getElementById('end_date');

        if (status === 'done') {
            // If Done: Show End Date & Make it Required
            endDateRow.style.display = 'flex';
            endDateInput.setAttribute('required', 'required');
        } else {
            // If Ongoing: Hide End Date & Remove Required
            endDateRow.style.display = 'none';
            endDateInput.removeAttribute('required');
            endDateInput.value = ''; // Clear value to ensure NULL in DB
        }
    }

    // Run on page load to set initial state
    document.addEventListener('DOMContentLoaded', function() {
        toggleEndDate();
    });
</script>
@endsection