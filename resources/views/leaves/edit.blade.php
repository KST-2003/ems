@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>{{ __('messages.edit_leave') }}</h1>
    </div>

    @if (!$leave || !$leave->employee)
        <div class="alert alert-danger">
            Leave record or associated employee not found.
        </div>
        <a href="{{ route('leaves.index') }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
    @else

        <form action="{{ route('leaves.update', $leave->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="employee_id" class="form-label">{{ __('messages.employee') }}</label>
                <input type="text" class="form-control" value="{{ $leave->employee->name ?? 'N/A' }}" disabled>
                <input type="hidden" name="employee_id" value="{{ $leave->employee_id }}">
                @error('employee_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="leave_type_id" class="form-label">{{ __('messages.leave_type') }}</label>
                <select name="leave_type_id" id="leave_type_id" class="form-control" required>
                    @foreach ($leaveTypes as $type)
                        <option value="{{ $type->id }}" {{ $leave->leave_type_id == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('leave_type_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">{{ __('messages.start_date') }}</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $leave->start_date->format('Y-m-d') }}" required>
                @error('start_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">{{ __('messages.end_date') }}</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $leave->end_date->format('Y-m-d') }}" required>
                @error('end_date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="reason" class="form-label">{{ __('messages.remark') }}</label>
                <textarea name="reason" id="reason" class="form-control">{{ $leave->reason }}</textarea>
                @error('reason')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">{{ __('messages.status') }}</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="Pending" {{ $leave->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ $leave->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ $leave->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">{{ __('messages.update') }}</button>
            <a href="{{ route('employees.leaves', $leave->employee_id) }}" class="btn btn-secondary">{{ __('messages.back') }}</a>
        </form>
    @endif
@endsection