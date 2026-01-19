@extends('layouts.app')

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
                        <h5 class="card-title">New Leave Request</h5>

                        <form action="{{ route('leaves.store') }}" method="POST" id="leaveForm">
                            @csrf

                            {{-- 1. Employee Selection --}}
                            <div class="row mb-3">
                                <label for="employee_id" class="col-sm-2 col-form-label">{{ __('messages.employee') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select class="form-select select2" id="employee_id" name="employee_id" required>
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

                            {{-- 2. Leave Type Selection (Filtered by Allocation) --}}
                            <div class="row mb-3">
                                <label for="leave_type_id" class="col-sm-2 col-form-label">{{ __('messages.leave_type') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="leave_type_id" name="leave_type_id" required disabled>
                                        <option value="">{{ __('Please select an employee first') }}</option>
                                    </select>
                                    <div id="allocation-info" class="mt-2 small text-muted"></div>
                                    @error('leave_type_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- 3. Status Selection --}}
                            <div class="row mb-3">
                                <label for="status" class="col-sm-2 col-form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select class="form-select" id="status" name="status" required onchange="toggleEndDate()">
                                        <option value="ongoing">Ongoing (Currently Away)</option>
                                        <option value="done">Done (Returned/Historical)</option>
                                    </select>
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
                                <label for="reason" class="col-sm-2 col-form-label">{{ __('messages.remark') }}</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Enter reason for leave..."></textarea>
                                    @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

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
    /**
     * Toggles visibility and requirement of the End Date field
     */
    function toggleEndDate() {
        const status = document.getElementById('status').value;
        const endDateRow = document.getElementById('end_date_row');
        const endDateInput = document.getElementById('end_date');

        if (status === 'done') {
            endDateRow.style.display = 'flex';
            endDateInput.setAttribute('required', 'required');
        } else {
            endDateRow.style.display = 'none';
            endDateInput.removeAttribute('required');
            endDateInput.value = '';
        }
    }

    $(document).ready(function() {
        toggleEndDate();

        /**
         * Dynamic Filtering: Fetch only Allocated Leave Types for the chosen employee
         */
        $('#employee_id').on('change', function() {
            const empId = $(this).val();
            const leaveSelect = $('#leave_type_id');
            const infoBox = $('#allocation-info');

            if (!empId) {
                leaveSelect.html('<option value="">Select Employee First</option>').attr('disabled', true);
                infoBox.html('');
                return;
            }

            // You will need to define this route in web.php
            $.ajax({
                url: `/api/employees/${empId}/allocations`,
                method: 'GET',
                success: function(response) {
                    let options = '<option value="">Select Allowed Leave</option>';
                    
                    if (response.length === 0) {
                        options = '<option value="">No leaves allocated to this employee</option>';
                        leaveSelect.attr('disabled', true);
                    } else {
                        response.forEach(alloc => {
                            options += `<option value="${alloc.leave_type_id}" data-max="${alloc.max_allowed_days}">
                                            ${alloc.leave_type.name} (Max: ${alloc.max_allowed_days} days)
                                        </option>`;
                        });
                        leaveSelect.attr('disabled', false);
                    }
                    leaveSelect.html(options);
                },
                error: function() {
                    alert('Error fetching leave allocations.');
                }
            });
        });

        // Show specific allocation details when a leave type is selected
        $('#leave_type_id').on('change', function() {
            const selected = $(this).find(':selected');
            const max = selected.data('max');
            if (max) {
                $('#allocation-info').html(`<span class="text-info"><i class="bi bi-info-circle"></i> This employee is allowed up to <strong>${max} days</strong> for this leave type this year.</span>`);
            } else {
                $('#allocation-info').html('');
            }
        });
    });
</script>
@endsection